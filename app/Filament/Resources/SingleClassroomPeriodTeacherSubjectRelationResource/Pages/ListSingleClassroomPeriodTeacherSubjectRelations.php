<?php

namespace App\Filament\Resources\SingleClassroomPeriodTeacherSubjectRelationResource\Pages;

use App\Filament\Resources\SingleClassroomPeriodTeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSingleClassroomPeriodTeacherSubjectRelations extends ListRecords
{
    protected static string $resource = SingleClassroomPeriodTeacherSubjectRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
