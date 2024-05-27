<?php

namespace App\Livewire\Admin\Administrator\Users\InspectorGroups;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectorGroups extends Component
{
    public $title = "Inspector groups";
    public function render()
    {
        return view('livewire.admin.administrator.users.inspector-groups.inspector-groups')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
