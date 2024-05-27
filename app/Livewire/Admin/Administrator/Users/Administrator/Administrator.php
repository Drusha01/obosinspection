<?php

namespace App\Livewire\Admin\Administrator\Users\Administrator;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Administrator extends Component
{
    public $title = "Administrators";
    public function render()
    {
        return view('livewire.admin.administrator.users.administrator.administrator')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
