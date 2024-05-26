<?php

namespace App\Livewire\Admin\Administrator\Dashboard;

use Livewire\Component;

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
