<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mary\Traits\Toast;

class Register extends Component
{
    use Toast;

    public $title = 'Register here to continue !';
    public ?string $name;
    public ?string $username;
    public ?string $email;
    public ?string $password;

    public function register()
    {
        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $user = User::create(['name' => $this->name, 'username' => $this->username, 'email' => $this->email, 'password' => Hash::make($this->password)]);

        $this->success('Registration successfull 😊 !');

        // event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');
    }

    #[Layout('layouts.auth')]
    #[Title('Register')]
    public function render()
    {
        return view('livewire.auth.register');
    }
}