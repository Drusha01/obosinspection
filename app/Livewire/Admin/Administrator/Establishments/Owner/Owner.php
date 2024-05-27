<?php

namespace App\Livewire\Admin\Administrator\Establishments\Owner;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Owner extends Component
{
    public $title = "Owners";
    public function render()
    {
        return view('livewire.admin.administrator.establishments.owner.owner')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
