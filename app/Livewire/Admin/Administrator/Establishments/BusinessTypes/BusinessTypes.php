<?php

namespace App\Livewire\Admin\Administrator\Establishments\BusinessTypes;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BusinessTypes extends Component
{
    public $title = "Business Types";
    public function render()
    {
        return view('livewire.admin.administrator.establishments.business-types.business-types')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
