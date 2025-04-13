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
            ->whereIn('type', ['bank'])
            ->sum('balance'); 
        
        $account_caju_yuri = Account::query()
            ->where('user_id', 1)
            ->whereIn('type', ['meal_voucher'])
            ->sum('balance'); 
        
        $total_yuri = Account::query()
            ->where('user_id', 1)
            ->where('type', ['wallet'])
            ->sum('balance'); 

        $account_amanda = Account::query()
            ->where('user_id', 2)
            ->whereIn('type', ['bank', 'meal_voucher'])
            ->sum('balance'); 
        $total_amanda = Account::query()
            ->where('user_id', 2)
            ->where('type', ['wallet'])
            ->sum('balance'); 

        $famaily_total = Account::query()
            ->whereIn('user_id', [1, 2])
            ->whereIn('type', ['bank'])
            ->sum('balance');
        return [
            Stat::make('Conta Bancaria Yuri', number_format($account_yuri, 2, ',', '.')),
            Stat::make('Caju Yuri', number_format($account_caju_yuri, 2, ',', '.')),
            Stat::make('Total Guardado Yuri',number_format($total_yuri, 2, ',', '.')),
            Stat::make('Conta Bancaria Amanda', number_format($account_amanda, 2, ',', '.')),
            Stat::make('Total Guardado Amanda',number_format($total_amanda, 2, ',', '.')),
            Stat::make('Total Familia(Mensal)', number_format($famaily_total, 2, ',', '.')),
        ];
    }
}
