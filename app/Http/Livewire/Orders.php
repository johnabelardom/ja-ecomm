<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Orders extends Component
{
    public $orders = [];

    public function __construct()
    {
        $this->orders = auth()->user()->getOrders();
       
    }

    public function render()
    {
        return view('livewire.orders');
    }

    

}
