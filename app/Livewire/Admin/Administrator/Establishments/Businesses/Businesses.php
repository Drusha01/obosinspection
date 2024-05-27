<?php

namespace App\Livewire\Admin\Administrator\Establishments\Businesses;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class Businesses extends Component
{
    public $title = "Businesses";
    public function render()
    {

        return view('livewire.admin.administrator.establishments.businesses.businesses')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
