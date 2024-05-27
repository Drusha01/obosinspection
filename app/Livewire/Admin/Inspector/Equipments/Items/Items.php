<?php

namespace App\Livewire\Admin\Inspector\Equipments\Items;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Items extends Component
{
    public $title = "Items";
    public function render()
    {
        return view('livewire.admin.inspector.equipments.items.items')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
