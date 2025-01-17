<?php

namespace App\Filament\Resources\ClassroomPeriodTeacherSubjectRelationResource\Pages;

use App\Filament\Resources\ClassroomPeriodTeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomPeriodTeacherSubjectRelations extends ListRecords
{
    protected static string $resource = ClassroomPeriodTeacherSubjectRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
