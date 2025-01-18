<?php

namespace App\Filament\Resources\CommentResource\Pages;

use App\Filament\Resources\CommentResource;
use Filament\Actions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        CreateAction::make()
            ->url(fn () => route('filament.admin.resources.comments.create', ['supervision_id' => (session('supervision_id') ?? request()->query('supervision_id'))])) // ✅ Tambahkan parameter di tombol Create
        ];
    }

    public function mount(): void
    {
        $relationId = request('supervision_id');
        if (!$relationId) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.'); // ❌ Forbidden jika tidak sesuai
        }
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}