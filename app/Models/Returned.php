<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returned extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'return_date',
        'condition_car',
        'price_penalty',
    ];

    protected $casts = [
        'return_date' => 'date',
        'price_penalty' => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}