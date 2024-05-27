<?php

namespace App\Livewire\Admin\Administrator\Inspections\InspectionSchedules;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectionSchedules extends Component
{
    public $title = "Inspection schedules";
    public function render()
    {
        return view('livewire.admin.administrator.inspections.inspection-schedules.inspection-schedules')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
