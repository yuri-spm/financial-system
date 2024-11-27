<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionsLogs extends Model
{
    protected $fillable = [
        'transaction_id',
        'action',
        'old_data',
        'new_data'
    ];
}
