<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSchool extends EditRecord
{
    protected static string $resource = SchoolResource::class;
    public function getHeaderActions(): array
    {
        return [

        ];
    }
    public function getBreadcrumbs(): array
    {
        return [];
    }
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save')
                ->color('primary')
                ->action('save'),
            Action::make('download_manual')
                ->label('Download Manual Book')
                ->visible(fn ($record) => $record && $record->manual_book) // Tampilkan hanya jika ada file
                ->url(fn ($record) => asset('storage/' . $record->manual_book))
                ->extraAttributes(['class' => 'bg-gray-400 hover:bg-gray-300'])
                ->openUrlInNewTab(),

        ];
    }

    public function getRecord(): Model
    {
        return SchoolResource::getModel()::findOrFail(1); // Paksa hanya bisa edit ID 1
    }
}
