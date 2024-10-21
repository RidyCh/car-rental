<?php

namespace App\Livewire\Auth;

use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class Login extends Component
{
    use Toast;

    public $title = 'Welcome to the future !';
    public ?string $email;
    public ?string $password;
    public $remember;

    public function authenticate()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            request()->session()->regenerate();
            $this->success('Logged in successfully', position: 'toast-top');
            if (auth()->user()->role == "Administrator" || auth()->user()->role == "Petugas") {
                return Redirect::route('admin.cars');
            }
            return Redirect::route('features');
        } else {
            $this->error("Cannot verify the credentials !", position: 'toast-top');
        }
    }

    #[Layout('layouts.auth')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}