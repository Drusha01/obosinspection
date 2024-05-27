<?php

namespace App\Livewire\Admin\Administrator\Dashboard;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $title = "Dashboard";
    public function render()
    {
        return view('livewire.admin.administrator.dashboard.dashboard')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
