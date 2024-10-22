<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'returned_id',
        'payment_amount',
        'total_payment',
        'status',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'total_payment' => 'decimal:2',
    ];

    public function returned()
    {
        return $this->belongsTo(Returned::class);
    }
}