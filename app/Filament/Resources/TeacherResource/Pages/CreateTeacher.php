<?php

namespace App\Filament\Resources\TeacherResource\Pages;

use App\Filament\Resources\TeacherResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;
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
