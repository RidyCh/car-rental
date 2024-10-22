<?php

namespace App\Livewire;

use Livewire\Component;
use Mary\Traits\Toast;

class Profile extends Component
{
    use Toast;

    public $editMode = false;
    public $name, $username, $email, $phone_number, $address, $gender, $nik;

    public $genderOptions = [
        ['name' => 'Laki-laki', 'value' => 'Male'],
        ['name' => 'Perempuan', 'value' => 'Female'],
    ];

    public function mount()
    {
        $this->fillUserData();
    }

    public function fillUserData()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
        $this->address = $user->address;
        $this->gender = $user->gender;
        $this->nik = $user->nik;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'phone_number' => 'nullable|max:20',
            'address' => 'nullable',
            'gender' => 'nullable',
            'nik' => 'nullable|size:16|unique:users,nik,' . auth()->id(),
        ]);

        auth()->user()->update([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'gender' => $this->gender,
            'nik' => $this->nik,
        ]);

        $this->editMode = false;
        $this->success('Berhasil mengubah profil.');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
