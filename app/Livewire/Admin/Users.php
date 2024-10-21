<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\UsersForm;
use App\Models\User as ModelsUsers;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Users extends Component
{
    public UsersForm $form;

    use WithFileUploads, WithPagination, WithoutUrlPagination, Toast;

    public bool $modalForm = false;

    public bool $editMode = false;

    public $search = '';

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    public $modalDelete = null;

    public $genderOptions = [
        ['name' => 'Laki-laki', 'value' => 'Male'],
        ['name' => 'Perempuan', 'value' => 'Female'],
    ];

    public $roleOptions = [
        ['name' => 'Member', 'value' => 'Member'],
        ['name' => 'Petugas', 'value' => 'Petugas'],
    ];

    public function openModal()
    {
        $this->form->reset();
        $this->form->role = 'Member';
        $this->modalForm = true;
        $this->editMode = false;
    }

    public function edit($id)
    {
        $user = Modelsusers::findorFail($id);

        $this->form->setUser($user);
        $this->modalForm = true;
        $this->editMode = true;
    }

    public function delete()
    {
        if ($this->modalDelete) {
            $this->form->delete($this->modalDelete);
            $this->success('Data deleted successfully');
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

    // Table headers
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'nik', 'label' => 'NIK'],
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'username', 'label' => 'Username'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'gender', 'label' => 'Jenis Kelamin'],
            ['key' => 'phone_number', 'label' => 'Nomor Telepon'],
            ['key' => 'address', 'label' => 'Alamat'],
            ['key' => 'role', 'label' => 'Role'],
        ];
    }

    public function users(): LengthAwarePaginator
    {
        return ModelsUsers::query()
            ->whereIn('role', ['Member', 'Petugas'])
            ->when($this->sortBy['column'], function ($query) {
                $query->orderBy($this->sortBy['column'], $this->sortBy['direction']);
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);
    }

    #[Layout('layouts.admin')]
    #[Title('Users')]
    public function render()
    {
        return view('livewire.admin.users', [
            'users' => $this->users(),
            'headers' => $this->headers(),
        ]);

    }
}