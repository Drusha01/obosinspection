<?php

namespace App\Livewire\Admin\Inspector\ActivityLogs;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogs extends Component
{
    public $title = "Activity logs";
    public function render()
    {
        return view('livewire.admin.inspector.activity-logs.activity-logs')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
