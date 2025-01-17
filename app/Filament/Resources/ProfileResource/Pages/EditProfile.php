<?php

namespace App\Filament\Resources\ProfileResource\Pages;

use App\Filament\Resources\ProfileResource;
use App\Filament\Resources\SchoolResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditProfile extends EditRecord
{
    protected static string $resource = ProfileResource::class;

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
                ->label('Download Gambar')
                ->visible(fn ($record) => $record && $record->image) // Tampilkan hanya jika ada file
                ->url(fn ($record) => asset('storage/' . $record->image))
                ->extraAttributes(['class' => 'bg-gray-400 hover:bg-gray-300'])
                ->openUrlInNewTab(),

        ];
    }

    public function getRecord(): Model
    {
        return User::getModel()::findOrFail(Auth::id());
    }
}
