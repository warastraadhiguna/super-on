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
use App\Models\Supervision;
use Filament\Forms\Components\FileUpload;
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
                FileUpload::make('document')
                    ->label('Dokumen')
                    ->disk('public') // Gunakan penyimpanan public agar bisa diakses
                    ->directory('comment-documents') // Simpan dalam folder ini
                    ->preserveFilenames() // Gunakan nama asli file
                    ->acceptedFileTypes(['application/pdf','image/jpg','image/png',  'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']) // Hanya menerima file PDF dan Word
                    ->maxSize(10240),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // return parent::getEloquentQuery()->when(request('supervision_id'), function ($query) {
        //     $query->where('supervision_id', request()->query('supervision_id'));
        // });

        if (request()->has('supervision_id')) {
            session(['supervision_id' => request()->query('supervision_id')]);
        }

        return parent::getEloquentQuery()->when(session('supervision_id'), function ($query) {
            $query->where('supervision_id', session('supervision_id'));
        });

    }

    public static function table(Table $table): Table
    {
        return $table
        ->header(function () {
            $supervision = Supervision::find(session('supervision_id') ?? request()->query('supervision_id')); // 🔥 Ambil data supervision dengan ID 1

            return view('filament.custom.supervision-header', [
                'supervision' => $supervision
            ]);
        })
        ->defaultSort('created_at', 'desc')
        ->striped()
            ->recordUrl(fn ($record) => null)
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Dibuat oleh'),
                Tables\Columns\TextColumn::make('comment')->label('Komentar')->wrap(),
                Tables\Columns\TextColumn::make('document')
                    ->label('Dokumen')
                    ->formatStateUsing(fn ($state) => $state ? basename($state) : 'Tidak Ada File')
                    ->url(fn ($record) => $record->document ? asset('storage/' . $record->document) : null) // 🔥 Buka file di tab baru
                    ->openUrlInNewTab()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
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
                ->action(function ($record, $livewire) {
                    $record->update(['is_read' => 'y']); // 🔥 Update langsung ke database
                    $livewire->dispatch('refreshTable'); // 🔥 Memastikan tabel direfresh tanpa kehilangan filter
                }),
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

    public static function shouldRegisterNavigation(): bool
    {
        return false; // ❌ Tidak akan muncul di sidebar
    }

}