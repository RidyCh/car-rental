<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class ForgotPassword extends Component
{
    #[Layout('layouts.auth')]
    #[Title('Forgot Password')]
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
