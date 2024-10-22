<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\PaymentForm;
use App\Models\Payment as ModelsPayment;
use App\Models\Returned;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Payment extends Component
{
    public PaymentForm $form;

    use WithPagination, WithoutUrlPagination, Toast;

    public $search = '';

    public bool $modalForm = false;

    public bool $editMode = false;

    public array $sortBy = ['column' => 'updated_at', 'direction' => 'desc'];

    public function edit($id)
    {
        $payment = ModelsPayment::findOrFail($id);

        $this->form->setPayment($payment);
        $this->modalForm = true;
        $this->editMode = true;
    }

    public function save()
    {
        $this->form->update();
        $this->success('Data updated successfully');
        $this->modalForm = false;
        $this->editMode = false;
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'returned_id', 'label' => 'ID Pengembalian'],
            ['key' => 'payment_amount', 'label' => 'Jumlah Pembayaran'],
            ['key' => 'total_payment', 'label' => 'Total Harga'],
            ['key' => 'updated_at', 'label' => 'Tanggal Pembayaran'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }

    public function payments(): LengthAwarePaginator
    {
        return ModelsPayment::query()
            ->when($this->sortBy['column'], function ($query) {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                $query->where('returned_id', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    #[Layout('layouts.admin')]
    #[Title('Payment')]
    public function render()
    {
        return view('livewire.admin.payment', [
            'payments' => $this->payments(),
            'returneds' => Returned::all(),
            'headers' => $this->headers(),
        ]);
    }
}
