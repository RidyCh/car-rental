<?php

namespace App\Livewire\Forms;

use App\Models\Payment as ModelsPayment;
use App\Models\Returned;
use App\Models\Transaction;
use Livewire\Attributes\Validate;
use Livewire\Form;

class PaymentForm extends Form
{
    public ?ModelsPayment $payment;

    public ?int $transactionId = null;
    public float $price = 0;

    #[Validate('required|min:0')]
    public $paymentAmount = 0;
    public $totalPayment = 0;

    #[Validate('required|in:Paid,Unpaid')]
    public $status = 'Unpaid';

    public function setTransaction($transactionId)
    {
        $this->transactionId = $transactionId;
        $transaction = Transaction::with('returned')->findOrFail($transactionId);

        $this->price = $transaction->price_final;

        if ($transaction->returned) {
            $this->price += $transaction->returned->price_penalty;
        }
    }

    public function store($returnedId)
    {
        $this->validate();

        $returned = Returned::findOrFail($returnedId);
        $transaction = $returned->transaction;
        $this->totalPayment = $transaction->price_final + $returned->price_penalty;

        // Cari payment berdasarkan returned_id
        $payment = ModelsPayment::where('returned_id', $returnedId)->first();

        // Jika payment belum ada, buat baru
        if (!$payment) {
            $payment = new ModelsPayment();
            $payment->returned_id = $returnedId;
        }

        $status = $this->paymentAmount >= $this->totalPayment ? 'Paid' : 'Unpaid';

        $payment->total_payment = $this->totalPayment;
        $payment->payment_amount = $this->paymentAmount;
        $payment->status = $status;

        $payment->save();

        $this->reset('paymentAmount');

        return $payment;
    }

    public function setPayment($returnedId)
    {
        $payment = ModelsPayment::where('returned_id', $returnedId)->first();
        if ($payment) {
            $this->payment = $payment;
            $this->paymentAmount = $payment->payment_amount;
            $this->totalPayment = $payment->total_payment;
        } else {
            $returned = Returned::findOrFail($returnedId);
            $transaction = $returned->transaction;
            $this->totalPayment = $transaction->price_final + $returned->price_penalty;
            $this->paymentAmount = 0;
        }
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