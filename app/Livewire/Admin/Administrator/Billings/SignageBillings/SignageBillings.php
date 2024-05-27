<?php

namespace App\Livewire\Admin\Administrator\Billings\SignageBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SignageBillings extends Component
{
    public $title = "Signage billings";
    public function render()
    {
        return view('livewire.admin.administrator.billings.signage-billings.signage-billings')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
