<?php

namespace App\Livewire\Admin\InspectorTeamLeader\Dashboard;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $title = "Dashboard";
    public $dashboard = [
        'total_inspected_business'=> 0,
        'total_inspected_business_without_violation'=> 0,
        'total_inspected_business_with_violation'=> 0,
        'total_issued_certificate'=> 0,
        'montly_inspected_business' => [],
        'certificate_application_types' => [],

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
        $total_issued_certificate = DB::table('annual_certificate_inspections')
            ->select(
                DB::raw('count(*) as total_issued_certificate')
            )
            ->first();
        
        $montly_inspected_business = DB::table('inspections as i')
            ->select(
                DB::raw('YEAR(i.date_updated) as year'),
                DB::raw('MONTHNAME(i.date_updated) as month_name'),
                DB::raw('count(*) as montly_inspected_business')
            )
            ->join('inspection_status as st','i.status_id','st.id')
            ->groupby(DB::raw('YEAR(i.date_updated), MONTHNAME(i.date_updated)'))
            ->orderBy(DB::raw('YEAR(i.date_updated), MONTHNAME(i.date_updated)'),'desc')
            ->where('st.name','=','Completed')
            ->get()
            ->toArray();
        
        $certificate_application_types = DB::table('annual_certificate_inspections as aci')
            ->select(
                'at.id',
                'at.name as application_type_name',
                DB::raw('count(*) as certificate_application_types')
            )
            ->join('application_types as at','aci.application_type_id','at.id')
            ->groupBy('at.id')
            ->get()
            ->toArray();
        if($total_inspected_business){
            $this->dashboard['total_inspected_business'] = $total_inspected_business->total_inspected_business;
        }
        if($total_inspected_business_with_violation){
            $this->dashboard['total_inspected_business_with_violation'] = $total_inspected_business_with_violation->total_inspected_business_with_violation;
        }
        if($total_issued_certificate){
            $this->dashboard['total_issued_certificate'] = $total_issued_certificate->total_issued_certificate;
        }
        if($montly_inspected_business){
            $this->dashboard['montly_inspected_business'] = $montly_inspected_business;
        }
        if($certificate_application_types){
            $this->dashboard['certificate_application_types'] = $certificate_application_types;
        }
        $this->dashboard['total_inspected_business_without_violation'] = intval($this->dashboard['total_inspected_business'] - $this->dashboard['total_inspected_business_with_violation']);

        return view('livewire.admin.inspector-team-leader.dashboard.dashboard')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
