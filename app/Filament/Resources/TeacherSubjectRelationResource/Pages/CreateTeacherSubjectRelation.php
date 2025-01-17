<?php

namespace App\Filament\Resources\TeacherSubjectRelationResource\Pages;

use App\Filament\Resources\TeacherSubjectRelationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacherSubjectRelation extends CreateRecord
{
    protected static string $resource = TeacherSubjectRelationResource::class;

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Create')
                ->color('primary')
                ->action('create'),
            Action::make('cancel')
                ->label('Cancel')
                ->color('primary')
        ->url($this->getResource()::getUrl('index'))
                ->extraAttributes(['class' => 'bg-gray-400 hover:bg-gray-300'])
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Redirect ke halaman index setelah create
        return $this->getResource()::getUrl('index');
    }
}
