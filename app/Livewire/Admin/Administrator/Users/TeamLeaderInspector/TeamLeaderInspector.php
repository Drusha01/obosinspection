<?php

namespace App\Livewire\Admin\Administrator\Users\TeamLeaderInspector;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamLeaderInspector extends Component
{
    public $title = "Inspectors team leader";
    public function render()
    {
        return view('livewire.admin.administrator.users.team-leader-inspector.team-leader-inspector')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
