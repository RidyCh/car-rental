<?php

namespace App\Livewire\Forms;

use App\Models\User as ModelsUsers;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UsersForm extends Form
{
    public ?ModelsUsers $user;

    #[Validate('nullable|string|max:255|unique:users,nik')]
    public $nik = '';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:255|unique:users,username')]
    public $username = '';

    #[Validate('required|string|email|max:255|unique:users,email')]
    public $email = '';

    #[Validate('required|string|min:8')]
    public $password = '';

    #[Validate('nullable|in:Male,Female')]
    public $gender;

    #[Validate('nullable|string|max:255')]
    public $phone_number = '';

    #[Validate('nullable|string')]
    public $address = '';

    #[Validate('required|in:Administrator,Petugas,Member')]
    public $role = 'Member';

    public function store()
    {
        $validated = $this->validate();

        $validated['password'] = bcrypt($validated['password']);
        $users = ModelsUsers::create($validated);

        $this->reset();
        return $users;
    }

    public function setuser(ModelsUsers $user)
    {
        $this->user = $user;
        $this->nik = $user->nik;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->gender = $user->gender;
        $this->phone_number = $user->phone_number;
        $this->address = $user->address;
        $this->role = $user->role;
    }

    // public function updateProfile()
    // {
    //     $this->validate([
    //         'name' => 'required|string|max:255',
    //         'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
    //         'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
    //         'phone_number' => 'string|max:20',
    //         'address' => 'string',
    //         'gender' => 'in:Male,Female',
    //         'nik' => 'string|size:16|unique:users,nik,' . auth()->id(),
    //     ]);

    //     auth()->user()->update([
    //         'name' => $this->name,
    //         'username' => $this->username,
    //         'email' => $this->email,
    //         'phone_number' => $this->phoneNumber,
    //         'address' => $this->address,
    //         'gender' => $this->gender,
    //         'nik' => $this->nik,
    //     ]);
    //     $this->reset();
    // }

    public function update()
    {
        $validated = $this->validate([
            'nik' => 'nullable|string|max:255|unique:users,nik,' . $this->user->id,
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user->id,
            'gender' => 'nullable|in:Male,Female',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'role' => 'required|in:Administrator,Petugas,Member',
        ]);

        $user = ModelsUsers::findOrFail($this->user->id);

        $user->update($validated);
        $this->reset();
    }

    public function delete($id)
    {
        $user = ModelsUsers::findOrFail($id);
        $user->delete();
    }
}