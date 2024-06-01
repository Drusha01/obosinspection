<?php

namespace App\Livewire\Admin\Administrator\Dashboard;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $title = "Dashboard";
    public $dashboard = [
        'total_inspected_business'=> NULL,
        'total_inspected_business_without_violation'=> NULL,
        'total_inspected_business_with_violation'=> NULL,
        'total_issued_certificate'=> NULL,
        'montly_inspected_business' => [],
        'certificate_application_type' => NULL,

    ];
    public function render()
    {
        // $total_inspected_business_without_violation = DB::table('inspections')
        return view('livewire.admin.administrator.dashboard.dashboard')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
