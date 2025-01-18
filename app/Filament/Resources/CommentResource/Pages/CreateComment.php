<?php

namespace App\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateComment extends CreateRecord
{
    protected static string $resource = CommentResource::class;

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
        ->url($this->getResource()::getUrl('index', [
                'supervision_id' => request('supervision_id'), // 🔥 Meneruskan parameter
            ]))
                ->extraAttributes(['class' => 'bg-gray-400 hover:bg-gray-300'])
        ];
    }

    protected function getRedirectUrl(): string
    {

        $relationId = $this->record->supervision_id ??  request('supervision_id');

        // Redirect ke halaman index setelah create

        return $this->getResource()::getUrl('index', [
                'supervision_id' => $relationId, // 🔥 Meneruskan parameter
            ]);

    }


    public function getBreadcrumbs(): array
    {
        return [];
    }
}