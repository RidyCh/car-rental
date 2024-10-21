<?php

namespace App\Livewire\Forms;

use App\Models\Transaction as ModelsTransaction;
use Livewire\Attributes\Validate;
use Livewire\Form;

class TransactionForm extends Form
{
    public ?ModelsTransaction $transaction;

    #[Validate('required|string|max:255|exists:users,nik')]
    public $nik = '';

    #[Validate('required|exists:cars,id')]
    public $carId;

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

    #[Validate('required|numeric|min:0')]
    public $dp = 0;

    #[Validate('required|numeric|min:0')]
    public $priceFinal = 0;

    #[Validate('required|in:Booked,On Rent,Returned,Cancelled')]
    public $status = 'Booked';

    public function store()
    {
        $validated = $this->validate();

        $transaction = ModelsTransaction::create($validated);

        $this->reset();
        return $transaction;
    }

    public function setTransaction(ModelsTransaction $transaction)
    {
        $this->transaction = $transaction;
        $this->nik = $transaction->nik;
        $this->carId = $transaction->car_id;
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

    public function update()
    {
        $validated = $this->validate();

        $transaction = ModelsTransaction::findOrFail($this->transaction->id);

        $transaction->update($validated);
        $this->reset();
    }

    public function delete($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);
        $transaction->delete();
    }
}