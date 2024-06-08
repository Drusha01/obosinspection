<?php

namespace App\Livewire\Admin\Administrator\Establishments\BusinessOccuClass;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BusinessOccuClass extends Component
{
    public $title = "Business Occupancy Classificatiohn";
    public function render()
    {
        return view('livewire.admin.administrator.establishments.business-occu-class.business-occu-class')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
