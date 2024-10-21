<?php

namespace App\Livewire\Forms;

use App\Models\Returned;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ReturnForm extends Form
{
    public ?Returned $return;

    #[Validate('required')]
    public $transactionId;

    #[Validate('required|date')]
    public $returnDate;

    #[Validate('nullable|string')]
    public $conditionCar;
    
    #[Validate('required|numeric|min:0')]
    public $pricePenalty;

    public function store()
    {
        $validatedData = $this->validate();
        $return = Returned::create($validatedData);
        $return->transaction()->update(['status' => 'Returned']);
        return $return;
    }

    public function setReturn(Returned $return)
    {
        $this->$return = $return;
        $this->transactionId = $return->transaction_id;
        $this->returnDate = $return->return_date;
        $this->conditionCar = $return->condition_car;
        $this->pricePenalty = $return->price_penalty;
    }

    public function update()
    {
        $validatedData = $this->validate();
        $return = Returned::findOrFail($this->transactionId);
        $return->update($validatedData);
        $this->reset();
    }

    public function delete($id)
    {
        $return = Returned::findOrFail($id);
        $return->delete();
    }
}