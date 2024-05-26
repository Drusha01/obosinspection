<?php

namespace App\Livewire\Admin\InspectorTeamLeader\Dashboard;

use Livewire\Component;

class Dashboard extends Component
{
    public $title = "Dashboard";
    public function render()
    {
        return view('livewire.admin.inspector-team-leader.dashboard.dashboard')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
