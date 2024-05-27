<?php

namespace App\Livewire\Admin\Administrator\Billings\SanitaryBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SanitaryBillings extends Component
{
    public $title = "Sanitary billings";
    public function render()
    {
        return view('livewire.admin.administrator.billings.sanitary-billings.sanitary-billings')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
