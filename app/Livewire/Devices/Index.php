<?php

namespace App\Livewire\Devices;

use Livewire\Component;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class Index extends Component
{
    public function render(): Factory|View
    {
        return view('livewire.devices.index');
    }
}
