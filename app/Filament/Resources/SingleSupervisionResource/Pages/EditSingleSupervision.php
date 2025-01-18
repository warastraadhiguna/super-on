<?php

namespace App\Filament\Resources\SingleSupervisionResource\Pages;

use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\SingleSupervisionResource;
use App\Models\ClassroomPeriodTeacherSubjectRelation;

class EditSingleSupervision extends EditRecord
{
    protected static string $resource = SingleSupervisionResource::class;

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->color('primary')
                ->action('save'),
            Action::make('cancel')
                ->label('Cancel')
                ->color('primary')
                ->url($this->getResource()::getUrl('index', [
                        'classroom_period_teacher_subject_relation_id' => $this->record->classroom_period_teacher_subject_relation_id, // 🔥 Meneruskan parameter
                    ]))
                ->extraAttributes(['class' => 'bg-gray-400 hover:bg-gray-300']),
            Action::make('download_manual')
                ->label('Download File')
                ->visible(fn ($record) => $record && $record->document) // Tampilkan hanya jika ada file
                ->url(fn ($record) => asset('storage/' . $record->document))
                ->extraAttributes(['class' => 'bg-gray-400 hover:bg-gray-300'])
                ->openUrlInNewTab(),
        ];
    }


    protected function getRedirectUrl(): string
    {
        $relationId = $this->record->classroom_period_teacher_subject_relation_id;

        // Redirect ke halaman index setelah create

        return $this->getResource()::getUrl('index', [
                'classroom_period_teacher_subject_relation_id' => $relationId, // 🔥 Meneruskan parameter
            ]);

    }

    protected function beforeFill(): void
    {
        $relationId = $this->record->classroom_period_teacher_subject_relation_id;

        if (!$relationId) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $relation = ClassroomPeriodTeacherSubjectRelation::with('teacherSubjectRelation')
            ->where('id', $relationId)
            ->first();

        if (!$relation || $relation->teacherSubjectRelation->teacher_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}