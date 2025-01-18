<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use App\Filament\Resources\CommentResource\Pages;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('supervision_id')
                    ->default(fn () => request('supervision_id')) // 🔥 Ambil dari request
                    ->required(),
                Hidden::make('user_id')->default(Auth::id()), // 🔥 Simpan user_id secara otomatis
                Textarea::make('comment')->label('Komentar')->required(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->when(request('supervision_id'), function ($query) {
            $query->where('supervision_id', request('supervision_id'));
        });
    }

    public static function table(Table $table): Table
    {
        return $table
        ->defaultSort('created_at', 'desc')
            ->recordUrl(fn ($record) => null)
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Dibuat oleh'),
                Tables\Columns\TextColumn::make('comment')->label('Komentar')->wrap(),
                Tables\Columns\TextColumn::make('is_read')
                                ->label('Dibaca?')
                                ->formatStateUsing(fn ($state) => $state === 'y' ? '✅ Ya' : '❌ Belum'),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat pada')->dateTime(),
            ])
            ->actions([
            EditAction::make()
                ->visible(
                    fn ($record) => $record->is_read === 'n' && Auth::id() === $record->user_id
                ),

            DeleteAction::make()
                ->visible(fn ($record) => $record->is_read === 'n' && Auth::id() === $record->user_id), // 🔥 Hanya hapus jika is_read = 'n' dan milik user
            Action::make('markAsRead')
                ->label('Tandai Sudah Dibaca')
                ->icon('heroicon-o-check') // 🟢 Tambahkan ikon checklist
                ->color('success') // 🟢 Warna hijau
                ->visible(fn ($record) => $record->is_read === 'n' && Auth::id() !== $record->user_id) // 🔥 Hanya tampil jika belum dibaca
                ->requiresConfirmation() // 🔥 Munculkan popup konfirmasi sebelum eksekusi
                ->action(fn ($record) => $record->update(['is_read' => 'y'])), // 🔥 Update langsung ke database
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Komentar';
    }

    public static function getModelLabel(): string
    {
        return __('Komentar'); // Label singular
    }

    public static function getPluralModelLabel(): string
    {
        return __('Data Komentar'); // Label pluSekolah
    }
}
