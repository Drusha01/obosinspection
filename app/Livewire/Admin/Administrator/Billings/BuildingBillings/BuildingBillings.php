<?php

namespace App\Livewire\Admin\Administrator\Billings\BuildingBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingBillings extends Component
{
    public $title = "Building billings";
    public function render()
    {
        return view('livewire.admin.administrator.billings.building-billings.building-billings')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
