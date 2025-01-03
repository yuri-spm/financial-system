<?php

namespace App\Filament\Resources\TransactionsResource\Pages;

use Filament\Actions;
use App\Models\Transactions;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\TransactionsResource;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ManageTransactions extends ManageRecords
{
    protected static string $resource = TransactionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        $filterType = request()->query('filter_tipo_de_categoria');

        $sumExpenses = Transactions::sumExpense();
        $sumIncome = Transactions::sumIncome();

        $tabs[] = Tab::make('Total de Despesas (R$' . number_format($sumExpenses, 2, ',', '.') . ')')
         ->modifyQueryUsing(fn (Builder $query) => $query->expense());

        $tabs[] = Tab::make('Total de Proventos (R$'. number_format($sumIncome, 2, ',', '.'). ')')
         ->modifyQueryUsing(fn(Builder $query) => $query->income());

        return $tabs;
    }

}