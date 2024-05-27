<?php

namespace App\Livewire\Admin\Administrator\ActivityLogs;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActivityLogs extends Component
{
    public $title = "Activity logs";
    public function render()
    {
        return view('livewire.admin.administrator.activity-logs.activity-logs')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
