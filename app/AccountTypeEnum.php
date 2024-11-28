<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum AccountTypeEnum:string implements HasLabel
{
    case Bank  = 'bank';
    case Wallet = 'wallet';
    case Credit_Card = 'credit_cart';
    case Meal_Voucher = 'meal_voucher';


    public function getLabel(): ?string
    {
        return match($this){
            self::Bank         => 'Conta Salário',
            self::Wallet       => 'Carteira',
            self::Credit_Card  => 'Cartão de Credito',
            self::Meal_Voucher => 'Vale Refeição'
        };
    }

}
