<?php

namespace App\Livewire\Admin\Inspector\Certifications\Generate;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Certification extends Component
{
    public $title = "Generate Certification";
    public $generate ;
    public function mount($id){
        $annual_certificate_inspections = DB::table('annual_certificate_inspections as aci')
            ->select(
                'b.img_url',
                'b.name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'oc.character_of_occupancy_group',
                'b.contact_number',
                'b.street_address',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'at.name as application_type_name',
                'aci.bin',
                'aci.occupancy_no',
                'aci.date_compiled',
                'aci.issued_on',
                'aci.date_created',
                'aci.date_updated'
            )
            ->join('businesses as b','b.id','aci.business_id')
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->join('application_types as at','at.id','aci.application_type_id')
            ->where('aci.id','=',$id)
            ->first();

        $annual_certificate_inspection_inspectors = DB::table('annual_certificate_inspection_inspectors as acii')
            ->select(
                "acii.id",
                "acii.annual_certificate_inspection_id",
                "acii.person_id",
                "acii.category_id",
                'acc.name as category_name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.contact_number',
                'p.email',
                'p.img_url',
            )
            ->join('persons as p','p.id','acii.person_id')
            ->join('annual_certificate_categories as acc','acc.id','acii.category_id')
            ->where('acii.annual_certificate_inspection_id','=',$id)
            ->get()
            ->toArray();

        $unique_annual_certificate_inspection_inspectors = DB::table('annual_certificate_inspection_inspectors as acii')
            ->select(
                DB::raw('DISTINCT(p.id)'),
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.contact_number',
                'p.email',
                'p.img_url',
            )
            ->join('persons as p','p.id','acii.person_id')
            ->where('acii.annual_certificate_inspection_id','=',$id)
            ->get()
            ->toArray();
        $annual_certificate_categories = DB::table('annual_certificate_categories as acc')
            ->get()
            ->toArray();
        if($annual_certificate_inspections){

        }else{
            return redirect()->route('administrator-certifications');
        }

        $this->generate = [
            'annual_certificate_inspections'=>$annual_certificate_inspections,
            'annual_certificate_inspection_inspectors'=>$annual_certificate_inspection_inspectors,
            'unique_annual_certificate_inspection_inspectors'=>$unique_annual_certificate_inspection_inspectors
        ];
        DB::table('activity_logs')
        ->insert([
            'created_by' => $this->activity_logs['created_by'],
            'inspector_team_id' => $this->activity_logs['inspector_team_id'],
            'log_details' => 'has generated a cetificate for '.$annual_certificate_inspections->name.' with business type of '.$annual_certificate_inspections->business_type_name,
        ]);
    }
    public $activity_logs = [
        'created_by' => NULL,
        'inspector_team_id' => NULL,
        'log_details' => NULL,
    ];
    public function boot(Request $request){
        $session = $request->session()->all();
        $this->activity_logs['created_by'] = $session['id'];
        $user_details = 
            DB::table('users as u')
            ->select(
                'im.member_id',
                'im.inspector_team_id',
                'it.team_leader_id',
                'it.id',
                )
            ->join('persons as p','p.id','u.id')
            ->leftjoin('inspector_members as im','im.member_id','p.id')
            ->leftjoin('inspector_teams as it','it.team_leader_id','p.id')
            ->where('u.id','=',$session['id'])
            ->first();
        if($user_details->member_id){
            $this->activity_logs['inspector_team_id'] = $user_details->member_id;
        }elseif($user_details->team_leader_id){
            $this->activity_logs['inspector_team_id'] = $user_details->team_leader_id;
        }else{
            $this->activity_logs['inspector_team_id'] = 0;
        }
    }
    public function render()
    {
        return view('livewire.admin.inspector.certifications.generate.certification',[
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
