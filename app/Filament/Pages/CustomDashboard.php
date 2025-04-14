<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TransactionsChart;
use App\Filament\Widgets\TransactionsYuriChart;
use App\Filament\Widgets\CategoriesYuriDoughnut;
use App\Filament\Widgets\TransactionsAmandaChart;
use App\Filament\Widgets\CategoriesAmandaDoughnut;

class CustomDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.custom-dashboard';

    protected static ?string $navigationLabel = 'Dashboard';


    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
            TransactionsYuriChart::class,
            TransactionsAmandaChart::class,
            TransactionsChart::class,
            CategoriesYuriDoughnut::class,
            CategoriesAmandaDoughnut::class
        ];
    }
}
