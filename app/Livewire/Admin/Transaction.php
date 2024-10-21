<?php

namespace App\Livewire\Admin;

use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Cars as ModelsCars;
use Livewire\WithoutUrlPagination;
use App\Models\User as ModelsUsers;
use App\Livewire\Forms\TransactionForm;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Transaction as ModelsTransaction;

class Transaction extends Component
{
    public TransactionForm $form;

    use WithPagination, WithoutUrlPagination, Toast;

    public bool $modalForm = false;

    public bool $editMode = false;

    public $search = '';

    public array $sortBy = ['column' => 'rental_date', 'direction' => 'desc'];

    public $modalDelete = null;

    public $carPrice;

    public $users;

    public $cars;

    public function mount()
    {
        $this->users = ModelsUsers::all();
        $this->cars = ModelsCars::all();
    }

    public function openModal()
    {
        $this->form->reset();
        $this->modalForm = true;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);

        $this->form->setTransaction($transaction);
        $this->modalForm = true;
        $this->editMode = true;
    }

    public function delete()
    {
        if ($this->modalDelete) {
            $this->form->delete($this->modalDelete);
            $this->success('Data berhasil dihapus');
            $this->modalDelete = null;
        }
    }

    public function save()
    {
        if ($this->editMode) {
            $this->form->update();
            $this->success('Data berhasil diperbarui');

        } else {
            $this->form->store();
            $this->success('Data berhasil dibuat');
        }

        $this->modalForm = false;
        $this->editMode = false;
    }

    public function updateCarPrice()
    {
        if ($this->form->carId) {
            $car = ModelsCars::find($this->form->carId);
            if ($car) {
                $this->carPrice = $car->price;
                $this->calculateTotal();
            }
        }
    }

    public function calculateTotal()
    {
        $carPrice = $this->form->carPrice ?? 0;
        $amountCar = $this->form->amountCar ?? 1;
        $duration = $this->form->duration ?? 1;
        $driverCost = $this->form->driver ? 100000 : 0;

        $total = ($carPrice * $amountCar * $duration) + $driverCost;
        $this->form->priceTotal = $total;

        $this->calculateFinal();
    }

    public function calculateFinal()
    {
        $total = $this->form->priceTotal ?? 0;
        $dp = $this->form->dp ?? 0;
        $this->form->priceFinal = $total - $dp;
    }

    public function updatedFormAmountCar()
    {
        $this->calculateTotal();
    }

    public function updatedFormDuration()
    {
        $this->calculateTotal();
    }

    public function updatedFormDriver()
    {
        $this->calculateTotal();
    }

    public function updatedFormDp()
    {
        $this->calculateFinal();
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'nik', 'label' => 'NIK'],
            ['key' => 'car_id', 'label' => 'Mobil'],
            ['key' => 'amount_car', 'label' => 'Jumlah Mobil'],
            ['key' => 'rental_date', 'label' => 'Tanggal Sewa'],
            ['key' => 'pick_up_at', 'label' => 'Waktu Pengambilan'],
            ['key' => 'duration', 'label' => 'Durasi'],
            ['key' => 'driver', 'label' => 'Supir'],
            ['key' => 'price_total', 'label' => 'Total Harga'],
            ['key' => 'dp', 'label' => 'DP'],
            ['key' => 'price_final', 'label' => 'Harga Akhir'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }

    public function transactions(): LengthAwarePaginator
    {
        return ModelsTransaction::query()
            ->when($this->sortBy['column'], function ($query) {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                $query->where('nik', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    #[Layout('layouts.admin')]
    #[Title('Transaction')]
    public function render()
    {
        return view('livewire.admin.transaction', [
            'users' => ModelsUsers::where('role', 'Member')
                ->whereNotNull('nik')
                ->get(),
            'cars' => ModelsCars::where('status', 'Available')->get(),
            'transactions' => $this->transactions(),
            'headers' => $this->headers(),
        ]);
    }
}