<?php

namespace App\Livewire\Admin\Inspector\Equipments\Category;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Category extends Component
{
    public $title = "Category";
    public function render()
    {
        return view('livewire.admin.inspector.equipments.category.category')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
