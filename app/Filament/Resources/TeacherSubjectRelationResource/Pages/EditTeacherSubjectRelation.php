<?php

namespace App\Filament\Resources\TeacherSubjectRelationResource\Pages;

use App\Filament\Resources\TeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeacherSubjectRelation extends EditRecord
{
    protected static string $resource = TeacherSubjectRelationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
