<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Supervision;
use App\Models\Classroom;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
        Stat::make('Total Pengguna', User::count())
        ->description('Jumlah pengguna yang terdaftar')
        ->color('success'),

        Stat::make('Total Supervisi', Supervision::count())
        ->description('Jumlah supervisi yang sudah dilakukan')
        ->color('info'),

        Stat::make('Total Kelas', Classroom::count())
        ->description('Total jumlah kelas yang ada')
        ->color('primary'),
        ];
    }
}
