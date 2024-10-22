<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\PaymentForm;
use App\Livewire\Forms\ReturnForm;
use App\Livewire\Forms\TransactionForm;
use App\Models\Transaction as ModelsTransaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Transaction extends Component
{
    public TransactionForm $form;
    public ReturnForm $returnForm;
    public PaymentForm $paymentForm;

    use WithPagination, WithoutUrlPagination, Toast;

    public $modalTake = null;
    public $modalCancel = null;
    public bool $modalDetail = false;
    public bool $modalReturn = false;
    public bool $modalPay = false;
    public $search = '';

    public array $sortBy = ['column' => 'rental_date', 'direction' => 'desc'];

    public $statusOptions = [
        ['name' => 'Booked', 'value' => 'Booked'],
        ['name' => 'On Rent', 'value' => 'On Rent'],
        ['name' => 'Returned', 'value' => 'Returned'],
        ['name' => 'Cancelled', 'value' => 'Cancelled'],
    ];

    public function showDetail($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);

        $this->form->setTransaction($transaction);
        $this->modalDetail = true;
    }

    public function take()
    {
        $this->modalDetail = false;
        if ($this->modalTake) {
            $this->form->take($this->modalTake);
            $this->dispatch('refreshTransactions');
            $this->success('Mobil sudah diambil');
            $this->modalTake = null;
        }
    }

    public function cancel()
    {
        $this->modalDetail = false;
        if ($this->modalCancel) {
            $this->form->cancel($this->modalCancel);
            $this->dispatch('refreshTransactions');
            $this->success('Booking berhasil dibatalkan');
            $this->modalCancel = null;
        }
    }

    public function return ($id)
    {
        $transaction = ModelsTransaction::findOrFail($id);
        $this->form->setTransaction($transaction);
        $this->modalReturn = true;
        $this->modalDetail = false;
    }

    public function saveReturn()
    {
        $this->returnForm->store($this->form->transactionId);

        $this->modalReturn = false;
        $this->modalDetail = false;
        $this->dispatch('refreshTransactions');
        $this->success('Berhasil mengembalikan mobil, silahkan lanjutkan pembayaran!');
    }

    public function savePay($id)
    {
        $this->paymentForm->store($this->form->transactionId);
        $this->paymentForm->setTransaction($id);
        $this->modalPay = true;
        $this->modalDetail = false;
        $this->dispatch('refreshTransactions');
        $this->success('Pembayaran berhasil dilakukan!');
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'nik', 'label' => 'Nama Member'],
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
            ->with('user', 'car')
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
            'transactions' => $this->transactions(),
            'headers' => $this->headers(),
        ]);
    }
}
