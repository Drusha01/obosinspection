<?php

namespace App\Livewire;

use Livewire\Component;

class Test extends Component
{
    public $title = "Test";
    public function render()
    {
        return view('livewire.test') 
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
