<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomPeriodTeacherSubjectRelationResource\Pages;
use App\Models\ClassroomPeriodTeacherSubjectRelation;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ClassroomPeriodTeacherSubjectRelationResource extends Resource
{
    protected static ?string $model = ClassroomPeriodTeacherSubjectRelation::class;
    protected static ?string $navigationGroup = 'Pengaturan Dasar';
    protected static ?string $navigationIcon = 'heroicon-o-command-line';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Hidden::make('user_id')
                ->default(Auth::id())
                ->required(),

            Select::make('period_id')
                ->label('Periode')
                ->relationship('period', 'name')
                ->options(fn () => \App\Models\Period::where('is_default', '=', 'y')->pluck('name', 'id'))
                ->searchable()
                ->required(),

            Select::make('classroom_id')
                ->label('Kelas')
                ->relationship('classroom', 'name')
                ->options(fn () => \App\Models\Classroom::pluck('name', 'id'))
                ->searchable()
                ->required(),

            Select::make('teacher_subject_relation_id')
                ->label('Pengampu Mapel')
                ->options(
                    fn () => \App\Models\TeacherSubjectRelation::with(['teacher', 'subject'])
                    ->get()
                    ->mapWithKeys(fn ($relation) => [
                        $relation->id => "{$relation->teacher->name} - {$relation->subject->name}"
                    ])
                )
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
            ->recordUrl(fn ($record) => null)
            ->columns([
                TextColumn::make('classroom.name')->label('Kelas')->sortable()->searchable(['classroom.name', 'period.name']),
                TextColumn::make('period.name')->label('Periode')->sortable(),
                TextColumn::make('teacherSubjectRelation.teacher.name')->label('Guru')->sortable(),
                TextColumn::make('teacherSubjectRelation.subject.name')->label('Mapel')->sortable(),
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
            'index' => Pages\ListClassroomPeriodTeacherSubjectRelations::route('/'),
            'create' => Pages\CreateClassroomPeriodTeacherSubjectRelation::route('/create'),
            'edit' => Pages\EditClassroomPeriodTeacherSubjectRelation::route('/{record}/edit'),
        ];
    }


    public static function getNavigationLabel(): string
    {
        return 'Pengajaran';
    }

    public static function getModelLabel(): string
    {
        return __('Pengajaran'); // Label singular
    }

    public static function getPluralModelLabel(): string
    {
        return __('Data Pengajaran'); // Label plural
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role  === 'admin'; // ✅ Hanya admin yang bisa melihat
    }
}
