<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Models\ClassroomPeriodTeacherSubjectRelation;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateAssessment extends CreateRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Create')
                ->color('primary')
                ->action('create'),
            Action::make('cancel')
                ->label('Cancel')
                ->color('primary')
        ->url($this->getResource()::getUrl('index', [
                'classroom_period_teacher_subject_relation_id' => request('classroom_period_teacher_subject_relation_id'), // 🔥 Meneruskan parameter
            ]))
                ->extraAttributes(['class' => 'bg-gray-400 hover:bg-gray-300'])
        ];
    }

    protected function getRedirectUrl(): string
    {

        $relationId = $this->record->classroom_period_teacher_subject_relation_id ??  request('classroom_period_teacher_subject_relation_id');

        // Redirect ke halaman index setelah create

        return $this->getResource()::getUrl('index', [
                'classroom_period_teacher_subject_relation_id' => $relationId, // 🔥 Meneruskan parameter
            ]);

    }

    protected function beforeFill(): void
    {
        $relationId = request('classroom_period_teacher_subject_relation_id');

        if (!$relationId) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $relation = ClassroomPeriodTeacherSubjectRelation::with('teacherSubjectRelation')
            ->where('id', $relationId)
            ->first();

        if (!$relation) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}