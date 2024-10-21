<?php

namespace App\Livewire\Admin;

use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\CarsForm;
use App\Models\Cars as ModelsCars;
use Livewire\WithoutUrlPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class Cars extends Component
{
    public CarsForm $form;

    use WithFileUploads, WithPagination, WithoutUrlPagination, Toast;

    public bool $modalForm = false;

    public bool $editMode = false;

    public $search = '';

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public $modalDelete = null;

    public $statusOptions = [
        ['name' => 'Available', 'value' => 'Available'],
        ['name' => 'Unavailable', 'value' => 'Unavailable'],
        ['name' => 'Maintenance', 'value' => 'Maintenance'],
    ];

    public function openModal()
    {
        $this->form->reset();
        $this->form->status = 'Available';
        $this->modalForm = true;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $car = ModelsCars::findorFail($id);

        $this->form->setCar($car);
        $this->modalForm = true;
        $this->editMode = true;
    }

    public function delete()
    {
        if ($this->modalDelete) {
            $this->form->delete($this->modalDelete);
            $this->success('Berhasil menghapus data');
            $this->modalDelete = null;
        }
    }

    public function save()
    {
        if ($this->editMode) {
            $this->form->update();
            $this->success('Berhasil mengupdate data');

        } else {
            $this->form->store();
            $this->success('Berhasil menambah data');
        }

        $this->modalForm = false;
        $this->editMode = false;
    }

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'image', 'label' => 'Gambar', 'sortable' => false],
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'brand', 'label' => 'Merek'],
            ['key' => 'type', 'label' => 'Tipe'],
            ['key' => 'year', 'label' => 'Tahun'],
            ['key' => 'price', 'label' => 'Harga'],
            ['key' => 'seats_total', 'label' => 'Jumlah Kursi'],
            ['key' => 'stock', 'label' => 'Stok'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }

    public function cars(): LengthAwarePaginator
    {
        return ModelsCars::query()
            ->when($this->sortBy['column'], function ($query) {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    #[Layout('layouts.admin')]
    #[Title('Cars')]
    public function render()
    {
        return view('livewire.admin.cars', [
            'cars' => $this->cars(),
            'headers' => $this->headers(),
        ]);
    }
}
