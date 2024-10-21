<?php

namespace App\Livewire\Forms;

use App\Models\Payment as ModelsPayment;
use App\Models\Transaction;
use App\Models\Returned;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PaymentForm extends Form
{
    public ?ModelsPayment $payment;

    public ?int $transactionId = null;
    public float $price = 0;

    #[Validate('required|decimal:2|min:0')]
    public $paymentAmount = 0;

    #[Validate('required|in:Paid,Unpaid')]
    public $status = 'Unpaid';

    #[Validate('required|exists:returneds,id')]
    public $returnedId;

    public function setTransaction($transactionId)
    {
        $this->transactionId = $transactionId;
        $transaction = Transaction::with('returned')->findOrFail($transactionId);

        $this->price = $transaction->price_final;

        if ($transaction->returned) {
            $this->price += $transaction->returned->price_penalty;
        }
    }

    // public function store($transactionId)
    // {
    //     $validated = $this->validate();

    //     $payment = ModelsPayment::create($validated);

    //     $this->reset();
    //     return $payment;
    // }

    public function setPayment(ModelsPayment $payment)
    {
        $this->payment = $payment;
        $this->paymentAmount = $payment->payment_amount;
        $this->status = $payment->status;
        $this->returnedId = $payment->returned_id;
    }

    public function update()
    {
        $validated = $this->validate();

        $payment = ModelsPayment::findOrFail($this->payment->id);

        $payment->update($validated);
        $this->reset();
    }

    // public function delete($id)
    // {
    //     $payment = ModelsPayment::findOrFail($id);
    //     $payment->delete();
    // }
}
