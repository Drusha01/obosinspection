<?php

namespace App\Livewire\Admin\Administrator\Billings\EquipmentBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentBillings extends Component
{
    public $title = "Equipment billings";
    public function render()
    {
        return view('livewire.admin.administrator.billings.equipment-billings.equipment-billings')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
