<?php

namespace App\Livewire;

use App\Livewire\Forms\TransactionForm;
use App\Models\Cars;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class DetailCar extends Component
{
    use Toast;

    public TransactionForm $form;

    public $slug;

    public bool $modalBooking = false;

    public $car;

    public $durationOptions = [
        ['name' => '1 hari', 'value' => '1'],
        ['name' => '2 hari', 'value' => '2'],
        ['name' => '3 hari', 'value' => '3'],
        ['name' => '4 hari', 'value' => '4'],
        ['name' => '5 hari', 'value' => '5'],
        ['name' => '6 hari', 'value' => '6'],
        ['name' => '7 hari', 'value' => '7'],
        ['name' => '8 hari', 'value' => '8'],
        ['name' => '9 hari', 'value' => '9'],
        ['name' => '10 hari', 'value' => '10'],
        ['name' => '11 hari', 'value' => '11'],
        ['name' => '12 hari', 'value' => '12'],
        ['name' => '13 hari', 'value' => '13'],
        ['name' => '14 hari', 'value' => '14'],
    ];

    public $driverCost = 100000; // Biaya supir per hari

    public function mount($slug)
    {
        $this->car = Cars::where('slug', $slug)->first();
        $this->slug = $slug;
        $this->form->rentalDate = now()->toDateString();
        $this->form->amountCar = 1;
        $this->form->duration = 1;
        $this->form->dp = 0;
        $this->form->driver = false;
        $this->updatePrices();
    }

    public function saveBooking()
    {
        try {

            if (auth()->user()->check) {
                $this->modalBooking = false;
                $this->error('Anda harus login terlebih dahulu', redirectTo: route('login', ['navigate' => true]));
                return;
            }

            if (auth()->user()->nik == null) {
                $this->modalBooking = false;
                $this->error('Anda harus mengisi data diri terlebih dahulu');
                return;
            }

            $this->form->store($this->car->id);
            $this->modalBooking = false;
            $this->success('Anda berhasil membooking mobil ini!');
        } catch (\Exception $e) {
            $this->modalBooking = false;
            // Simpan pesan kesalahan dalam variabel properti
            $this->errorMessage = 'Terjadi kesalahan: ' . $e->getMessage();
            // Tambahkan log untuk debugging
            \Log::error('Error saat booking: ' . $e->getMessage());
        }
    }

    public function updatedFormAmountCar()
    {
        $this->form->amountCar = max(1, min((int) $this->form->amountCar, 50));
        $this->updatePrices();
    }

    public function updatedFormDuration()
    {
        $this->updatePrices();
    }

    public function updatedFormDp()
    {
        $this->form->dp = min($this->form->dp, $this->form->priceTotal);
        $this->updatePrices();
    }

    public function updatedFormDriver()
    {
        $this->updatePrices();
    }

    public function updatePrices()
    {
        $basePrice = $this->car->price * $this->form->duration * $this->form->amountCar;
        $driverCost = $this->form->driver ? $this->driverCost : 0;
        $this->form->priceTotal = $basePrice + $driverCost;
        $this->form->priceFinal = $this->form->dp ? max($this->form->priceTotal - $this->form->dp, 0) : $this->form->priceTotal;
    }

    #[Computed]
    public function formattedPriceTotal()
    {
        return number_format($this->form->priceTotal, 2, ',', '.');
    }

    #[Computed]
    public function formattedPriceFinal()
    {
        return number_format($this->form->priceFinal, 2, ',', '.');
    }

    public function render()
    {
        return view('livewire.detail-car')
            ->title($this->car->name);
    }

    public function decrementAmount()
    {
        $this->form->amountCar = max(1, $this->form->amountCar - 1);
        $this->updatePrices();
    }

    public function incrementAmount()
    {
        $this->form->amountCar = min(50, $this->form->amountCar + 1);
        $this->updatePrices();
    }
}