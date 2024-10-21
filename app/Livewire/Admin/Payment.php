<?php

namespace App\Livewire\Admin;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\Returned;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\WithoutUrlPagination;
use App\Livewire\Forms\PaymentForm;
use App\Models\Payment as ModelsPayment;
use Illuminate\Pagination\LengthAwarePaginator;

class Payment extends Component
{
    public PaymentForm $form;

    use WithPagination, WithoutUrlPagination, Toast;

    public $search = '';

    public bool $modalForm = false;

    public bool $editMode = false;

    public $modalDelete = null;

    public array $sortBy = ['column' => 'created_at', 'direction' => 'desc'];

    public function openModal()
    {
        $this->form->reset();
        $this->modalForm = true;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $payment = ModelsPayment::findOrFail($id);

        $this->form->setPayment($payment);
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
            $this->success('Data updated successfully');

        } else {
            $this->form->store();
            $this->success('Data created successfully');
        }

        $this->modalForm = false;
        $this->editMode = false;
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'returned_id', 'label' => 'ID Pengembalian'],
            ['key' => 'payment_amount', 'label' => 'Jumlah Pembayaran'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => 'Tanggal Pembayaran'],
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