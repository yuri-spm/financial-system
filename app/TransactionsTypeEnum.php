<?php

namespace App;


use Filament\Support\Contracts\HasLabel;

enum TransactionsTypeEnum:string implements HasLabel
{
    case Expense  = 'expense';
    case Income = 'income';

    public function getLabel(): ?string
    {
        return match($this){
            self::Expense         => 'Despesa',
            self::Income       => 'Renda'
        };
    }
}
