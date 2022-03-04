<?php

namespace App\View\Components;

use Illuminate\Support\Facades\View;
use Illuminate\View\Component;

class StoreLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        View::share('cartSession', session()->get('cart', []));

        return view('layouts.store');
    }
}
