<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherSubjectRelationResource\Pages;
use App\Filament\Resources\TeacherSubjectRelationResource\RelationManagers;
use App\Models\TeacherSubjectRelation;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TeacherSubjectRelationResource extends Resource
{
    protected static ?string $model = TeacherSubjectRelation::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Pengaturan Mapel';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Hidden::make('user_id')
                ->default(Auth::id())
                ->required(),

            Select::make('teacher_id')
                ->label('Teacher')
                ->relationship('teacher', 'name')
                ->options(fn () => \App\Models\User::where('role', '!=', 'admin')->pluck('name', 'id'))
                ->searchable()
                ->required(),

            Select::make('subject_id')
                ->label('Subject')
                ->relationship('subject', 'name')
                ->options(fn () => \App\Models\Subject::pluck('name', 'id'))
                ->searchable()
                ->required(),

            Textarea::make('note')
                ->label('Notes')
                ->maxLength(255)
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('teacher.name')->label('Guru')->sortable()->searchable(['teacher.name', 'subject.name']),
                TextColumn::make('subject.name')->label('Mata Pelajaran')->sortable(),
                TextColumn::make('note')->label('Catatan')->limit(50),
                // TextColumn::make('user.name')->label('User')->sortable(),
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
            'index' => Pages\ListTeacherSubjectRelations::route('/'),
            'create' => Pages\CreateTeacherSubjectRelation::route('/create'),
            'edit' => Pages\EditTeacherSubjectRelation::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Pengampu Mapel';
    }

    public static function getModelLabel(): string
    {
        return __('Pengampu Mapel'); // Label singular
    }

    public static function getPluralModelLabel(): string
    {
        return __('Data Pengampu Mapel'); // Label plural
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin'; // ✅ Hanya admin yang bisa melihat
    }
}
