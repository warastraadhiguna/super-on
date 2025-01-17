<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Models\School;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Table;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;
    protected static ?string $navigationGroup = 'Pengaturan Dasar';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('Nama')->required(),
                TextInput::make('address')->label('Alamat')->required(),
                TextInput::make('phone_number')->label('No Telepon')->required(),
                TextInput::make('email')->label('Email')->required(),
                FileUpload::make('manual_book')
                    ->label('Petunjuk Penggunaan')
                    ->disk('public') // Simpan di storage/public
                    ->directory('manual_books') // Folder penyimpanan
                    ->preserveFilenames() // Simpan dengan nama asli
                    ->acceptedFileTypes(['application/pdf']) // Hanya file PDF
                    ->maxSize(5120), // Maksimal 5MB
                Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

            ])
            ->filters([
                //
            ])
            ->actions([
            ])
            ->paginated(false);
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchools::route('/'),
            // 'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }

    public static function getNavigationUrl(): string
    {
        return static::getUrl('edit', ['record' => 1]); // Langsung ke halaman edit School ID 1
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->role === 'admin'; // ✅ Hanya admin yang bisa melihat
    }

    public static function getNavigationLabel(): string
    {
        return 'Sekolah';
    }

    public static function getModelLabel(): string
    {
        return __('Sekolah'); // Label singular
    }

    public static function getPluralModelLabel(): string
    {
        return __('Data Sekolah'); // Label pluSekolah
    }

}
