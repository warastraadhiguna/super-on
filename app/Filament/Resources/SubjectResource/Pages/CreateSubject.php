<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateSubject extends CreateRecord
{
    protected static string $resource = SubjectResource::class;
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
