<?php

namespace App\Livewire\Admin\Inspector\Inspections\CompletedSchedules;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompletedSchedules extends Component
{
    public $title = "Completed inspections";
    public function render()
    {
        return view('livewire.admin.inspector.inspections.completed-schedules.completed-schedules')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
