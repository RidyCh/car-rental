<?php

namespace App\Livewire;

use App\Models\Cars;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TopCars extends Component
{
    public $topCarsCount = 6;
    public $topCars = [];

    public function mount()
    {
        $this->loadTopCars();
    }

    public function loadTopCars()
    {
        $this->topCars = Cars::query()
            ->groupBy('cars.id')
            ->orderByDesc('rental_count')
            ->limit($this->topCarsCount)
            ->get();
        // $this->topCars = Cars::select('cars.*', DB::raw('COUNT(rentals.id) as rental_count'))
        //     ->leftJoin('transactions', 'cars.id', '=', 'transactons.car_id')
        //     ->groupBy('cars.id')
        //     ->orderByDesc('rental_count')
        //     ->limit($this->topCarsCount)
        //     ->get();
    }

    public function render()
    {
        return view('livewire.top-cars', [
            'topCars' => $this->topCars
        ]);
    }
}