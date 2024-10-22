<?php

namespace App\Livewire\Forms;

use App\Models\Payment;
use App\Models\Returned;
use App\Models\Transaction as ModelsTransaction;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ReturnForm extends Form
{
    public ?Returned $return;

    #[Validate('nullable|string')]
    public $conditionCar;

    #[Validate('nullable|numeric|min:0')]
    public $pricePenalty;

    public function store($transactionId)
    {
        $this->validate();
        $returnDate = now();

        $transaction = ModelsTransaction::findOrFail($transactionId);
        $rentalEndDate = \Carbon\Carbon::parse($transaction->rental_date)->addDays($transaction->duration);

        $pricePenalty = 0;
        if ($returnDate > $rentalEndDate) {
            $daysLate = $returnDate->diffInDays($rentalEndDate);
            $pricePenalty = $daysLate * 100000;
        }

        $return = Returned::create([
            'transaction_id' => $transactionId,
            'return_date' => $returnDate,
            'condition_car' => $this->conditionCar,
            'price_penalty' => $pricePenalty + $this->pricePenalty,
        ]);

        $transaction->update([
            'status' => 'Returned',
        ]);
        $return->transaction()->update(['status' => 'Returned']);

        $car = $transaction->car;
        $car->update([
            'stock' => $car->stock + $transaction->amount_car,
        ]);

        if ($return) {
            Payment::create([
                'returned_id' => $return->id,
                'payment_amount' => 0,
                'total_payment' => $return->transaction->price_total,
                'status' => 'Unpaid',
            ]);
        }

        return $return;
    }

    // public function setReturn(Returned $return)
    // {
    //     $this->$return = $return;
    //     $this->transactionId = $return->transaction_id;
    //     $this->conditionCar = $return->condition_car;
    //     $this->pricePenalty = $return->price_penalty;
    // }

    // public function update()
    // {
    //     $validatedData = $this->validate();
    //     $return = Returned::findOrFail($this->transactionId);
    //     $return->update($validatedData);
    //     $this->reset();
    // }

    // public function delete($id)
    // {
    //     $return = Returned::findOrFail($id);
    //     $return->delete();
    // }
}