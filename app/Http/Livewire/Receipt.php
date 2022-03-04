<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Receipt extends Component
{
    public $order;

    public function render()
    {
        return view('livewire.receipt');
    }
}
