<?php

namespace App\Livewire\Admin\Administrator\Establishments\BusinessCategory;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BusinessCategory extends Component
{
    public $title = "Business Category";
    public function render()
    {
        return view('livewire.admin.administrator.establishments.business-category.business-category')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
