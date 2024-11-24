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
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
