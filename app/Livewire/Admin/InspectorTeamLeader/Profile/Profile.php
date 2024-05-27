<?php

namespace App\Livewire\Admin\InspectorTeamLeader\Profile;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Profile extends Component
{
    public $title = "Profile";
    public function render()
    {
        return view('livewire.admin.inspector-team-leader.profile.profile')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
