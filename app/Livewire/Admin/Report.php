<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Report extends Component
{

    #[Title('Report')]
    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.admin.report');
    }
}
