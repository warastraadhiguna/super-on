<?php

namespace App\Filament\Resources\SingleAssessmentResource\Pages;

use App\Filament\Resources\SingleAssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSingleAssessment extends EditRecord
{
    protected static string $resource = SingleAssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
