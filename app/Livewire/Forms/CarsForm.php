<?php

namespace App\Livewire\Forms;

use App\Models\Cars as ModelsCars;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class CarsForm extends Form
{
    use WithFileUploads;

    public ?ModelsCars $car;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:255')]
    public $brand = '';

    #[Validate('required|string|max:255')]
    public $type = '';

    #[Validate('nullable|image|max:1024')]
    public $image;

    #[Validate('required|integer|min:1900|max:2100')]
    public $year;

    #[Validate('required|numeric|min:0')]
    public $price;

    #[Validate('required|integer|min:1')]
    public $seatsTotal = 1;

    #[Validate('required|integer|min:0')]
    public $stock = 0;

    #[Validate('required|in:Available,Unavailable,Maintenance')]
    public $status = 'Available';

    public function store()
    {
        $validated = $this->validate();

        if ($this->image) {
            $imageName = Str::random(20) . '.' . $this->image->getClientOriginalExtension();
            $imagePath = $this->image->storeAs('car-images', $imageName, 'public');
            $validated['image'] = $imageName;
            $validated['seats_total'] = 1;
        }

        $cars = ModelsCars::create($validated);

        $this->reset();
        return $cars;
    }

    public function setCar(ModelsCars $car)
    {
        $this->car = $car;
        $this->name = $car->name;
        $this->brand = $car->brand;
        $this->type = $car->type;
        $this->year = $car->year;
        $this->price = $car->price;
        $this->seatsTotal = $car->seats_total;
        $this->stock = $car->stock;
        $this->status = $car->status;
    }

    public function update()
    {
        $validated = $this->validate();

        $car = ModelsCars::findOrFail($this->car->id);

        if ($this->image) {
            if ($car->image) {
                Storage::disk('public')->delete($car->image);
            }

            $imageName = Str::random(20) . '.' . $this->image->getClientOriginalExtension();
            $imagePath = $this->image->storeAs('car-images', $imageName, 'public');

            $validated['image'] = $imageName;
        } else {
            unset($validated['image']);
        }

        $car->update($validated);
        $this->reset();
    }

    public function delete($id)
    {
        $car = ModelsCars::findOrFail($id);

        if ($car->image) {
            Storage::disk('public')->delete($car->image);
        }

        $car->delete();
    }
}
