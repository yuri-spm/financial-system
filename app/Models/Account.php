<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',
        'balance',
        'type',
        'user_id',
        'type'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expense()
    {
        return $this->belongsTo(Transactions::class);
    }
}
