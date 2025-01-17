<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfileResource\Pages;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProfileResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Menu Utama';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('number')->label('Nomor Induk')->disabled(),
                TextInput::make('name')->label('Nama')->disabled()->required(),
                TextInput::make('email')->label('Email')->required(),
                Textarea::make('address')->label('Alamat')->required(),
                TextInput::make('phone_number')->label('Nomor Telepon')->required(),
                Select::make('role')
                    ->label('Role')
                    ->options(['admin' => 'Administrator', 'teacher' => 'Guru', 'principal' => 'Kepala Sekolah'])
                    ->default('n')
                    ->disabled()
                    ->required(),
                FileUpload::make('image')
                    ->label('Foto Guru')
                    ->directory('teacher-images') // Direktori penyimpanan di `storage/app/public`
                    ->image() // Validasi gambar
                    ->maxSize(2048), // Opsional: gambar wajib diunggah
                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        ->required(fn ($livewire) => $livewire instanceof CreateRecord) // Wajib diisi hanya saat membuat user baru
                        ->dehydrated(fn ($state) => filled($state)) // Hanya menyimpan password jika ada input
                        ->afterStateHydrated(fn ($state, callable $set) => $set('password', '')) // Kosongkan setelah load data
                        ->maxLength(255),
                Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
            ])
        ;
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getNavigationUrl(): string
    {
        return static::getUrl('edit', ['record' => Auth::id()]); // Langsung ke halaman edit School ID 1
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfiles::route('/'),
            // 'create' => Pages\CreateProfile::route('/create'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Profil';
    }

    public static function getModelLabel(): string
    {
        return __('Profil'); // Label singular
    }

    public static function getPluralModelLabel(): string
    {
        return __('Data Profil'); // Label pluSekolah
    }
}
