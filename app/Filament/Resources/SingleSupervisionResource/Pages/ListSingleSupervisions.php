<?php

namespace App\Filament\Resources\SingleSupervisionResource\Pages;

use App\Filament\Resources\SingleSupervisionResource;
use App\Models\ClassroomPeriodTeacherSubjectRelation;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListSingleSupervisions extends ListRecords
{
    protected static string $resource = SingleSupervisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
        CreateAction::make()
            ->url(fn () => route('filament.admin.resources.single-supervisions.create', ['classroom_period_teacher_subject_relation_id' => request('classroom_period_teacher_subject_relation_id')])) // ✅ Tambahkan parameter di tombol Create
        ];
    }

    public function mount(): void
    {
        $relationId = request('classroom_period_teacher_subject_relation_id');
        if (!$relationId) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.'); // ❌ Forbidden jika tidak sesuai
        }
        if ($relationId) {
            $relation = ClassroomPeriodTeacherSubjectRelation::with('teacherSubjectRelation')
                ->where('id', $relationId)
                ->first();
            if (!$relation || $relation->teacherSubjectRelation->teacher_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.'); // ❌ Forbidden jika tidak sesuai
            }
        }
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}