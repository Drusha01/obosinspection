<?php

namespace App\Livewire\Admin\InspectorTeamLeader\Inspections\InspectionSchedules;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Database\Query\Builder;

class InspectionSchedules extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Inspection schedules";
    public $inspector_leaders;
    public $inspector_members;
    public $businesses;
    public $modal = [
        'search'=>NULL,
        'search_prev'=> NULL,
        'brgy_id'=> NULL,
        'prev_brgy_id'=> NULL,
        'business_category_id'=> NULL,
    ];

    public $business_categories = [];

    public $inspection = [
        'id'=>NULL,
        'inspector_leaders' =>[],
        'inspector_members' => [],
        'business_id' =>NULL,
        'schedule_date'=>NULL,
        'step'=> 1,
        'last_inspection'=> NULL,
    ];

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'id','active'=> true,'name'=>'ID'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
        ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
        ['column_name'=> 'schedule_date','active'=> true,'name'=>'Schedule'],
        ['column_name'=> 'status_name','active'=> true,'name'=>'Status'],
        ['column_name'=> 'id','active'=> true,'name'=>'Inspection Details'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $issue_inspection = [
        'id' => NULL,
        'status_id' => NULL,
        'business_id' => NULL,
        'schedule_date' => NULL,
        'signage_id' => NULL,
        'building_billing_id' => NULL,
        'application_type_id' => NULL,
        'remarks' => NULL,
        'date_signed' => NULL,
        'step'=> 1,
        'steps'=>[],
        'item_id'=> NULL,
        'sanitary_billing_id'=> NULL,

        'inspection_business_name' => NULL,
        'inspection_items' =>[],
        'inspection_inspector_members' =>[],
        'inspection_inspector_team_leaders' =>[],
        'inspection_violations' =>[],

        'application_types'  =>[],
        'items' =>[],
        'signage_billings' =>[],
        'building_billings'  =>[],
        'sanitary_billings'  =>[],
        'inspector_members'  => [],
        'inspector_team_leaders'  => [],
        'violations'  =>[],
    ];
    public function mount(Request $request){
        $session = $request->session()->all();

        $this->inspector_members = DB::table('persons as p')
            ->select(
                "p.id",
                "p.person_type_id",
                "p.brgy_id",
                "p.work_role_id",
                "p.first_name",
                "p.middle_name",
                "p.last_name",
                "p.suffix",
                "p.contact_number",
                "p.email",
                "p.img_url",
                'wr.name as work_role_name',
                'it.name as inspector_team',
            )
            ->join('inspector_members as im','im.member_id','p.id')
            ->leftjoin('inspector_teams as iit','iit.id','im.inspector_team_id')
            ->join('inspector_teams as it','im.inspector_team_id','it.id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('pt.name','Inspector')
            ->where('it.team_leader_id','=',$session['id'])
            ->get()
            ->toArray();
            $this->inspector_leaders = DB::table('persons as p')
            ->select(
                "p.id",
                "p.person_type_id",
                "p.brgy_id",
                "p.work_role_id",
                "p.first_name",
                "p.middle_name",
                "p.last_name",
                "p.suffix",
                "p.contact_number",
                "p.email",
                "p.img_url",
                'wr.name as work_role_name',
                'it.name as inspector_team',
            )
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->whereNotNull('it.team_leader_id')
            ->where('pt.name','Inspector')
            ->get()
            ->toArray();

            $session = $request->session()->all();
            $table_filter = DB::table('table_filters')
            ->where('user_id',$session['id'])
            ->where('path','=',$request->path())
            ->first();
            if($table_filter){
                $temp_filter = [];
                foreach (json_decode($table_filter->filter) as $key => $value) {
                    array_push($temp_filter,[
                        'column_name'=>$value->column_name,
                        'active'=>$value->active,
                        'name'=>$value->name,
                    ]);
                }
                $this->table_filter = [
                    'id'=>$table_filter->id,
                    'path'=>$table_filter->path,
                    'table_rows'=>$table_filter->table_rows,
                    'filter'=>$temp_filter,
                ];
            }else{
                DB::table('table_filters')
                ->insert([
                    'user_id' =>$session['id'],
                    'path' =>$request->path(),
                    'table_rows' =>10,
                    'filter'=> json_encode($this->filter)
                ]);
                $table_filter = DB::table('table_filters')
                ->where('user_id',$session['id'])
                ->where('path','=',$request->path())
                ->first();
                $temp_filter = [];
                foreach (json_decode($table_filter->filter) as $key => $value) {
                    array_push($temp_filter,[
                        'column_name'=>$value->column_name,
                        'active'=>$value->active,
                        'name'=>$value->name,
                    ]);
                }
                $this->table_filter = [
                    'id'=>$table_filter->id,
                    'path'=>$table_filter->path,
                    'table_rows'=>$table_filter->table_rows,
                    'filter'=>$temp_filter,
                ];
            }
        $city_mun = DB::table('citymun')
        ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
        ->first();
        // $this->brgy = DB::table('brgy')
        //     ->where('citymunCode','=',$city_mun->citymunCode)
        //     ->orderBy('brgyDesc','asc')
        //     ->get()
        //     ->toArray();

        $this->brgy = DB::table('team_target_barangays as ttb')
            ->select(
                'ttb.id',
                'b.brgyDesc',
                'ttb.brgy_id'
                )
            ->join('brgy as b','b.id','ttb.brgy_id')
            ->join('inspector_teams as it','ttb.inspector_team_id','it.id')
            ->where('it.team_leader_id','=',$session['id'])
            ->get()
            ->toArray();
            
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
    public $search = [
        'search'=> NULL,
        'search_prev'=> NULL,
        'type' => NULL,
        'type_prev' => NULL,
        'brgy_id'=>NULL,
    ];
    public $search_by = [
        ['name'=>'Name','column_name'=>'b.name'],
        ['name'=>'ID','column_name'=>'i.id'],
        // ['name'=>'Contact','column_name'=>'b.contact_number'],
    ];

    public $table_filter = [];

    public function save_filter(Request $request){
        $session = $request->session()->all();
        $table_filter = DB::table('table_filters')
        ->where('id',$this->table_filter['id'])
        ->first();
        if($table_filter){
            DB::table('table_filters')
            ->where('id',$this->table_filter['id'])
            ->update([
                'table_rows'=>$this->table_filter['table_rows'],
                'filter'=>json_encode($this->table_filter['filter']),
            ]);
            $table_filter = DB::table('table_filters')
                ->where('id',$this->table_filter['id'])
                ->first();
            $temp_filter = [];
            foreach (json_decode($table_filter->filter) as $key => $value) {
                array_push($temp_filter,[
                    'column_name'=>$value->column_name,
                    'active'=>$value->active,
                    'name'=>$value->name,
                ]);
            }
            $this->table_filter = [
                'id'=>$table_filter->id,
                'path'=>$table_filter->path,
                'table_rows'=>$table_filter->table_rows,
                'filter'=>$temp_filter,
            ];
        }
        $this->dispatch('swal:redirect',
            position         									: 'center',
            icon              									: 'success',
            title             									: 'Successfully updated!',
            showConfirmButton 									: 'true',
            timer             									: '1000',
            link              									: '#'
        );
    }
    public $brgy = [];
    public $from_request = false;
    public $business_from = 'from-email';

    public function render(Request $request)
    {
        $session = $request->session()->all();
        $person = DB::table('users as u')
            ->select('u.person_id')
            ->where('u.id','=',$session['id'])
            ->first();

       

        if($this->business_from == 'from-email'){
            if(intval($this->modal['brgy_id'])){
                if(intval($this->modal['business_category_id'])){
                    $this->businesses = DB::table('request_inspections as ri')
                        ->select(
                            'b.id',
                            'b.img_url',
                            'b.name',
                            'b.business_category_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'brg.brgyDesc as barangay',
                            'bt.name as business_type_name',
                            'oc.character_of_occupancy as occupancy_classification_name',
                            'b.contact_number',
                            'b.email',
                            'b.floor_area',
                            'b.signage_area',
                            'b.is_active',
                            'rs.name as status_name',
                            'ri.request_date',
                            'ri.expiration_date',
                            'ri.accepted_date',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

                        ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
                        ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
                        ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
                        ->where('it.team_leader_id','=',$person->person_id)

                        ->where('rs.name','=','Accepted')
                        ->where('b.brgy_id','=',$this->modal['brgy_id'] )
                        ->where('b.business_category_id','=',$this->modal['business_category_id'])
                        ->where('b.name','like',$this->modal['search'] .'%')
                        ->orderBy('ri.id','desc')
                        ->limit(15)
                        ->get()
                        ->toArray();
                }else{
                    $this->businesses = DB::table('request_inspections as ri')
                        ->select(
                            'b.id',
                            'b.img_url',
                            'b.name',
                            'b.business_category_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'brg.brgyDesc as barangay',
                            'bt.name as business_type_name',
                            'oc.character_of_occupancy as occupancy_classification_name',
                            'b.contact_number',
                            'b.email',
                            'b.floor_area',
                            'b.signage_area',
                            'b.is_active',
                            'rs.name as status_name',
                            'ri.request_date',
                            'ri.expiration_date',
                            'ri.accepted_date',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

                        ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
                        ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
                        ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
                        ->where('it.team_leader_id','=',$person->person_id)

                        ->where('rs.name','=','Accepted')
                        ->where('b.brgy_id','=',$this->modal['brgy_id'] )
                        ->where('b.name','like',$this->modal['search'] .'%')
                        ->orderBy('ri.id','desc')
                        ->limit(15)
                        ->get()
                        ->toArray();
                }
            }else{
                if(intval($this->modal['business_category_id'])){
                    $this->businesses = DB::table('request_inspections as ri')
                        ->select(
                            'b.id',
                            'b.img_url',
                            'b.name',
                            'b.business_category_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'brg.brgyDesc as barangay',
                            'bt.name as business_type_name',
                            'oc.character_of_occupancy as occupancy_classification_name',
                            'b.contact_number',
                            'b.email',
                            'b.floor_area',
                            'b.signage_area',
                            'b.is_active',
                            'rs.name as status_name',
                            'ri.request_date',
                            'ri.expiration_date',
                            'ri.accepted_date',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

                        ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
                        ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
                        ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
                        ->where('it.team_leader_id','=',$person->person_id)

                        ->where('rs.name','=','Accepted')
                        ->where('b.business_category_id','=',$this->modal['business_category_id'])
                        ->where('b.name','like',$this->modal['search'] .'%')
                        ->orderBy('ri.id','desc')
                        ->limit(15)
                        ->get()
                        ->toArray();
                }else{
                    $this->businesses = DB::table('request_inspections as ri')
                        ->select(
                            'b.id',
                            'b.img_url',
                            'b.name',
                            'b.business_category_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'brg.brgyDesc as barangay',
                            'bt.name as business_type_name',
                            'oc.character_of_occupancy as occupancy_classification_name',
                            'b.contact_number',
                            'b.email',
                            'b.floor_area',
                            'b.signage_area',
                            'b.is_active',
                            'rs.name as status_name',
                            'ri.request_date',
                            'ri.expiration_date',
                            'ri.accepted_date',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

                        ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
                        ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
                        ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
                        ->where('it.team_leader_id','=',$person->person_id)

                        ->where('rs.name','=','Accepted')
                        ->where('b.name','like',$this->modal['search'] .'%')
                        ->orderBy('ri.id','desc')
                        ->limit(15)
                        ->get()
                        ->toArray();
                }
            }
        }else{
            if(intval($this->modal['brgy_id'])){
                if(intval($this->modal['business_category_id'])){
                    $this->businesses = DB::table('businesses as b')
                        ->select(
                            'b.id',
                            'b.img_url',
                            'b.name',
                            'b.business_category_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'brg.brgyDesc as barangay',
                            'bt.name as business_type_name',
                            'oc.character_of_occupancy as occupancy_classification_name',
                            'b.contact_number',
                            'b.email',
                            'b.floor_area',
                            'b.signage_area',
                            'b.is_active',
                        )
                        ->where('b.is_active','=',1)
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

                        ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
                        ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
                        ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
                        ->where('it.team_leader_id','=',$person->person_id)

                        ->where('b.brgy_id','=',$this->modal['brgy_id'] )
                        ->where('b.name','like',$this->modal['search'] .'%')
                        ->where('b.business_category_id','=',$this->modal['business_category_id'])
                        ->limit(15)
                        ->get()
                        ->toArray();
                }else{
                    $this->businesses = DB::table('businesses as b')
                        ->select(
                            'b.id',
                            'b.img_url',
                            'b.name',
                            'b.business_category_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'brg.brgyDesc as barangay',
                            'bt.name as business_type_name',
                            'oc.character_of_occupancy as occupancy_classification_name',
                            'b.contact_number',
                            'b.email',
                            'b.floor_area',
                            'b.signage_area',
                            'b.is_active',
                        )
                        ->where('b.is_active','=',1)
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

                        ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
                        ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
                        ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
                        ->where('it.team_leader_id','=',$person->person_id)

                        ->where('b.brgy_id','=',$this->modal['brgy_id'] )
                        ->where('b.name','like',$this->modal['search'] .'%')
                        ->where('b.business_category_id','=',$this->modal['business_category_id'])
                        ->limit(15)
                        ->get()
                        ->toArray();
                }
            }else{
                if(intval($this->modal['business_category_id'])){
                    $this->businesses = DB::table('businesses as b')
                        ->select(
                            'b.id',
                            'b.img_url',
                            'b.name',
                            'b.business_category_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'brg.brgyDesc as barangay',
                            'bt.name as business_type_name',
                            'oc.character_of_occupancy as occupancy_classification_name',
                            'b.contact_number',
                            'b.email',
                            'b.floor_area',
                            'b.signage_area',
                            'b.is_active',
                        )
                        ->where('b.is_active','=',1)
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

                        ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
                        ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
                        ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
                        ->where('it.team_leader_id','=',$person->person_id)

                        ->where('b.name','like',$this->modal['search'] .'%')
                        ->where('b.business_category_id','=',$this->modal['business_category_id'])
                        ->limit(15)
                        ->get()
                        ->toArray();
                }else{
                    $this->businesses = DB::table('businesses as b')
                        ->select(
                            'b.id',
                            'b.img_url',
                            'b.name',
                            'b.business_category_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'brg.brgyDesc as barangay',
                            'bt.name as business_type_name',
                            'oc.character_of_occupancy as occupancy_classification_name',
                            'b.contact_number',
                            'b.email',
                            'b.floor_area',
                            'b.signage_area',
                            'b.is_active',
                        )
                        ->where('b.is_active','=',1)
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

                        ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
                        ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
                        ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
                        ->where('it.team_leader_id','=',$person->person_id)

                        ->where('b.name','like',$this->modal['search'] .'%')
                        ->limit(15)
                        ->get()
                        ->toArray();
                }
            }
        }

        if($this->search['search'] != $this->search['search_prev']){
            $this->search['search_prev'] = $this->search['search'];
            $this->resetPage();
        }
        if($this->search['type'] != $this->search['type_prev']){
            $this->search['type_prev'] = $this->search['type'];
            if($this->search['type'] == 'b.contact_number'){
                $this->search['search'] = substr($this->search['search'],1);
            }
            $this->resetPage();
        }else{
            if(!$this->search['type']){
                $this->search['type'] = $this->search_by[0]['column_name'];
            }
        }
        if(intval($this->search['brgy_id']) ){
            $table_data = DB::table('inspections as i')
                ->select(
                    'i.id',
                    'b.img_url',
                    'b.name',
                    'p.first_name',
                    'p.middle_name',
                    'p.last_name',
                    'p.suffix',
                    'brg.brgyDesc as barangay',
                    'bt.name as business_type_name',
                    'oc.character_of_occupancy as occupancy_classification_name',
                    'b.contact_number',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'b.is_active',
                    'st.name as status_name',
                    'i.schedule_date',

                )
                ->join('inspection_inspector_team_leaders as iitl','iitl.inspection_id','i.id')
                ->join('inspection_status as st','st.id','i.status_id')
                ->join('businesses as b','b.id','i.business_id')
                ->leftjoin('persons as p','p.id','b.owner_id')
                ->join('brgy as brg','brg.id','b.brgy_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                ->where('iitl.person_id','=',$person->person_id)
                ->where('st.name','=','Pending')
                ->where('b.brgy_id','=',$this->search['brgy_id'] )
                ->where($this->search['type'],'like',$this->search['search'] .'%')
                ->orderBy('i.id','desc')
                ->paginate($this->table_filter['table_rows']);
        }else{
            $table_data = DB::table('inspections as i')
                ->select(
                    'i.id',
                    'b.img_url',
                    'b.name',
                    'p.first_name',
                    'p.middle_name',
                    'p.last_name',
                    'p.suffix',
                    'brg.brgyDesc as barangay',
                    'bt.name as business_type_name',
                    'oc.character_of_occupancy as occupancy_classification_name',
                    'b.contact_number',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'b.is_active',
                    'st.name as status_name',
                    'i.schedule_date',

                )
                ->join('inspection_inspector_team_leaders as iitl','iitl.inspection_id','i.id')
                ->join('inspection_status as st','st.id','i.status_id')
                ->join('businesses as b','b.id','i.business_id')
                ->leftjoin('persons as p','p.id','b.owner_id')
                ->join('brgy as brg','brg.id','b.brgy_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                ->where('iitl.person_id','=',$person->person_id)
                ->where('st.name','=','Pending')
                ->where($this->search['type'],'like',$this->search['search'] .'%')
                ->orderBy('i.id','desc')
                ->paginate($this->table_filter['table_rows']);
        }
        // $this->businesses = DB::table('businesses as b')
        //     ->select(
        //         'b.id',
        //         'b.img_url',
        //         'b.name',
        //         'p.first_name',
        //         'p.middle_name',
        //         'p.last_name',
        //         'p.suffix',
        //         'brg.brgyDesc as barangay',
        //         'bt.name as business_type_name',
        //         'oc.character_of_occupancy as occupancy_classification_name',
        //         'b.contact_number',
        //         'b.email',
        //         'b.floor_area',
        //         'b.signage_area',
        //         'b.is_active'
        //     )
        //     ->join('persons as p','p.id','b.owner_id')
        //     ->join('business_types as bt','bt.id','b.business_type_id')
        //     ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
        
        //     ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
        //     ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
        //     ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
        //     ->where('it.team_leader_id','=',$person->person_id)
        //     ->where('b.is_active','=',1)
        //     ->get()
        //     ->toArray();
        
        return view('livewire.admin.inspector-team-leader.inspections.inspection-schedules.inspection-schedules',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }

    public function update_business_id(){
        $this->inspection['business_id'] = NULL;
        $this->inspection['last_inspection'] = NULL;
    }
    
    public function last_inspection(){
        if(intval($this->inspection['business_id'])){
            $this->inspection['last_inspection'] = DB::table('inspections as i')
                ->join('inspection_status as is','is.id','i.status_id')
                ->where('business_id','=',$this->inspection['business_id'])
                ->where('is.name','=','Completed')
                ->orderBy('i.id','desc')
                ->first();
        }
    }
    public function add($modal_id){
        $this->business_categories = DB::table('business_category')
        ->get()
        ->toArray();
        $this->from_request = false;
        $this->business_from = 'Bypass';
        $this->modal['brgy_id'] = NULL;
        $this->inspection = [
            'id'=>NULL,
            'inspector_leaders' =>[],
            'inspector_leader_id'=>NULL,
            'inspector_members' => [],
            'inspector_member_id'=>NULL,
            'business_id' =>NULL,
            'schedule_date'=>date_format(date_create(now()),"Y-m-d"),
            'step'=> 1,
            'last_inspection'=> NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }


    public function next(Request $request, $modal_id){
        $session = $request->session()->all();
        if($this->inspection['step'] == 1){
            if(intval($this->inspection['business_id'])){
                $this->inspection['step']+=1;
                // self add
                $inspector_leaders = DB::table('persons as p')
                ->select(
                    "p.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                )
                ->join('inspector_teams as it','p.id','it.team_leader_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                ->join('users as u','p.id','u.person_id')
                ->whereNotNull('it.team_leader_id')
                ->where('u.id','=',$session['id'])
                ->where('pt.name','Inspector')
                ->first();
                $this->inspection['inspector_leader_id'] = $inspector_leaders->id;
                self::add_team_leader();
            }else{
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please select business!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }elseif($this->inspection['step'] == 2){
            if(count($this->inspection['inspector_leaders'])){
                $this->inspection['step']+=1;
            }else{
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please add at least 1 team leader!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            
        }elseif($this->inspection['step'] == 3){
            if(!intval($this->inspection['business_id'])){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please select business!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            if(!count($this->inspection['inspector_leaders'])){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please add at least 1 team leader!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }

            $status = DB::table('inspection_status')
                ->where('name','Pending')
                ->first();

            //add
            if($this->business_from == 'from-email'){
                $businesses = DB::table('request_inspections as ri')
                ->select(
                    'ri.id',
                    'b.id as business_id',
                    'b.img_url',
                    'b.name',
                    'b.business_category_id',
                    'p.first_name',
                    'p.middle_name',
                    'p.last_name',
                    'p.suffix',
                    'brg.brgyDesc as barangay',
                    'bt.name as business_type_name',
                    'oc.character_of_occupancy as occupancy_classification_name',
                    'b.contact_number',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'b.is_active',
                    'rs.name as status_name',
                    'ri.request_date',
                    'ri.expiration_date',
                    'ri.accepted_date',
                    'ri.reason',
                    'brg.brgyDesc as barangay',
                )
                ->join('request_status as rs','rs.id','ri.status_id')
                ->join('businesses as b','b.id','ri.business_id')
                ->join('persons as p','p.id','b.owner_id')
                ->join('brgy as brg','brg.id','b.brgy_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                ->where('rs.name','=','Accepted')
                ->first();
                $this->inspection['business_id'] = $businesses->business_id;
            }
            if( DB::table('inspections')
                ->insert([
                    'status_id' => $status->id , 
                    'business_id' =>$this->inspection['business_id'], 
                    'schedule_date' =>$this->inspection['schedule_date'],
            ])){
                if($this->business_from == 'from-email'){
                    $status = DB::table('request_status')
                    ->where('name','=',"Completed")
                    ->first();
                    DB::table('request_inspections as ri')
                        ->join('request_status as rs','ri.status_id','rs.id')
                        ->where('rs.name','=','Accepted')
                        ->where('ri.id','=',$businesses->id)
                        ->update([
                            'status_id'=>$status->id,
                        ]);
                }
                $inspection = DB::table('inspections')
                    ->orderBy('id','desc')
                    ->first();
                foreach ($this->inspection['inspector_leaders'] as $key => $value) {
                    DB::table('inspection_inspector_team_leaders')
                    ->insert([
                        'inspection_id' => $inspection->id,
                        'person_id' => $value->id,
                    ]);
                }
                foreach ($this->inspection['inspector_members'] as $key => $value) {
                    DB::table('inspection_inspector_members')
                    ->insert([
                        'inspection_id' => $inspection->id,
                        'person_id' => $value->id,
                    ]);
                }
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'success',
                    title             									: 'Schedule has been added!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                $edit = DB::table('businesses as b')
                    ->select(
                        'b.id',
                        'b.img_url',
                        'b.name',
                        'p.first_name',
                        'p.middle_name',
                        'p.last_name',
                        'p.suffix',
                        'brg.brgyDesc as barangay',
                        'bt.name as business_type_name',
                        'oc.character_of_occupancy as occupancy_classification_name',
                        'b.is_active',
                        'b.brgy_id',
                        'b.owner_id',
                        'b.occupancy_classification_id',
                        'b.business_type_id',
                        'b.street_address',
                        'b.contact_number',
                        'b.email',
                        'b.floor_area',
                        'b.signage_area',



                    )
                    ->join('persons as p','p.id','b.owner_id')
                    ->join('brgy as brg','brg.id','b.brgy_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->where('b.id','=',$this->inspection['business_id'])
                    ->first();
                DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has added an inspection for '.$edit->name.' (' .$edit->business_type_name. ') ',
                ]);
                $this->dispatch('openModal',$modal_id);
                return 0;
            }
        }
    }
    public function prev(){
        $this->inspection['step']-=1;
    }
    public function update_inspection_data($id){
        $inspection = DB::table('inspections as i')
            ->select(
                'i.id',
                'b.img_url',
                'b.name as business_name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'b.street_address',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'b.is_active',
                'st.name as status_name',
                'i.schedule_date',
                "i.status_id",
                "i.business_id",
                "i.signage_id",
                "i.building_billing_id",
                "i.application_type_id",
                "i.remarks",
                "i.date_signed",
            )
            ->join('inspection_status as st','st.id','i.status_id')
            ->join('businesses as b','b.id','i.business_id')
            ->leftjoin('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('i.id','=',$id)
            ->first();

        if( $business = DB::table('request_business_categories as rbc')
            ->select(
                'rbc.id as rbc_id',
                'b.id',
                'b.img_url',
                'b.name',
                'b.business_category_id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'b.is_active',
            )
            ->rightjoin('businesses as b','b.business_category_id','rbc.business_category_id')
            ->whereNotNull('rbc.id')
            ->where('b.is_active','=',1)
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('b.id','=',$inspection->business_id)
            ->get()
            ->toArray()){
            $segragated = true;

            $type_id = 1; 
            // segregated // segregated// segregated// segregated// segregated// segregated// segregated// segregated// segregated

            $application_types = DB::table('application_types')
                ->where('is_active','=',1)
                ->get()
                ->toArray();
            $signage_billings = DB::table('signage_billings as sb')
                ->select(
                    'sb.id',
                    'sbdt.name as display_type_name',
                    'sbt.name as sign_type_name',
                    'sb.fee',
                    'sb.is_active'
                )
                ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
                ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
                ->where('sb.is_active','=',1)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($signage_billings as $key => $value) {
                array_push($temp,[
                    "id" => $value->id,
                    'display_type_name'=> $value->display_type_name,
                    'sign_type_name' =>$value->sign_type_name,
                    "fee" => $value->fee,
                    "is_active" => $value->is_active,
                ]);
            }
            $signage_billings = $temp;
            $sanitary_billings = DB::table('sanitary_billings')
                ->select(
                    'id' ,
                    'name' ,
                    'is_active',
                    'fee' ,
                )
                ->where('is_active','=',1)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($sanitary_billings as $key => $value) {
                array_push($temp,[
                    'id' => $value->id,
                    'name' => $value->name,
                    'is_active'=> $value->is_active,
                    'fee' => $value->fee,
                ]);
            }
            $sanitary_billings = $temp;
            $inspector_members = DB::table('persons as p')
                ->select(
                    "p.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                    'iit.name as inspector_team',
                )
                ->leftjoin('inspector_members as im','im.member_id','p.id')
                ->leftjoin('inspector_teams as iit','iit.id','im.inspector_team_id')
                ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                ->whereNull('it.team_leader_id')
                ->where('pt.name','Inspector')
                ->get()
                ->toArray();
            
        
           
           
            $inspection_items = DB::table('inspection_items as ii')
                ->select(
                    'c.name as category_name',
                    'c.id as category_id',
                    'i.name',
                    'i.section_id',
                    'i.img_url',
                    'i.is_active',
                    "ii.id",
                    "ii.inspection_id",
                    "ii.item_id",
                    "ii.equipment_billing_id",
                    "ii.power_rating",
                    "ii.quantity",
                    "eb.fee",
                    'ebs.name as section_name',
                    'ii.added_by',
                    )
                ->join('items as i','i.id','ii.item_id')
                ->join('equipment_billing_sections as ebs','ebs.id','i.category_id')
                ->join('categories as c','c.id','i.category_id')
                ->leftjoin('equipment_billings as eb','eb.id','ii.equipment_billing_id')
                ->where('ii.inspection_id','=',$id)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($inspection_items as $key => $value) {
                array_push($temp,[
                    'category_name' => $value->category_name,
                    'category_id' => $value->category_id,
                    'name'=> $value->name,
                    'section_id' => $value->section_id,
                    'img_url' => $value->img_url,
                    'is_active' => $value->is_active,
                    "id" => $value->id,
                    "inspection_id" => $value->inspection_id,
                    "item_id" => $value->item_id,
                    "equipment_billing_id" => $value->equipment_billing_id,
                    "power_rating" => $value->power_rating,
                    "quantity" => $value->quantity,
                    "fee" => $value->fee,
                    'section_name'=>$value->section_name,
                    'added_by'=>$value->added_by,
                ]);
            }
            $inspection_items = $temp;
            $inspection_inspector_members = DB::table('persons as p')
                ->select(
                    "iim.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                    'it_member_team.name as inspector_team',
                    // 'ic.id as inspector_category_id',
                    // 'c.name as category_name',
                    // 'c.id as category_id',
                )
                ->leftjoin('inspector_members as im','im.member_id','p.id')
                ->leftjoin('inspector_teams as it_member_team','it_member_team.id','im.inspector_team_id')
                ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
                ->leftjoin('inspection_inspector_members as iim','p.id','iim.person_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                // ->join('inspector_item_category as ic','p.id','ic.person_id')
                // ->join('categories as c','c.id','ic.category_id')
                // ->orderBy(DB::raw('LOWER(c.name)'),'asc')
                ->where('pt.name','Inspector')
                ->where('iim.inspection_id','=',$id)
                ->get()
                ->toArray();

            $inspection_inspector_team_leaders = DB::table('persons as p')
                ->select(
                    "p.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                    'it.name as inspector_team',
                    // 'ic.id as inspector_category_id',
                    // 'c.name as category_name',
                    // 'c.id as category_id',
                )
                ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                // ->leftjoin('inspector_item_category as ic','p.id','ic.person_id')
                // ->join('categories as c','c.id','ic.category_id')
                // ->orderBy(DB::raw('LOWER(c.name)'),'asc')
                // ->groupBy('it.team_leader_id')
                ->whereNotNull('it.team_leader_id')
                ->where('pt.name','Inspector')
                ->get()
                ->toArray();
            $inspector_team_leaders = DB::table('persons as p')
                ->select(
                    "iitl.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                    'it.name as inspector_team',
                )
                ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
                ->join('inspection_inspector_team_leaders as iitl','p.id','iitl.person_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                ->where('pt.name','Inspector')
                ->where('iitl.inspection_id','=',$id)
                ->get()
                ->toArray();

            $inspection_sanitary_billings = DB::table('inspection_sanitary_billings as isb')
                ->select(
                    'isb.id' ,
                    'isb.inspection_id' ,
                    'isb.sanitary_billing_id' ,
                    'isb.sanitary_quantity' ,
                    'sb.fee' ,
                    'sb.name as sanitary_name',
                )
                ->join('sanitary_billings as sb','sb.id','isb.sanitary_billing_id')
                ->where('isb.inspection_id','=',$id)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($inspection_sanitary_billings as $key => $value) {
                array_push($temp,[
                    "id" => $value->id,
                    "inspection_id" => $value->inspection_id,
                    "sanitary_billing_id" => $value->sanitary_billing_id,
                    "sanitary_quantity" => $value->sanitary_quantity,
                    "sanitary_quantity" => $value->sanitary_quantity,
                    "fee" => $value->fee,
                    "sanitary_name" => $value->sanitary_name,
                ]);
            }
            $inspection_sanitary_billings = $temp;
            $inspection_violations = DB::table('inspection_violations as iv')
                ->select(
                    'iv.id',
                    'v.id as violation_id',
                    'description',
                    'ivc.name as category_name',
                    'v.is_active',
                    'iv.added_by',
                    'remarks'
                )
                ->join('violations as v','v.id','iv.violation_id')
                ->join('violation_category as ivc','v.category_id','ivc.id')
                ->where('iv.inspection_id','=',$id)
                ->get()
                ->toArray();
            $temp = [];
            if(count($inspection_violations)){
                DB::table('inspections as i')
                    ->where('i.id','=',$id)
                    ->update([
                        'remarks'=> 'With Violation/s'
                    ]);
            }else{
                DB::table('inspections as i')
                    ->where('i.id','=',$id)
                    ->update([
                        'remarks'=> 'No Violation'
                    ]);
            }
            foreach ($inspection_violations as $key => $value) {
                array_push($temp,[
                    'description'=> $value->description,
                    'category_name'=> $value->category_name,
                    "id" => $value->id,
                    "added_by" => $value->added_by,
                    "remarks" => $value->remarks,
                    "violation_id" => $value->violation_id,
                ]);
            }
            $inspection_violations = $temp;

           

            $items = DB::table('items as i')
                ->select(
                    'i.id',
                    'c.name as category_name',
                    'i.name',
                    'i.section_id',
                    'i.img_url',
                    'i.is_active',
                    'ebs.name as section_name',
                    'iic.person_id',
                    )
                ->join('equipment_billing_sections as ebs','ebs.id','i.category_id')
                ->join('categories as c','c.id','i.category_id')
                ->join('inspector_item_category as iic','i.category_id','iic.category_id')
                ->where('i.is_active','=',1)
                ->where('iic.person_id','=',$this->activity_logs['inspector_team_id'])
                ->where('iic.type_id','=',$type_id)
                ->get()
                ->toArray();
                
            $violations = DB::table('violations as v')
                ->select(
                    'v.id',
                    'description',
                    'vc.name as category_name',
                    'vc.id as category_id',
                    'v.is_active',
                )
                ->join('violation_category as vc','v.category_id','vc.id')
                ->join('inspector_violation_category as ivc','v.category_id','ivc.category_id')
                ->where('v.is_active','=',1)
                ->orderBy(DB::raw('LOWER(vc.name)'),'asc')
                ->where('ivc.person_id','=',$this->activity_logs['inspector_team_id'])
                ->where('ivc.type_id','=',$type_id)
                ->get()
                ->toArray();
                

            $building_billings = DB::table('building_billings as bb')
                ->select(
                    "bb.id",
                    "bb.section_id",
                    'bbs.name as section_name',
                    "bb.property_attribute",
                    "bb.fee",
                )
                ->join('building_billing_sections as bbs','bbs.id','bb.section_id')
                ->where('bb.is_active','=',1)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($building_billings as $key => $value) {
                array_push($temp,[
                    'id' => $value->id,
                    'section_id' => $value->section_id,
                    'section_name'=> $value->section_name,
                    'property_attribute'=> $value->property_attribute,
                    'fee' => $value->fee,
                ]);
            }
            $building_billings = $temp;

            $building_billing = DB::table('building_billings')
                ->where('id','=',$inspection->building_billing_id)
                ->first();
            $building_billing_fee = 0;
            if($building_billing){
                $building_billing_fee = $building_billing->fee;
            }
            $signage_billing = DB::table('signage_billings')
                ->where('id','=',$inspection->signage_id)
                ->first();
            $signage_billing_fee = 0;
            if($signage_billing){
                $signage_billing_fee = $signage_billing->fee;
            }
            // segregated // segregated// segregated// segregated// segregated// segregated// segregated// segregated// segregated
        }else{
            $segragated = false;
            $type_id = 2; 
            $application_types = DB::table('application_types')
                ->where('is_active','=',1)
                ->get()
                ->toArray();
            $signage_billings = DB::table('signage_billings as sb')
                ->select(
                    'sb.id',
                    'sbdt.name as display_type_name',
                    'sbt.name as sign_type_name',
                    'sb.fee',
                    'sb.is_active'
                )
                ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
                ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
                ->where('sb.is_active','=',1)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($signage_billings as $key => $value) {
                array_push($temp,[
                    "id" => $value->id,
                    'display_type_name'=> $value->display_type_name,
                    'sign_type_name' =>$value->sign_type_name,
                    "fee" => $value->fee,
                    "is_active" => $value->is_active,
                ]);
            }
            $signage_billings = $temp;
            $sanitary_billings = DB::table('sanitary_billings')
                ->select(
                    'id' ,
                    'name' ,
                    'is_active',
                    'fee' ,
                )
                ->where('is_active','=',1)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($sanitary_billings as $key => $value) {
                array_push($temp,[
                    'id' => $value->id,
                    'name' => $value->name,
                    'is_active'=> $value->is_active,
                    'fee' => $value->fee,
                ]);
            }
            $sanitary_billings = $temp;
            $inspector_members = DB::table('persons as p')
                ->select(
                    "p.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                    'iit.name as inspector_team',
                )
                ->leftjoin('inspector_members as im','im.member_id','p.id')
                ->leftjoin('inspector_teams as iit','iit.id','im.inspector_team_id')
                ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                ->whereNull('it.team_leader_id')
                ->where('pt.name','Inspector')
                ->get()
                ->toArray();

            $items = DB::table('items as i')
                ->select(
                    'i.id',
                    'c.name as category_name',
                    'i.name',
                    'i.section_id',
                    'i.img_url',
                    'i.is_active',
                    'ebs.name as section_name',
                    'iic.person_id',
                    )
                ->join('equipment_billing_sections as ebs','ebs.id','i.category_id')
                ->join('categories as c','c.id','i.category_id')
                ->join('inspector_item_category as iic','i.category_id','iic.category_id')
                ->where('i.is_active','=',1)
                ->where('iic.person_id','=',$this->activity_logs['inspector_team_id'])
                ->where('iic.type_id','=',$type_id)
                ->get()
                ->toArray();
                
            $violations = DB::table('violations as v')
                ->select(
                    'v.id',
                    'description',
                    'vc.name as category_name',
                    'vc.id as category_id',
                    'v.is_active',
                )
                ->join('violation_category as vc','v.category_id','vc.id')
                ->join('inspector_violation_category as ivc','v.category_id','ivc.category_id')
                ->where('v.is_active','=',1)
                ->orderBy(DB::raw('LOWER(vc.name)'),'asc')
                ->where('ivc.person_id','=',$this->activity_logs['inspector_team_id'])
                ->where('ivc.type_id','=',$type_id)
                ->get()
                ->toArray();
        
            $inspection_items = DB::table('inspection_items as ii')
                ->select(
                    'c.name as category_name',
                    'c.id as category_id',
                    'i.name',
                    'i.section_id',
                    'i.img_url',
                    'i.is_active',
                    "ii.id",
                    "ii.inspection_id",
                    "ii.item_id",
                    "ii.equipment_billing_id",
                    "ii.power_rating",
                    "ii.quantity",
                    "eb.fee",
                    'ebs.name as section_name',
                    'ii.added_by',
                    )
                ->join('items as i','i.id','ii.item_id')
                ->join('equipment_billing_sections as ebs','ebs.id','i.category_id')
                ->join('categories as c','c.id','i.category_id')
                ->leftjoin('equipment_billings as eb','eb.id','ii.equipment_billing_id')
                ->where('ii.inspection_id','=',$id)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($inspection_items as $key => $value) {
                array_push($temp,[
                    'category_name' => $value->category_name,
                    'category_id' => $value->category_id,
                    'name'=> $value->name,
                    'section_id' => $value->section_id,
                    'img_url' => $value->img_url,
                    'is_active' => $value->is_active,
                    "id" => $value->id,
                    "inspection_id" => $value->inspection_id,
                    "item_id" => $value->item_id,
                    "equipment_billing_id" => $value->equipment_billing_id,
                    "power_rating" => $value->power_rating,
                    "quantity" => $value->quantity,
                    "fee" => $value->fee,
                    'section_name'=>$value->section_name,
                    'added_by'=>$value->added_by,
                ]);
            }
            $inspection_items = $temp;
            $inspection_inspector_members = DB::table('persons as p')
                ->select(
                    "iim.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                    'it_member_team.name as inspector_team',
                    // 'ic.id as inspector_category_id',
                    // 'c.name as category_name',
                    // 'c.id as category_id',
                )
                ->leftjoin('inspector_members as im','im.member_id','p.id')
                ->leftjoin('inspector_teams as it_member_team','it_member_team.id','im.inspector_team_id')
                ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
                ->leftjoin('inspection_inspector_members as iim','p.id','iim.person_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                // ->join('inspector_item_category as ic','p.id','ic.person_id')
                // ->join('categories as c','c.id','ic.category_id')
                // ->orderBy(DB::raw('LOWER(c.name)'),'asc')
                ->where('pt.name','Inspector')
                ->where('iim.inspection_id','=',$id)
                ->get()
                ->toArray();

            $inspection_inspector_team_leaders = DB::table('persons as p')
                ->select(
                    "p.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                    'it.name as inspector_team',
                    // 'ic.id as inspector_category_id',
                    // 'c.name as category_name',
                    // 'c.id as category_id',
                )
                ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                // ->leftjoin('inspector_item_category as ic','p.id','ic.person_id')
                // ->join('categories as c','c.id','ic.category_id')
                // ->orderBy(DB::raw('LOWER(c.name)'),'asc')
                // ->groupBy('it.team_leader_id')
                ->whereNotNull('it.team_leader_id')
                ->where('pt.name','Inspector')
                ->get()
                ->toArray();


            $inspector_team_leaders = DB::table('persons as p')
                ->select(
                    "iitl.id",
                    "p.person_type_id",
                    "p.brgy_id",
                    "p.work_role_id",
                    "p.first_name",
                    "p.middle_name",
                    "p.last_name",
                    "p.suffix",
                    "p.contact_number",
                    "p.email",
                    "p.img_url",
                    'wr.name as work_role_name',
                    'it.name as inspector_team',
                )
                ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
                ->join('inspection_inspector_team_leaders as iitl','p.id','iitl.person_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                ->where('pt.name','Inspector')
                ->where('iitl.inspection_id','=',$id)
                ->get()
                ->toArray();

            $inspection_sanitary_billings = DB::table('inspection_sanitary_billings as isb')
                ->select(
                    'isb.id' ,
                    'isb.inspection_id' ,
                    'isb.sanitary_billing_id' ,
                    'isb.sanitary_quantity' ,
                    'sb.fee' ,
                    'sb.name as sanitary_name',
                )
                ->join('sanitary_billings as sb','sb.id','isb.sanitary_billing_id')
                ->where('isb.inspection_id','=',$id)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($inspection_sanitary_billings as $key => $value) {
                array_push($temp,[
                    "id" => $value->id,
                    "inspection_id" => $value->inspection_id,
                    "sanitary_billing_id" => $value->sanitary_billing_id,
                    "sanitary_quantity" => $value->sanitary_quantity,
                    "sanitary_quantity" => $value->sanitary_quantity,
                    "fee" => $value->fee,
                    "sanitary_name" => $value->sanitary_name,
                ]);
            }
            $inspection_sanitary_billings = $temp;
            $inspection_violations = DB::table('inspection_violations as iv')
                ->select(
                    'iv.id',
                    'v.id as violation_id',
                    'description',
                    'ivc.name as category_name',
                    'v.is_active',
                    'iv.added_by',
                    'remarks'
                )
                ->join('violations as v','v.id','iv.violation_id')
                ->join('violation_category as ivc','v.category_id','ivc.id')
                ->where('iv.inspection_id','=',$id)
                ->get()
                ->toArray();
            $temp = [];
            if(count($inspection_violations)){
                DB::table('inspections as i')
                    ->where('i.id','=',$id)
                    ->update([
                        'remarks'=> 'With Violation/s'
                    ]);
            }else{
                DB::table('inspections as i')
                    ->where('i.id','=',$id)
                    ->update([
                        'remarks'=> 'No Violation'
                    ]);
            }
            foreach ($inspection_violations as $key => $value) {
                array_push($temp,[
                    'description'=> $value->description,
                    'category_name'=> $value->category_name,
                    "id" => $value->id,
                    "added_by" => $value->added_by,
                    "remarks" => $value->remarks,
                    "violation_id" => $value->violation_id,
                ]);
            }
            $inspection_violations = $temp;

            $building_billings = DB::table('building_billings as bb')
                ->select(
                    "bb.id",
                    "bb.section_id",
                    'bbs.name as section_name',
                    "bb.property_attribute",
                    "bb.fee",
                )
                ->join('building_billing_sections as bbs','bbs.id','bb.section_id')
                ->where('bb.is_active','=',1)
                ->get()
                ->toArray();
            $temp = [];
            foreach ($building_billings as $key => $value) {
                array_push($temp,[
                    'id' => $value->id,
                    'section_id' => $value->section_id,
                    'section_name'=> $value->section_name,
                    'property_attribute'=> $value->property_attribute,
                    'fee' => $value->fee,
                ]);
            }
            $building_billings = $temp;

            $building_billing = DB::table('building_billings')
                ->where('id','=',$inspection->building_billing_id)
                ->first();
            $building_billing_fee = 0;
            if($building_billing){
                $building_billing_fee = $building_billing->fee;
            }
            $signage_billing = DB::table('signage_billings')
                ->where('id','=',$inspection->signage_id)
                ->first();
            $signage_billing_fee = 0;
            if($signage_billing){
                $signage_billing_fee = $signage_billing->fee;
            }
        }
        $categories = DB::table('categories')
        ->where('is_active','=',1)
        ->get()
        ->toArray();

        $email_inspection_inspector_members = DB::table('persons as p')
            ->select(
                "iim.id",
                "p.person_type_id",
                "p.brgy_id",
                "p.work_role_id",
                "p.first_name",
                "p.middle_name",
                "p.last_name",
                "p.suffix",
                "p.contact_number",
                "p.email",
                "p.img_url",
                'wr.name as work_role_name',
                'it_member_team.name as inspector_team',
                'ivc.id as inspector_category_id',
                'vc.name as category_name',
                'vc.id as category_id',
            )
            ->leftjoin('inspector_members as im','im.member_id','p.id')
            ->leftjoin('inspector_teams as it_member_team','it_member_team.id','im.inspector_team_id')
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->leftjoin('inspection_inspector_members as iim','p.id','iim.person_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->join('inspector_violation_category as ivc','p.id','ivc.person_id')
            ->join('violation_category as vc','vc.id','ivc.category_id')   
            ->orderBy(DB::raw('LOWER(vc.name)'),'asc')
            ->where('pt.name','Inspector')
            ->where('iim.inspection_id','=',$id)
            ->get()
            ->toArray();

        $email_inspection_inspector_team_leaders = DB::table('persons as p')
            ->select(
                "p.id",
                "p.person_type_id",
                "p.brgy_id",
                "p.work_role_id",
                "p.first_name",
                "p.middle_name",
                "p.last_name",
                "p.suffix",
                "p.contact_number",
                "p.email",
                "p.img_url",
                'wr.name as work_role_name',
                'it.name as inspector_team',
                'ivc.id as inspector_category_id',
                'vc.name as category_name',
                'vc.id as category_id',
            )
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->leftjoin('inspection_inspector_team_leaders as iitl','p.id','iitl.person_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->join('inspector_violation_category as ivc','p.id','ivc.person_id')
            ->join('violation_category as vc','vc.id','ivc.category_id')   
            ->orderBy(DB::raw('LOWER(vc.name)'),'asc')
            ->whereNotNull('it.team_leader_id')
            ->where('pt.name','Inspector')
            ->where('iitl.inspection_id','=',$id)
            ->get()
            ->toArray();

       
        $inspector_bss_category = DB::table('inspector_bss_category as bbs')
            ->select(
                'bbs.id',
                'bc.name as category_name',
            )
            ->rightjoin('bss_category as bc','bc.id','bbs.category_id')
            ->where('bbs.person_id','=',$this->activity_logs['inspector_team_id'])
            ->where('bbs.type_id','=',$type_id)
            ->get()
            ->toArray();
        $violation_category = DB::table('violation_category')
            ->get()
            ->toArray();

        $steps = [
            ['name'=>'Details'],
            ['name'=>'Leaders'],
            ['name'=>'Members'],
        ];
        
        $this->issue_inspection = [
            'id' => $inspection->id,
            'status_id' => $inspection->status_id,
            'inspection' => $inspection,
            'business_id' => $inspection->business_id,
            'schedule_date' => $inspection->schedule_date,
            'signage_id' => $inspection->signage_id,
            'signage_billing_fee' => $signage_billing_fee,
            'building_billing_id' => $inspection->building_billing_id,
            'building_billing_fee' => $building_billing_fee,
            'application_type_id' => $inspection->application_type_id,
            'remarks' => $inspection->remarks,
            'date_signed' => $inspection->date_signed,
            
            'step'=> $this->issue_inspection['step'],
            'steps'=>$steps,
            'violation_category'=>$violation_category,
            'inspector_bss_category'=>$inspector_bss_category,
            'email_inspection_inspector_team_leaders'=>$email_inspection_inspector_team_leaders,
            'email_inspection_inspector_members'=>$email_inspection_inspector_members,
            'segregated'=>  $segragated,
            'inspection_business_name' => $inspection->business_name. ' ( '.$inspection->business_type_name.' )',
            'inspection_items' =>$inspection_items,
            'inspection_inspector_members' =>$inspection_inspector_members,
            'inspection_inspector_team_leaders' =>$inspection_inspector_team_leaders,
            'inspection_violations' =>$inspection_violations,
            'inspection_sanitary_billings' =>$inspection_sanitary_billings,
            'item_id'=> NULL,
            'violatio_id'=> NULL,
            'sanitary_billing_id'=> NULL,
            'inspector_leader_id'=> NULL,
            'inspector_member_id'=> NULL,

            'application_types'  =>$application_types,
            'items' =>$items,
            'signage_billings' =>$signage_billings,
            'building_billings'  =>$building_billings,
            'sanitary_billings'  =>$sanitary_billings,
            'inspector_members'  => $inspector_members,
            'inspector_team_leaders'  => $inspector_team_leaders,
            'violations'  =>$violations,
            'categories'=> $categories,
        ];
    }
    public function issue($id,$modal_id){
        self::update_inspection_data($id,1);
        $this->dispatch('openModal',$modal_id);
    }
    public function prev_issue(){
        $this->issue_inspection['step']--;
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function go_issue($step){
        $this->issue_inspection['step'] = $step;
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function next_issue(){
        $this->issue_inspection['step']++;
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_application_type(){
        if(intval($this->issue_inspection['application_type_id'])){
            DB::table('inspections as i')
                ->where('id','=',$this->issue_inspection['id'])
                ->update([
                    'application_type_id'=>$this->issue_inspection['application_type_id']
            ]);
            $var = DB::table('application_types')
                ->where('id','=',$this->issue_inspection['application_type_id'])
                ->first();
            
            $edit = DB::table('inspections as i')
                ->select(
                    'b.id',
                    'b.img_url',
                    'b.name',
                    'b.occupancy_classification_id',
                    'b.business_type_id',
                    'b.street_address',
                    'b.contact_number',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'bt.name as business_type_name',
                )
                ->join('businesses as b','b.id','i.business_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->where('i.id','=',$this->issue_inspection['id'])
                ->first();

            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has updated an application type ( '.$var->name.' ) for '.$edit->name.' (' .$edit->business_type_name. ') ',
            ]);
            
            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
        }
    }
    public function update_inspection_items(){
        if(intval($this->issue_inspection['item_id'])){
            $temp = DB::table('inspection_items')
            ->where('item_id','=',$this->issue_inspection['item_id'])
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->first();
            if($temp){

            }else{
                DB::table('inspection_items')
                ->insert([
                    'item_id' =>$this->issue_inspection['item_id'],
                    'inspection_id'=>$this->issue_inspection['id']
                ]);


                $var = DB::table('items')
                ->where('id','=',$this->issue_inspection['item_id'])
                ->first();
            
                $edit = DB::table('inspections as i')
                    ->select(
                        'b.id',
                        'b.img_url',
                        'b.name',
                        'b.occupancy_classification_id',
                        'b.business_type_id',
                        'b.street_address',
                        'b.contact_number',
                        'b.email',
                        'b.floor_area',
                        'b.signage_area',
                        'bt.name as business_type_name',
                    )
                    ->join('businesses as b','b.id','i.business_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->where('i.id','=',$this->issue_inspection['id'])
                    ->first();

                DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has added an item ( '.$var->name.' ) for '.$edit->name.' (' .$edit->business_type_name. ') ',
                ]);
                
            }
        }
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_equipment_billing($id,$key){
        if($this->issue_inspection['inspection_items'][$key]['equipment_billing_id']){
            DB::table('inspection_items')
                ->where('id','=',$id)
                ->where('inspection_id','=',$this->issue_inspection['id'])
                ->update([
                    'equipment_billing_id'=> $this->issue_inspection['inspection_items'][$key]['equipment_billing_id']
                ]);
            $var_1 = DB::table('equipment_billings')
                ->where('id','=',$this->issue_inspection['inspection_items'][$key]['equipment_billing_id'])
                ->first();
            $var = DB::table('items')
                ->where('id','=',$this->issue_inspection['inspection_items'][$key]['item_id'])
                ->first();
            
            $edit = DB::table('inspections as i')
                ->select(
                    'b.id',
                    'b.img_url',
                    'b.name',
                    'b.occupancy_classification_id',
                    'b.business_type_id',
                    'b.street_address',
                    'b.contact_number',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'bt.name as business_type_name',
                )
                ->join('businesses as b','b.id','i.business_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->where('i.id','=',$this->issue_inspection['id'])
                ->first();
            DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has updated an item ( '.$var->name.' ) capacity of '.$var_1->capacity.' for '.$edit->name.' (' .$edit->business_type_name. ') ',
            ]);
        }else{
            DB::table('inspection_items')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->update([
                'equipment_billing_id'=> NULL
            ]);
        }
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);

    }
    public function update_item_quantity($id,$key){
        $var = DB::table('items')
            ->where('id','=',$this->issue_inspection['inspection_items'][$key]['item_id'])
            ->first();
            
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has updated an item ( '.$var->name.' ) quantity of '.$this->issue_inspection['inspection_items'][$key]['quantity'].' for '.$edit->name.' (' .$edit->business_type_name. ') ',
        ]);
        DB::table('inspection_items')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->update([
                'quantity'=> $this->issue_inspection['inspection_items'][$key]['quantity']
            ]);
            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_item_power_rating($id,$key){
        $var = DB::table('items')
            ->where('id','=',$this->issue_inspection['inspection_items'][$key]['item_id'])
            ->first();
            
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has updated an item ( '.$var->name.' ) power rating of '.floatval($this->issue_inspection['inspection_items'][$key]['power_rating']).' for '.$edit->name.' (' .$edit->business_type_name. ') ',
        ]);
        DB::table('inspection_items')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->update([
                'power_rating'=> floatval($this->issue_inspection['inspection_items'][$key]['power_rating'])
            ]);
            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_building_billing(){
        $var = DB::table('building_billings as bb')
            ->select(
                'bbs.name as section_name',
                'bb.property_attribute',
                'bb.fee'
            )
            ->join('building_billing_sections as bbs','bbs.id', 'bb.section_id')
            ->where('bb.id','=',$this->issue_inspection['building_billing_id'])
            ->first();
            
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has updated building billing ( section '.$var->section_name.', property_attribute '.$var->property_attribute.', and fee of '.$var->fee.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
        ]);
        if(intval($this->issue_inspection['building_billing_id'])){
            DB::table('inspections as i')
                ->where('id','=',$this->issue_inspection['id'])
                ->update([
                    'building_billing_id'=>$this->issue_inspection['building_billing_id']
            ]);
            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
        }
    }
    public function update_inspection_sanitary_billings(){
        if(intval($this->issue_inspection['sanitary_billing_id'])){
            $temp = DB::table('inspection_sanitary_billings')
            ->where('sanitary_billing_id','=',$this->issue_inspection['sanitary_billing_id'])
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->first();
            if($temp){

            }else{
                DB::table('inspection_sanitary_billings')
                ->insert([
                    'sanitary_billing_id' =>$this->issue_inspection['sanitary_billing_id'],
                    'inspection_id'=>$this->issue_inspection['id']
                ]);
                $var = DB::table('sanitary_billings')
                ->where('id','=',$this->issue_inspection['sanitary_billing_id'])
                ->first();
                
                $edit = DB::table('inspections as i')
                    ->select(
                        'b.id',
                        'b.img_url',
                        'b.name',
                        'b.occupancy_classification_id',
                        'b.business_type_id',
                        'b.street_address',
                        'b.contact_number',
                        'b.email',
                        'b.floor_area',
                        'b.signage_area',
                        'bt.name as business_type_name',
                    )
                    ->join('businesses as b','b.id','i.business_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->where('i.id','=',$this->issue_inspection['id'])
                    ->first();
                DB::table('activity_logs')
                    ->insert([
                        'created_by' => $this->activity_logs['created_by'],
                        'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                        'log_details' => 'has added sanitary billing ( '.$var->name.', and fee of '.$var->fee.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
                ]);
                self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
            }
        }
    }
    public function update_sanitary_quantity($id,$key){
        $var = DB::table('inspection_sanitary_billings as isb')
            ->select(
                'ss.name',
                'ss.fee'
            )
            ->join('sanitary_billings as ss','ss.id','sanitary_billing_id')
            ->where('isb.id','=',$id)
            ->first();
            
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has updated sanitary billing quantity to '.$this->issue_inspection['inspection_sanitary_billings'][$key]['sanitary_quantity'].' of  ( '.$var->name.', and fee of '.$var->fee.' )   for '.$edit->name.' (' .$edit->business_type_name. ') ',
        ]);
        DB::table('inspection_sanitary_billings')
        ->where('id','=',$id)
        ->where('inspection_id','=',$this->issue_inspection['id'])
        ->update([
            'sanitary_quantity'=> $this->issue_inspection['inspection_sanitary_billings'][$key]['sanitary_quantity']
        ]);
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_signage_billing(){
        $var = DB::table('signage_billings as sb')
            ->select(
                'sbdt.name as display_type_name',
                'st.name as sign_type_name',
                'sb.fee'
            )
            ->join('signage_billing_display_types as sbdt','sbdt.id', 'sb.display_type_id')
            ->join('signage_billing_types as st','st.id', 'sb.sign_type_id')
            ->where('sb.id','=',$this->issue_inspection['building_billing_id'])
            ->first();
            
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has updated signage billing ( display type '.$var->display_type_name.', sign type '.$var->sign_type_name.', and fee of '.$var->fee.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
        ]);
        if(intval($this->issue_inspection['signage_id'])){
            DB::table('inspections as i')
                ->where('id','=',$this->issue_inspection['id'])
                ->update([
                    'signage_id'=>$this->issue_inspection['signage_id']
            ]);
            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
        }
    }
    public function update_inspection_team_leader(){
        if(intval($this->issue_inspection['inspector_leader_id'])){
            $temp = DB::table('inspection_inspector_team_leaders')
                ->where('inspection_id','=',$this->issue_inspection['id'])
                ->where('person_id','=',$this->issue_inspection['inspector_leader_id'])
                ->first();
            if($temp){

            }else{
                DB::table('inspection_inspector_team_leaders')
                    ->insert([
                        'inspection_id' => $this->issue_inspection['id'],
                        'person_id' =>$this->issue_inspection['inspector_leader_id']
                    ]);
                $var = DB::table('persons as p')
                ->where('id','=',$this->issue_inspection['inspector_leader_id'])
                ->first();
                    
                $edit = DB::table('inspections as i')
                    ->select(
                        'b.id',
                        'b.img_url',
                        'b.name',
                        'b.occupancy_classification_id',
                        'b.business_type_id',
                        'b.street_address',
                        'b.contact_number',
                        'b.email',
                        'b.floor_area',
                        'b.signage_area',
                        'bt.name as business_type_name',
                    )
                    ->join('businesses as b','b.id','i.business_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->where('i.id','=',$this->issue_inspection['id'])
                    ->first();
                DB::table('activity_logs')
                    ->insert([
                        'created_by' => $this->activity_logs['created_by'],
                        'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                        'log_details' => 'has added a team leader ( '.$var->first_name.' '.$var->middle_name.' '.$var->last_name.' '.$var->suffix.' '.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
                ]);
                self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
            }
        }
    }
    public function update_inspection_members(){
        if(intval($this->issue_inspection['inspector_member_id'])){
            $temp = DB::table('inspection_inspector_members')
                ->where('inspection_id','=',$this->issue_inspection['id'])
                ->where('person_id','=',$this->issue_inspection['inspector_member_id'])
                ->first();
            if($temp){

            }else{
                DB::table('inspection_inspector_members')
                    ->insert([
                        'inspection_id' => $this->issue_inspection['id'],
                        'person_id' =>$this->issue_inspection['inspector_member_id']
                    ]);
                $var = DB::table('persons as p')
                    ->where('id','=',$this->issue_inspection['inspector_member_id'])
                    ->first();
                    
                $edit = DB::table('inspections as i')
                    ->select(
                        'b.id',
                        'b.img_url',
                        'b.name',
                        'b.occupancy_classification_id',
                        'b.business_type_id',
                        'b.street_address',
                        'b.contact_number',
                        'b.email',
                        'b.floor_area',
                        'b.signage_area',
                        'bt.name as business_type_name',
                    )
                    ->join('businesses as b','b.id','i.business_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->where('i.id','=',$this->issue_inspection['id'])
                    ->first();
                DB::table('activity_logs')
                    ->insert([
                        'created_by' => $this->activity_logs['created_by'],
                        'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                        'log_details' => 'has added a team leader ( '.$var->first_name.' '.$var->middle_name.' '.$var->last_name.' '.$var->suffix.' '.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
                ]);
                self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
            }
        }
    }
    public function update_inspection_violation(){
        if(isset($this->issue_inspection['violation_id'])){
            $temp = DB::table('inspection_violations')
            ->where('violation_id','=',$this->issue_inspection['violation_id'])
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->first();
            if($temp){

            }else{
                DB::table('inspection_violations')
                ->insert([
                    'violation_id' =>$this->issue_inspection['violation_id'],
                    'inspection_id'=>$this->issue_inspection['id']
                ]);

                $var = DB::table('violations as p')
                    ->where('id','=',$this->issue_inspection['violation_id'])
                    ->first();
                    
                $edit = DB::table('inspections as i')
                    ->select(
                        'b.id',
                        'b.img_url',
                        'b.name',
                        'b.occupancy_classification_id',
                        'b.business_type_id',
                        'b.street_address',
                        'b.contact_number',
                        'b.email',
                        'b.floor_area',
                        'b.signage_area',
                        'bt.name as business_type_name',
                    )
                    ->join('businesses as b','b.id','i.business_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->where('i.id','=',$this->issue_inspection['id'])
                    ->first();
                DB::table('activity_logs')
                    ->insert([
                        'created_by' => $this->activity_logs['created_by'],
                        'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                        'log_details' => 'has added a violation ( '.$var->description.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
                ]);
                self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
            }
        }else{
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Select violation!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
    }
    public function add_team_leader(){
        if(intval($this->inspection['inspector_leader_id'])){
            $valid = true;
            foreach ($this->inspection['inspector_leaders'] as $key => $value) {
                if($value->id == $this->inspection['inspector_leader_id']){
                    $valid = false;
                }
            }
            if($valid){
                foreach ($this->inspector_leaders as $key => $value) {
                    if($this->inspection['inspector_leader_id'] == $value->id){
                        array_push($this->inspection['inspector_leaders'],$value);
                        $this->inspection['inspector_leader_id'] = NULL;
                    }
                }
            }
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Team leader has been added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select team leader!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
    }
    public function add_team_member(){
        if(intval($this->inspection['inspector_member_id'])){
            $valid = true;
            foreach ($this->inspection['inspector_members'] as $key => $value) {
                if($value->id == $this->inspection['inspector_member_id']){
                    $valid = false;
                }
            }
            if($valid){
                foreach ($this->inspector_members as $key => $value) {
                    if($this->inspection['inspector_member_id'] == $value->id){
                        array_push($this->inspection['inspector_members'],$value);
                        $this->inspection['inspector_member_id'] = NULL;
                    }
                }
            }
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Team member has been added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select team member!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
    }

    public function update_delete_item($id){
       
        $var = DB::table('inspection_items as ii')
            ->select('i.name')
            ->join('items as i','i.id','ii.item_id')
            ->where('ii.id','=',$id)
            ->first();
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deleted an item ( '.$var->name.' ) for '.$edit->name.' (' .$edit->business_type_name. ') ',
            ]);
        DB::table('inspection_items')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->delete();
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_delete_sanitary($id){
        $var = DB::table('inspection_sanitary_billings as isb')
            ->select(
                'ss.name',
                'ss.fee'
            )
            ->join('sanitary_billings as ss','ss.id','sanitary_billing_id')
            ->where('isb.id','=',$id)
            ->first();
            
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deleted sanitary billing of  ( '.$var->name.', and fee of '.$var->fee.' )   for '.$edit->name.' (' .$edit->business_type_name. ') ',
        ]);
        DB::table('inspection_sanitary_billings')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->delete();
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_delete_team_leaders($id){
        $temp =  DB::table('persons as p')
        ->select(
            "p.id",
            "p.person_type_id",
            "p.brgy_id",
            "p.work_role_id",
            "p.first_name",
            "p.middle_name",
            "p.last_name",
            "p.suffix",
            "p.contact_number",
            "p.email",
            "p.img_url",
            'wr.name as work_role_name',
        )
        ->join('inspection_inspector_team_leaders as iitl','p.id','iitl.person_id')
        ->join('person_types as pt','p.person_type_id','pt.id')
        ->join('work_roles as wr', 'wr.id','p.work_role_id')
        ->where('pt.name','Inspector')
        ->where('iitl.inspection_id','=',$this->issue_inspection['id'])
        ->get()
        ->toArray();
        if(count($temp)>=1){
           
            $var = DB::table('inspection_inspector_team_leaders as iitl')
                ->join('persons as p','p.id','iitl.person_id')
                ->where('iitl.inspection_id','=',$this->issue_inspection['id'])
                ->where('iitl.id','=',$id)
                ->first();
                
            $edit = DB::table('inspections as i')
                ->select(
                    'b.id',
                    'b.img_url',
                    'b.name',
                    'b.occupancy_classification_id',
                    'b.business_type_id',
                    'b.street_address',
                    'b.contact_number',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'bt.name as business_type_name',
                )
                ->join('businesses as b','b.id','i.business_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->where('i.id','=',$this->issue_inspection['id'])
                ->first();
            DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has deleted a team leader ( '.$var->first_name.' '.$var->middle_name.' '.$var->last_name.' '.$var->suffix.' '.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
            ]);
            DB::table('inspection_inspector_team_leaders')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->delete();

            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
        }else{
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'You cannot delete!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
    }
    public function update_delete_members($id){
        $var = DB::table('inspection_inspector_members as iim')
                ->join('persons as p','p.id','iim.person_id')
                ->where('iim.inspection_id','=',$this->issue_inspection['id'])
                ->where('iim.id','=',$id)
                ->first();
            
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deleted a member ( '.$var->first_name.' '.$var->middle_name.' '.$var->last_name.' '.$var->suffix.' '.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
        ]);
        DB::table('inspection_inspector_members')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->delete();
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
       
    }
    public function update_delete_violation($id){
        
        $var = DB::table('inspection_violations as iv')
            ->join('violations as v','v.id','iv.violation_id')
            ->where('iv.id','=',$id)
            ->where('iv.inspection_id','=',$this->issue_inspection['id'])
            ->first();
            
        $edit = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'bt.name as business_type_name',
            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->where('i.id','=',$this->issue_inspection['id'])
            ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deleted a violation ( '.$var->description.' )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
        ]);
        DB::table('inspection_violations')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->delete();
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
       
    }
    public function edit($id,$modal_id){
        if($inspection = DB::table('inspections as i')
        ->select(
            'i.id',
            'b.img_url',
            'b.name as business_name',
            'p.first_name',
            'p.middle_name',
            'p.last_name',
            'p.suffix',
            'brg.brgyDesc as barangay',
            'bt.name as business_type_name',
            'oc.character_of_occupancy as occupancy_classification_name',
            'b.contact_number',
            'b.email',
            'b.floor_area',
            'b.signage_area',
            'b.is_active',
            'st.name as status_name',
            'i.schedule_date',
            "i.status_id",
            "i.business_id",
            "i.signage_id",
            "i.building_billing_id",
            "i.application_type_id",
            "i.remarks",
            "i.date_signed",
        )
        ->join('inspection_status as st','st.id','i.status_id')
        ->join('businesses as b','b.id','i.business_id')
        ->join('persons as p','p.id','b.owner_id')
        ->join('brgy as brg','brg.id','b.brgy_id')
        ->join('business_types as bt','bt.id','b.business_type_id')
        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
        ->where('i.id','=',$id)
        ->first()){

        }
        $this->inspection = [
            'id'=>$inspection->id,
            'inspector_leaders' =>[],
            'inspector_members' => [],
            'business_id' =>NULL,
            'schedule_date'=>NULL,
            'step'=>NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_deactivate($id,$modal_id){
        $status = DB::table('inspection_status')
            ->where('name','=','Deleted')
            ->first();
        if(DB::table('inspections as i')
        ->where('i.id','=',$id)
        ->update([
            'status_id'=>$status->id
        ])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully deleted!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $edit = DB::table('inspections as i')
                ->select(
                    'b.id',
                    'b.img_url',
                    'b.name',
                    'b.occupancy_classification_id',
                    'b.business_type_id',
                    'b.street_address',
                    'b.contact_number',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'bt.name as business_type_name',
                )
                ->join('businesses as b','b.id','i.business_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->where('i.id','=',$id)
                ->first();
            DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has deleted an inspection for '.$edit->name.' (' .$edit->business_type_name. ') ',
            ]);
            $this->dispatch('openModal',$modal_id);
            return 0;
        }
    }
    public function save_complete($id,$modal_id){
        $status = DB::table('inspection_status')
            ->where('name','=','Completed')
            ->first();
        if(DB::table('inspections as i')
        ->where('i.id','=',$id)
        ->update([
            'status_id'=>$status->id
        ])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully completed!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $edit = DB::table('inspections as i')
                ->select(
                    'b.id',
                    'b.img_url',
                    'b.name',
                    'b.occupancy_classification_id',
                    'b.business_type_id',
                    'b.street_address',
                    'b.contact_number',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'bt.name as business_type_name',
                )
                ->join('businesses as b','b.id','i.business_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->where('i.id','=',$id)
                ->first();
            DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has completed an inspection for '.$edit->name.' (' .$edit->business_type_name. ') ',
            ]);
            $this->dispatch('openModal',$modal_id);
            return 0;
        }
    }
    public function update_status($id,$status){
        $status = DB::table('inspection_status')
            ->where('name','=',$status)
            ->first();
        DB::table('inspections')
            ->where('id','=',$id)
            ->update([
                'status_id'=> $status->id
        ]);
    }
  
}
