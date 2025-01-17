<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SingleSupervisionResource\Pages;
use App\Models\Supervision;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SingleSupervisionResource extends Resource
{
    protected static ?string $model = Supervision::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        // dd(request('classroom_period_teacher_subject_relation_id'));
        return $form
            ->schema([
                Hidden::make('classroom_period_teacher_subject_relation_id')
                    ->default(fn () => request('classroom_period_teacher_subject_relation_id')) // 🔥 Ambil dari request
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Berkas')
                    ->required()
                    ->maxLength(255),

                TextInput::make('link')
                    ->label('Tautan')
                    ->url()
                    ->nullable()
                    ->maxLength(500),
                Textarea::make('note')
                    ->label('Catatan')
                    ->maxLength(500),
                FileUpload::make('document')
                    ->label('Dokumen Supervisi')
                    ->disk('public') // Gunakan penyimpanan public agar bisa diakses
                    ->directory('supervision-documents') // Simpan dalam folder ini
                    ->preserveFilenames() // Gunakan nama asli file
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']) // Hanya menerima file PDF dan Word
                    ->maxSize(10240),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->when(request('classroom_period_teacher_subject_relation_id'), function ($query) {
            $query->where('classroom_period_teacher_subject_relation_id', request('classroom_period_teacher_subject_relation_id'));
        });
    }

    public static function table(Table $table): Table
    {
        // dd(request('classroom_period_teacher_subject_relation_id'));
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->sortable()->searchable(['name']),
                TextColumn::make('note')->label('Catatan')->limit(50),
            TextColumn::make('link')
                    ->label('Tautan')
                    ->url(fn ($record) => $record->link) // 🔥 Buat agar bisa diklik
                    ->openUrlInNewTab() // 🔥 Buka di tab baru
                    ->formatStateUsing(fn ($state) => $state ? 'Buka Tautan' : 'Tidak Ada Link')
                    ->color(fn ($state) => $state ? 'primary' : 'gray'),

                TextColumn::make('document')
                    ->label('Dokumen')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : 'Tidak Ada File')
                    ->url(fn ($record) => $record->document ? asset('storage/' . $record->document) : null) // 🔥 Buka file di tab baru
                    ->openUrlInNewTab()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSingleSupervisions::route('/'),
            'create' => Pages\CreateSingleSupervision::route('/create'),
            'edit' => Pages\EditSingleSupervision::route('/{record}/edit'),
        ];
    }

    // public static function getNavigationLabel(): string
    // {
    //     return 'Berkas';
    // }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // ❌ Tidak akan muncul di sidebar
    }

    public static function getModelLabel(): string
    {
        return __('Berkas'); // Label singular
    }

    public static function getPluralModelLabel(): string
    {
        return __('Data Berkas'); // Label plural
    }
}
