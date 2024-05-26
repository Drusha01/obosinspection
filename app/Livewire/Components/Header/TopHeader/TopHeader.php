<?php

namespace App\Livewire\Components\Header\TopHeader;

use Livewire\Component;

class TopHeader extends Component
{
    public $user_details;
    public function render()
    {
        return view('livewire.components.header.top-header.top-header');
    }
}
