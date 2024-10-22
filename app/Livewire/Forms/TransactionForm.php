<?php

namespace App\Livewire\Forms;

use App\Models\Cars;
use App\Models\Transaction as ModelsTransaction;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionForm extends Form
{
    public ?ModelsTransaction $transaction;

    public ?int $transactionId = null;

    // public $userId = '';

    // #[Validate('required|exists:cars,id')]
    // public $carId;

    #[Validate('required|integer|min:1')]
    public $amountCar = 1;

    #[Validate('required|date')]
    public $rentalDate;

    #[Validate('required')]
    public $pickUpAt;

    #[Validate('required|integer|min:1')]
    public $duration = 1;

    #[Validate('boolean')]
    public $driver = false;

    #[Validate('required|numeric|min:0')]
    public $priceTotal = 0;

    #[Validate('nullable|numeric|min:0')]
    public $dp = 0;

    #[Validate('required|numeric|min:0')]
    public $priceFinal = 0;

    #[Validate('string')]
    public $status = "Booked";

    public function store($carId)
    {
        $validated = $this->validate();

        $validated['user_id'] = auth()->id();
        $validated['car_id'] = $carId;

        // Pastikan semua field yang diperlukan ada
        $validated['amount_car'] = $this->amountCar;
        $validated['rental_date'] = $this->rentalDate;
        $validated['pick_up_at'] = $this->pickUpAt;
        $validated['duration'] = $this->duration;
        $validated['driver'] = $this->driver;
        $validated['price_total'] = $this->priceTotal;
        $validated['dp'] = $this->dp;
        $validated['price_final'] = $this->priceFinal;
        $validated['status'] = $this->status;

        $transaction = ModelsTransaction::create($validated);

        $car = Cars::findOrFail($carId);
        if ($transaction) {
            $car->decrement('stock', $validated['amount_car']);
        }

        $this->reset();
        return $transaction;
    }

    public function getDp()
    {
        return $this->dp;
    }

    public function setDp($value)
    {
        $this->dp = max(0, floatval($value));
    }

    public function setTransaction(ModelsTransaction $transaction)
    {
        $this->transaction = $transaction;
        $this->transactionId = $transaction->id;
        // $this->userId = $transaction->user_id;
        // $this->carId = $transaction->car_id;
        $this->amountCar = $transaction->amount_car;
        $this->rentalDate = $transaction->rental_date;
        $this->pickUpAt = $transaction->pick_up_at;
        $this->duration = $transaction->duration;
        $this->driver = $transaction->driver;
        $this->priceTotal = $transaction->price_total;
        $this->dp = $transaction->dp;
        $this->priceFinal = $transaction->price_final;
        $this->status = $transaction->status;
    }

    public function take($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);

        $transaction->update(['status' => 'On Rent']);
        $this->reset();
    }

    public function cancel($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);

        $transaction->update(['status' => 'Cancelled']);
        $this->reset();
    }

    public function delete($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);
        $transaction->delete();
    }
}
