<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transactions;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;

class CategoriesYuriDoughnut extends ChartWidget
{
   
    public ?string $filter = null;
    
    protected static ?string $heading = 'Gastos por Categoria - Yuri';

    
    public function mount(): void
    {
        parent::mount();
        
      
        $this->filter = (string) now()->month;
        
       
        $this->updateHeading();
    }
    
    protected function updateHeading(): void
    {
        $meses = $this->getFilters();
        $mesSelecionado = $meses[$this->filter] ?? '';
        static::$heading = "Gastos por Categoria - {$mesSelecionado} (Yuri)";
    }

    protected function getData(): array
    {
        
        $selectedMonth = $this->filter ?? now()->month;
       
        $results = Transactions::selectRaw('categories.name as category, SUM(transactions.amount) as total')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->whereMonth('transaction_date', (int)$selectedMonth)
            ->whereYear('transaction_date', Carbon::now()->year)
            ->where('account_id', 1)
            ->groupBy('categories.name')
            ->get();
        
        $labels = $results->map(function ($item) {
            $valorFormatado = 'R$ ' . number_format($item->total, 2, ',', '.');
            return "{$item->category} ({$valorFormatado})";
        })->toArray();
        
        $data = $results->pluck('total')->toArray();
        
        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => [
                        '#10B981',
                        '#3B82F6',
                        '#F59E0B',
                        '#EF4444',
                        '#6366F1',
                        '#8B5CF6'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => \Illuminate\Support\Js::from('function(context) {
                            return context.label;
                        }'),
                    ],
                ],
            ],
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            '1' => 'Janeiro',
            '2' => 'Fevereiro',
            '3' => 'Março',
            '4' => 'Abril',
            '5' => 'Maio',
            '6' => 'Junho',
            '7' => 'Julho',
            '8' => 'Agosto',
            '9' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        ];
    }
    
   
    protected function getDefaultFilterValue(): ?string
    {
        return (string) now()->month;
    }
    
    protected function filterFormChanged(): void
    {
        $this->updateHeading();
    }
}