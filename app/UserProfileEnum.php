<?php

namespace App;

use Filament\Support\Contracts\HasLabel;

enum UserProfileEnum:string implements HasLabel
{
    case User  = 'user';
    case Admin = 'admin';

    public function getLabel(): ?string
    {
        return match($this){
            self::User  => 'User',
            self::Admin => 'Admin'
        };
    }
}
