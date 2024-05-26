<?php

namespace App\Livewire\Admin\Inspector\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public $title = "Dashboard";
    public function render()
    {
        return view('livewire.admin.inspector.dashboard.dashboard')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
