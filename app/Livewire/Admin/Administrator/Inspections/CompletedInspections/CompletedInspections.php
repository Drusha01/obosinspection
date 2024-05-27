<?php

namespace App\Livewire\Admin\Administrator\Inspections\CompletedInspections;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompletedInspections extends Component
{
    public $title = "Completed inspections";
    public function render()
    {
        return view('livewire.admin.administrator.inspections.completed-inspections.completed-inspections')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
