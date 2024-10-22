<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\ReturnForm;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\WithoutUrlPagination;
use App\Models\Returned as ModelsReturned;
use App\Models\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;

class Returned extends Component
{
    use WithPagination, WithoutUrlPagination, Toast;

    public ReturnForm $form;

    public $search = '';

    public bool $modalForm = false;

    public bool $editMode = false;

    public array $sortBy = ['column' => 'return_date', 'direction' => 'desc'];


    // public function edit($id)
    // {
    //     $returned = ModelsReturned::findOrFail($id);

    //     $this->form->setReturn($returned);
    //     $this->modalForm = true;
    //     $this->editMode = true;
    // }

    // public function save()
    // {
    //     if ($this->editMode) {
    //         $this->form->update();
    //         $this->success('Data updated successfully');
    //     }

    //     $this->modalForm = false;
    //     $this->editMode = false;
    // }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'transaction_id', 'label' => 'Transaksi'],
            ['key' => 'return_date', 'label' => 'Tanggal Kembali'],
            ['key' => 'condition_car', 'label' => 'Kondisi Mobil'],
            ['key' => 'price_penalty', 'label' => 'Denda'],
        ];
    }

    public function returneds(): LengthAwarePaginator
    {
        return ModelsReturned::query()
            ->when($this->sortBy['column'], function ($query) {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                $query->where('transaction_id', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    #[Layout('layouts.admin')]
    #[Title('Returned')]
    public function render()
    {
        return view('livewire.admin.returned', [
            'returneds' => $this->returneds(),
            'transactions' => Transaction::all(),
            'headers' => $this->headers(),
        ]);
    }
}
