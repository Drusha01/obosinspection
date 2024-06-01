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
        $total_inspected_business = DB::table('inspections as i')
            ->select(
                DB::raw('count(*) as total_inspected_business')
            )
            ->join('inspection_status as st','i.status_id','st.id')
            ->where('st.name','=','Completed')
            ->first();
        $total_inspected_business_with_violation = DB::table('inspections as i')
            ->select(
                DB::raw('count(*) as total_inspected_business_with_violation')
            )
            ->join('inspection_status as st','i.status_id','st.id')
            ->join('inspection_violations as iv','iv.inspection_id','i.id')
            ->where('st.name','=','Completed')
            ->whereNotNull('iv.id')
            ->first();

        if($total_inspected_business){
            $this->dashboard['total_inspected_business'] = $total_inspected_business->total_inspected_business;
        }
        if($total_inspected_business_with_violation){
            $this->dashboard['total_inspected_business_with_violation'] = $total_inspected_business_with_violation->total_inspected_business_with_violation;
        }
        $this->dashboard['total_inspected_business_without_violation'] = intval($this->dashboard['total_inspected_business'] - $this->dashboard['total_inspected_business_with_violation']);
        // dd($this->dashboard);
        return view('livewire.admin.administrator.dashboard.dashboard')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
