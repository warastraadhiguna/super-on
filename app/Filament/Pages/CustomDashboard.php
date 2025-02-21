<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Models\Announcement;
use App\Models\School;
use Filament\Pages\Page;
use Filament\Widgets\AccountWidget;
use Illuminate\Support\Facades\Auth;

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home'; // 🔥 Ikon sidebar
    protected static ?string $navigationLabel = 'Dashboard'; // 🔥 Label di sidebar
    protected static ?string $navigationGroup = 'Menu Utama';

    protected static string $view = 'filament.pages.custom-dashboard';
    public static function shouldRegisterNavigation(): bool
    {
        return true; // ✅ Munculkan di sidebar
    }

    protected function getHeaderWidgets(): array
    {

        return array_filter([
            Auth::user()->role === 'admin' ? StatsOverview::class : null,
            AccountWidget::class,
        ]);

    }

    public function getTitle(): string
    {
        return 'Dashboard'; // 🔥 Judul halaman
    }

    public function getViewData(): array
    {
        return [
            'manual_book' => School::first()->manual_book,
            'dashboardImage' => asset('images/dashboardImage.png'),
            'announcements' => Announcement::where("is_published", "1")->get(),
        ];
    }
}
