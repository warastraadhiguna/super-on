<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Menu Utama';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('number')->label('Nomor Induk'),
                TextInput::make('name')->label('Nama')->required(),
                TextInput::make('email')->label('Email')->required(),
                Textarea::make('address')->label('Alamat')->required(),
                TextInput::make('phone_number')->label('Nomor Telepon')->required(),

                Select::make('role')
                    ->label('Role')
                    ->options(['admin' => 'Administrator', 'teacher' => 'Guru', 'principal' => 'Kepala Sekolah'])
                    ->default('n')
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
                TextColumn::make('number')->label('Nomor Induk')->searchable(['name', 'address', 'number']),
                TextColumn::make('name')->label('Nama'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('address')->label('Alamat'),
                TextColumn::make('phone_number')->label('No Telepon'),
                ImageColumn::make('image')
                    ->label('Foto')
                    ->size(50), // Ukuran gambar
                TextColumn::make('role')
                    ->label('Role')
                    ->formatStateUsing(function ($state) {
                        return $state === 'teacher' ? 'Guru' : ($state === 'principal' ? 'Kepala Sekolah' : 'Admin');
                    }),

                // TextColumn::make('user.name')->label('User'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Pengguna';
    }

    public static function getModelLabel(): string
    {
        return __('Pengguna'); // Label singular
    }

    public static function getPluralModelLabel(): string
    {
        return __('Data Pengguna'); // Label plural
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin'; // ✅ Hanya admin yang bisa melihat
    }
}
