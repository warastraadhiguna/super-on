<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\ClassroomPeriodTeacherSubjectRelation;
use App\Filament\Resources\SupervisionClassroomPeriodTeacherSubjectRelationResource\Pages;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;

class SupervisionClassroomPeriodTeacherSubjectRelationResource extends Resource
{
    protected static ?string $model = ClassroomPeriodTeacherSubjectRelation::class;
    protected static ?string $navigationGroup = 'Menu Utama';
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

        return parent::getEloquentQuery()
            ->whereHas('period', function ($query) {
                $query->where('is_default', 'y'); // 🔥 Tambahkan kondisi period is_default = 'y'
            });
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
                TextColumn::make('assessment_score')->label('Nilai')
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('supervision')
                    ->label('Berkas')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(fn ($record) => route('filament.admin.resources.supervisions.index', [
                        'classroom_period_teacher_subject_relation_id' => $record->id
                    ])) // 🔥 Mengarahkan ke halaman List SingleSupervision dengan filter
                    ->openUrlInNewTab(), // 🔥 Buka di tab baru
                Action::make('assessment')
                    ->label('Penilaian')
                    ->color('secondary')
                    ->icon('heroicon-o-document-arrow-up')
                    ->url(fn ($record) => route('filament.admin.resources.assessments.index', [
                        'classroom_period_teacher_subject_relation_id' => $record->id
                    ])) // 🔥 Mengarahkan ke halaman List SingleSupervision dengan filter
                    ->openUrlInNewTab(), // 🔥 Buka di tab baru
                Action::make('assessment')
                    ->label('Nilai')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('success')
                    ->visible(fn () => Auth::user()?->role === 'principal')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('assessment_score')
                            ->label('Nilai')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        // dd($record); // 🔥 Cek apakah data dari form masuk
                        $record->update(['assessment_score' => $data['assessment_score']]);
                    })
                    ->modalHeading('Beri Nilai')
                    ->modalSubmitActionLabel('Simpan')
                    ->modalCancelActionLabel('Batal')
                    ->requiresConfirmation(),
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
            'index' => Pages\ListSupervisionClassroomPeriodTeacherSubjectRelations::route('/'),
            'create' => Pages\CreateSupervisionClassroomPeriodTeacherSubjectRelation::route('/create'),
            'edit' => Pages\EditSupervisionClassroomPeriodTeacherSubjectRelation::route('/{record}/edit'),
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
        return Auth::user()?->role === 'principal'; // ✅ Hanya admin yang bisa melihat
    }

    public static function canCreate(): bool
    {
        return false; // ❌ Menghilangkan tombol tambah
    }
}