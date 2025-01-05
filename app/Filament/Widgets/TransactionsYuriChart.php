<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Transactions;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TransactionsYuriChart extends ChartWidget
{
    protected static ?string $heading = 'Yuri';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $incomeData = Trend::query(
            Transactions::query()
                ->where('type', 'income')
                ->where('user_id', 1)
        )
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->dateColumn('transaction_date')
        ->sum('amount');

        $expenseData = Trend::query(
            Transactions::query()
                ->where('type', 'expense')
                ->where('user_id', 1)
        )
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->dateColumn('transaction_date')
        ->sum('amount');

        return [
            'datasets' => [
                [
                    'label' => 'Receitas',
                    'data' => $incomeData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#10B981',
                    'borderColor' => '#10B981',
                ],
                [
                    'label' => 'Despesas',
                    'data' => $expenseData->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#EF4444',
                    'borderColor' => '#EF4444',
                ],
            ],
            'labels' => $incomeData->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}