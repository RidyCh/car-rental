<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaction;
use Livewire\WithPagination;

class MyRentCar extends Component
{
    use WithPagination;

    public function render()
    {
        $rentedCars = Transaction::where('user_id', auth()->id())
            ->whereIn('status', ['Booked', 'On Rent'])
            ->with('car')
            ->latest()
            ->paginate(10);

        return view('livewire.my-rent-car', [
            'rentedCars' => $rentedCars
        ])
            ->title(auth()->user()->name . '\'s Rent Car');
    }
}