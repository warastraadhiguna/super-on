<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SingleAssessmentResource\Pages;
use App\Filament\Resources\SingleAssessmentResource\RelationManagers;
use App\Models\Assessment;
use App\Models\SingleAssessment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SingleAssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

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
            ->columns([
                TextColumn::make('user.name')->label('Penilai')->sortable()->searchable(),
                TextColumn::make('name')->label('Nama Penilaian')->sortable()->searchable(),
                TextColumn::make('note')->label('Catatan')->limit(50),
                TextColumn::make('document')
                    ->label('Dokumen')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : 'Tidak Ada File')
                    ->url(fn ($record) => $record->document ? asset('storage/' . $record->document) : null)
                    ->color(fn ($state) => $state ? 'primary' : 'gray')
                    ->openUrlInNewTab(),
                TextColumn::make('created_at')->label('Dibuat Pada')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListSingleAssessments::route('/'),
            'create' => Pages\CreateSingleAssessment::route('/create'),
            'edit' => Pages\EditSingleAssessment::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Penilaian');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Berkas Penilaian');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // ❌ Tidak akan muncul di sidebar
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role  === 'teacher'; // ✅ Hanya admin yang bisa melihat
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}