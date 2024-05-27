<?php

namespace App\Livewire\Admin\Inspector\Inspections\InspectionSchedules;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectionSchedules extends Component
{
    public $title = "Inspections schedules";
    public function render()
    {
        return view('livewire.admin.inspector.inspections.inspection-schedules.inspection-schedules')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
