<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\OfficeSpace;
use App\Models\City;
// buat hitung data kota di dashboard
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->color('success'),

            Stat::make('Office Spaces', OfficeSpace::count())
                ->description('Total listed workspaces')
                ->color('warning'),

            Stat::make('Cities Covered', City::count())
                ->description('Cities you operate in')
                ->color('info'),
        ];
    }
}
