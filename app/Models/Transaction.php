<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'car_id',
        'amount_car',
        'rental_date',
        'pick_up_at',
        'duration',
        'driver',
        'price_total',
        'dp',
        'price_final',
        'status',
    ];

    protected $casts = [
        'rental_date' => 'date',
        'pick_up_at' => 'time',
        'driver' => 'boolean',
        'price_total' => 'decimal:2',
        'dp' => 'decimal:2',
        'price_final' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nik', 'nik');
    }

    public function car()
    {
        return $this->belongsTo(Cars::class);
    }
}