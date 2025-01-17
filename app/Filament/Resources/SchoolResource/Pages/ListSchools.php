<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ListSchools extends ListRecords
{
    protected static string $resource = SchoolResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
    public function getHeaderActions(): array
    {
        return [
            Action::make('edit_school')
                ->label('Edit School')
                ->icon('heroicon-o-pencil')
                ->url(static::getResource()::getUrl('edit', ['record' => 1])),
        ];
    }
}
