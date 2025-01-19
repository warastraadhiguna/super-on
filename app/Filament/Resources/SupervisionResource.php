<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupervisionResource\Pages;
use App\Models\ClassroomPeriodTeacherSubjectRelation;
use App\Models\Supervision;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SupervisionResource extends Resource
{
    protected static ?string $model = Supervision::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
        return $table
            ->recordUrl(fn ($record) => null)
            ->header(function () {
                $relation = ClassroomPeriodTeacherSubjectRelation::find(request('classroom_period_teacher_subject_relation_id')); // 🔥 Ambil data classroom dengan ID 1

                return view('filament.custom.supervision-classroom', [
                    'relation' => $relation
                ]);
            })
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
                Action::make('supervision')
                    ->label('Komentar')
                    ->color('info')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->url(fn ($record) => route('filament.admin.resources.comments.index', [
                        'supervision_id' => $record->id
                    ])) // 🔥 Mengarahkan ke halaman List SingleSupervision dengan filter
                    ->openUrlInNewTab(), // 🔥 Buka di tab baru
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
            'index' => Pages\ListSupervisions::route('/'),
            'create' => Pages\CreateSupervision::route('/create'),
            'edit' => Pages\EditSupervision::route('/{record}/edit'),
        ];
    }

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

    public static function canViewAny(): bool
    {
        return Auth::user()?->role !== 'teacher'; // ✅ Hanya admin yang bisa melihat
    }

    public static function canCreate(): bool
    {
        return false; // ❌ Menghilangkan tombol tambah
    }
}