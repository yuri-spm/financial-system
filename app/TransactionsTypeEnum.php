<?php

namespace App;


use Filament\Support\Contracts\HasLabel;

enum TransactionsTypeEnum:string implements HasLabel
{
    case Expense  = 'expense';
    case Income = 'Income';

    public function getLabel(): ?string
    {
        return match($this){
            self::Expense         => 'Despesa',
            self::Income       => 'Renda'
        };
    }
}
