<?php

namespace App\Filament\Resources\SingleAssessmentResource\Pages;

use App\Filament\Resources\SingleAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSingleAssessments extends ListRecords
{
    protected static string $resource = SingleAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
