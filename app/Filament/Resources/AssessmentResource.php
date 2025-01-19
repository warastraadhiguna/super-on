<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Filament\Resources\AssessmentResource\RelationManagers;
use App\Models\Assessment;
use App\Models\ClassroomPeriodTeacherSubjectRelation;
use Filament\Forms;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required(),
                Hidden::make('classroom_period_teacher_subject_relation_id')
                    ->default(fn () => request('classroom_period_teacher_subject_relation_id')) // 🔥 Ambil dari request
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Penilaian')
                    ->required()
                    ->maxLength(200),

                Textarea::make('note')
                    ->label('Catatan')
                    ->maxLength(500),

                FileUpload::make('document')
                    ->label('Dokumen Penilaian')
                    ->disk('public')
                    ->directory('assessment-documents')
                    ->preserveFilenames()
                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
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
        return $table
            ->recordUrl(fn ($record) => null)
            ->header(function () {
                $relation = ClassroomPeriodTeacherSubjectRelation::find(request('classroom_period_teacher_subject_relation_id'));

                return view('filament.custom.supervision-classroom', [
                    'relation' => $relation
                ]);
            })
            ->columns([
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
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/create'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
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
        return Auth::user()?->role  === 'principal'; // ✅ Hanya admin yang bisa melihat
    }
}