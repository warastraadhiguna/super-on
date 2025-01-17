<?php

namespace App\Filament\Resources\TeacherSubjectRelationResource\Pages;

use App\Filament\Resources\TeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeacherSubjectRelations extends ListRecords
{
    protected static string $resource = TeacherSubjectRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
