<?php

namespace App\Livewire\Authentication;

use Livewire\Component;

class Logout extends Component
{
    public $title = "Logout";
    public function render()
    {
        return view('livewire.authentication.logout')
        ->layout('components.layouts.guest',[
            'title'=>$this->title]);
    }
}
