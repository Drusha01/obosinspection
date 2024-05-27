<?php

namespace App\Livewire\Admin\InspectorTeamLeader\Violations;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Violations extends Component
{
    public $title = "Violations";
    public function render()
    {
        return view('livewire.admin.inspector-team-leader.violations.violations')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
