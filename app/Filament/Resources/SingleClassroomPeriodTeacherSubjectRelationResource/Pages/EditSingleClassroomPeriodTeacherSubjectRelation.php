<?php

namespace App\Filament\Resources\SingleClassroomPeriodTeacherSubjectRelationResource\Pages;

use App\Filament\Resources\SingleClassroomPeriodTeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSingleClassroomPeriodTeacherSubjectRelation extends EditRecord
{
    protected static string $resource = SingleClassroomPeriodTeacherSubjectRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
