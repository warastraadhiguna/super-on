<?php

namespace App\Filament\Resources\SupervisionClassroomPeriodTeacherSubjectRelationResource\Pages;

use App\Filament\Resources\SupervisionClassroomPeriodTeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupervisionClassroomPeriodTeacherSubjectRelations extends ListRecords
{
    protected static string $resource = SupervisionClassroomPeriodTeacherSubjectRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
