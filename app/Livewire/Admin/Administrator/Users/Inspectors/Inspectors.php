<?php

namespace App\Livewire\Admin\Administrator\Users\Inspectors;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Inspectors extends Component
{
    public $title = "Inspectors";
    public function render()
    {
        return view('livewire.admin.administrator.users.inspectors.inspectors')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
