<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $account_yuri = Account::query()
            ->where('user_id', 1)
            ->whereIn('type', ['bank', 'meal_voucher'])
            ->sum('balance'); 
        
        $total_yuri = Account::query()
            ->where('user_id', 1)
            ->where('type', ['wallet'])
            ->sum('balance'); 

        $account_Amanda = Account::query()
            ->where('user_id', 2)
            ->whereIn('type', ['bank', 'meal_voucher'])
            ->sum('balance'); 
        return [
            Stat::make('Conta Bancaria Yuri', $account_yuri),
            Stat::make('Total Guardado Yuri', $total_yuri),
            Stat::make('Conta Bancaria Amanda', $account_Amanda),
        ];
    }
}
