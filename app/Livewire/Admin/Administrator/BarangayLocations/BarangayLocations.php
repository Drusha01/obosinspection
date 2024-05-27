<?php

namespace App\Livewire\Admin\Administrator\BarangayLocations;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangayLocations extends Component
{
    public $title = "Barangay locations";
    public function render()
    {
        return view('livewire.admin.administrator.barangay-locations.barangay-locations')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
