<?php

namespace App\Filament\Resources\ClassroomPeriodTeacherSubjectRelationResource\Pages;

use App\Filament\Resources\ClassroomPeriodTeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroomPeriodTeacherSubjectRelation extends EditRecord
{
    protected static string $resource = ClassroomPeriodTeacherSubjectRelationResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirect ke halaman index setelah create
        return $this->getResource()::getUrl('index');
    }
}
