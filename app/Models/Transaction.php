<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'id',
        'value',
        'description',
        'method',
        'cardNumber',
        'cardHolderName',
        'cardExpirationDate',
        'cardCvv',
    ];
}
