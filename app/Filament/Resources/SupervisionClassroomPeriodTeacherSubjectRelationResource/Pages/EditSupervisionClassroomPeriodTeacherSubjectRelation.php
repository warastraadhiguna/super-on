<?php

namespace App\Filament\Resources\SupervisionClassroomPeriodTeacherSubjectRelationResource\Pages;

use App\Filament\Resources\SupervisionClassroomPeriodTeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupervisionClassroomPeriodTeacherSubjectRelation extends EditRecord
{
    protected static string $resource = SupervisionClassroomPeriodTeacherSubjectRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
