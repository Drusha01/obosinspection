<?php

namespace App\Livewire\Admin\Administrator\Request\Requests;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Mail;

class Requests extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Notifications";

    public $brgy = [];
    public $business = [];
    public $business_categories = [];
    public $business_category_list  = [];
    public $request_lists = [];
    public $status = [];
    public $inspector_leaders = [];
    public $inspector_members = [];

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'business_name','active'=> true,'name'=>'Business Name'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Barangay'],
        ['column_name'=> 'status_name','active'=> true,'name'=>'Status'],
        ['column_name'=> 'request_date','active'=> true,'name'=>'Notification Range'],
        ['column_name'=> 'schedule_date','active'=> true,'name'=>'Schedule Date'],
        ['column_name'=> 'accepted_date','active'=> true,'name'=>'Response Date'],
        ['column_name'=> 'reason','active'=> true,'name'=>'Remarks'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];

    public $business_category = [
        'id' => NULL,
        'name' => NULL,
        'is_active'=> NULL,
    ];
    
    public $activity_logs = [
        'created_by' => NULL,
        'inspector_team_id' => NULL,
        'log_details' => NULL,
    ];
    public $search = [
        'search'=> NULL,
        'search_prev'=> NULL,
        'status_id'=>NULL,
        'brgy_id'=> NULL,
        'business_category_id'=> NULL,
    ];

    public $modal = [
        'search'=>NULL,
        'search_prev'=> NULL,
        'brgy_id'=> NULL,
        'prev_brgy_id'=> NULL,
        'business_category_id'=>NULL,
    ];

    public $request  = [
        'id' =>NULL,
        'business_id' =>NULL,
        'status_id' =>NULL,
        'request_date' =>NULL,
        'expiration_date' =>NULL,
        'accepted_date' =>NULL,
        'request_type' => NULL,
        'schedule_date' => NULL,
        'is_responded' =>NULL,
        'business' =>NULL,
        'reason' =>NULL,
    ];

    public $inspection = [
        'id'=>NULL,
        'request_inspection_id'=>NULL,
        'inspector_leaders' =>[],
        'inspector_leader_id'=>NULL,
        'inspector_members' => [],
        'inspector_member_id'=>NULL,
        'business_id' =>NULL,
        'schedule_date'=>NULL,
        'step'=> 1,
        'last_inspection'=> NULL,

    ];
    public $businesses = [];


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
            ->join('persons as p','p.id','u.person_id')
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
    public $table_filter;
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

    public function mount(Request $request){

        $city_mun = DB::table('citymun')
        ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
        ->first();
        $this->brgy = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->orderBy('brgyDesc','asc')
            ->get()
            ->toArray();

        $request_status = DB::table('request_status as rs')
            ->get()
            ->toArray();
        $temp_status = [];
        foreach ($request_status as $key => $value) {
            if($value->name == 'Pending'){
                // $this->search['status_id'] = $value->id;
                array_push($temp_status,[
                    'name'=>'No Response',
                    'id'=>-1
                ]);
            }
            array_push($temp_status,[
                'name'=>$value->name,
                'id'=>$value->id
            ]);
        }
        
        $this->status = $temp_status;
        $this->business_categories = DB::table('business_category')
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
    }

    public function render()
    {
        if($this->search['search'] != $this->search['search_prev']){
            $this->search['search_prev'] = $this->search['search'];
            $this->resetPage();
        }
        
            
        if(intval($this->modal['brgy_id'])){
            if(intval($this->modal['business_category_id'])){
                $this->business = DB::table('businesses as b')
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
                    ->join('brgy as brg','brg.id','b.brgy_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->where('b.brgy_id','=',$this->modal['brgy_id'] )
                    ->where('b.name','like',$this->modal['search'] .'%')
                    ->where('b.business_category_id','=',$this->modal['business_category_id'])
                    ->limit(15)
                    ->get()
                    ->toArray();
            }else{
                $this->business = DB::table('businesses as b')
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
                    ->join('brgy as brg','brg.id','b.brgy_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->where('b.brgy_id','=',$this->modal['brgy_id'] )
                    ->where('b.name','like',$this->modal['search'] .'%')
                    ->limit(15)
                    ->get()
                    ->toArray();
            }
        }else{
            if(intval($this->modal['business_category_id'])){
                $this->business = DB::table('businesses as b')
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
                    ->join('brgy as brg','brg.id','b.brgy_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->where('b.name','like',$this->modal['search'] .'%')
                    ->where('b.business_category_id','=',$this->modal['business_category_id'])
                    ->limit(15)
                    ->get()
                    ->toArray();
            }else{
                $this->business = DB::table('request_inspections as ri')
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
                        'rs.name as request_status',
                    )
                    ->rightjoin('businesses as b','ri.business_id','b.id')
                    ->where('b.is_active','=',1)
                    ->join('persons as p','p.id','b.owner_id')
                    ->join('brgy as brg','brg.id','b.brgy_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->join('request_status as rs','rs.id','ri.status_id')
                    ->where('b.name','like',$this->modal['search'] .'%')
                    ->groupBy('b.id')
                    ->where('rs.name','=','Pending')
                    ->limit(15)
                    ->get()
                    ->toArray();
            }
        }
        if(!intval($this->search['status_id'] )){
            if(intval($this->search['brgy_id']) ){
                if($this->search['business_category_id']){
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('b.brgy_id','=',$this->search['brgy_id'] )
                        ->where('b.business_category_id','=',$this->search['business_category_id'])
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '>=', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }else{
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('b.brgy_id','=',$this->search['brgy_id'] )
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '>=', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }
            }else{
                if($this->search['business_category_id']){
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('b.business_category_id','=',$this->search['business_category_id'])
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '>=', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }else{
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '>=', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }
            }
        }elseif($this->search['status_id'] == -1){
            if(intval($this->search['brgy_id']) ){
                if($this->search['business_category_id']){
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            DB::raw('CONCAT("No response") as status_name'),
                            'ri.request_date',
                            'ri.expiration_date',
                            'ri.accepted_date',
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('rs.name','=','Pending')
                        ->where('b.brgy_id','=',$this->search['brgy_id'] )
                        ->where('b.business_category_id','=',$this->search['business_category_id'])
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '<', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }else{
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            DB::raw('CONCAT("No response") as status_name'),
                            'ri.request_date',
                            'ri.expiration_date',
                            'ri.accepted_date',
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('rs.name','=','Pending')
                        ->where('b.brgy_id','=',$this->search['brgy_id'] )
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '<', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }
            }else{
                if($this->search['business_category_id']){
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            DB::raw('CONCAT("No response") as status_name'),
                            'ri.request_date',
                            'ri.expiration_date',
                            'ri.accepted_date',
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('rs.name','=','Pending')
                        ->where('b.business_category_id','=',$this->search['business_category_id'])
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '<', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }else{
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            DB::raw('CONCAT("No response") as status_name'),
                            'ri.request_date',
                            'ri.expiration_date',
                            'ri.accepted_date',
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('rs.name','=','Pending')
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '<', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);

                }
            }
        }elseif($this->search['status_id']){
            if(intval($this->search['brgy_id']) ){
                if($this->search['business_category_id']){
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('rs.id','=',$this->search['status_id'])
                        ->where('b.brgy_id','=',$this->search['brgy_id'] )
                        ->where('b.business_category_id','=',$this->search['business_category_id'])
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '>=', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }else{
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('rs.id','=',$this->search['status_id'])
                        ->where('b.brgy_id','=',$this->search['brgy_id'] )
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '>=', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }
            }else{
                if($this->search['business_category_id']){
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('rs.id','=',$this->search['status_id'])
                        ->where('b.business_category_id','=',$this->search['business_category_id'])
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '>=', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }else{
                    $table_data = DB::table('request_inspections as ri')
                        ->select(
                            'ri.id',
                            'b.img_url',
                            'b.id as business_id',
                            'b.name as business_name',
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
                            'ri.hash',
                            'ri.reason',
                            'brg.brgyDesc as barangay',
                            'ri.schedule_date',
                            'ri.request_type',
                        )
                        ->join('request_status as rs','rs.id','ri.status_id')
                        ->join('businesses as b','b.id','ri.business_id')
                        ->join('persons as p','p.id','b.owner_id')
                        ->join('brgy as brg','brg.id','b.brgy_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->where('rs.id','=',$this->search['status_id'])
                        ->where('b.name','like',$this->search['search'] .'%')
                        ->where('ri.expiration_date', '>=', date('Y-m-d'))
                        ->orderBy('ri.id','desc')
                        ->paginate($this->table_filter['table_rows']);
                }
            }
        }
        return view('livewire.admin.administrator.request.requests.requests',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }

    public function update_business_id(){
        $this->request['business_id'] = NULL;
    }

    
    public function request_list_modal($modal_id){

        $this->business_category_list = DB::table('business_category')
            ->get()
            ->toArray();
        $this->request_lists = DB::table('request_business_categories as rbc')
            ->join('business_category as bc','rbc.business_category_id','bc.id')
            ->get()
            ->toArray();
        $this->dispatch('openModal',$modal_id);
    }
    public function add_category_to_request_list(){
        $temp = DB::table('request_business_categories')
        ->where('business_category_id','=',$this->business_category['id'])
        ->first();
        if(
           $temp 
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Business category has been already added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
        }else{
            DB::table('request_business_categories')
                ->insert([
                    'business_category_id'=>$this->business_category['id']
            ]);
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
        }
        $this->request_lists = DB::table('request_business_categories as rbc')
            ->join('business_category as bc','rbc.business_category_id','bc.id')
            ->get()
            ->toArray();
        $this->business_category['id'] = NULL;
    }
    public function delete_request_category($id){
        if( DB::table('request_business_categories')
            ->where('id','=',$id)
            ->delete()){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully deleted!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $this->request_lists = DB::table('request_business_categories as rbc')
            ->join('business_category as bc','rbc.business_category_id','bc.id')
            ->get()
            ->toArray();
        }
    }


    public function generate_request($modal_id){
        $this->business_categories = DB::table('business_category')
        ->get()
        ->toArray();
        $this->business = DB::table('request_business_categories as rbc')
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
            ->get()
            ->toArray();
        $this->request  = [
            'id' =>NULL,
            'business_id' =>NULL,
            'status_id' =>NULL,
            'request_date' =>date_format(date_create(now()),"Y-m-d"),
            'expiration_date' =>NULL,
            'accepted_date' =>NULL,
            'request_type' => 1,
            'schedule_date' =>date_format(date_create(now()),"Y-m-d"),
            'is_responded' =>NULL,
            'reason' =>NULL,
            'business' =>NULL,
            'duration'=>7,
          
        ];
        $this->request ['expiration_date'] = date_format(date_add(date_create($this->request ['request_date']),date_interval_create_from_date_string($this->request ['duration']." days")),"Y-m-d");
        $this->dispatch('openModal',$modal_id);
    }

    public function send_request($modal_id){
       
        $today = date_format(date_create(now()),"Y-m-d");
        if( !isset($this->request['id']) ){
            $status = DB::table('request_status')
            ->where('name','=',"Pending")
            ->first();
            $pre_request = DB::table('request_inspections as ri')
                ->select(
                    'ri.id'
                )
                ->where('ri.status_id','=',$status->id)
                ->where('ri.business_id','=', $this->request['business_id'])
                ->whereDate('ri.request_date','<=', $today)
                ->whereDate('ri.expiration_date','>=', $today)
                ->first();
            if($pre_request){
                $this->request['id'] = $pre_request->id;
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Request Record Exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1500',
                    link              									: '#',
                );
                return;
            }
        }
        if( isset($this->request['id']) ){
            $status = DB::table('request_status')
            ->where('name','=',"Pending")
            ->first();
            $pre_request = DB::table('request_inspections as ri')
                ->select(
                    'ri.id'
                )
                ->where('ri.status_id','=',$status->id)
                ->where('ri.business_id','=', $this->request['business_id'])
                ->whereDate('ri.request_date','<=', $today)
                ->whereDate('ri.expiration_date','>=', $today)
                ->first();
            if($pre_request){
                $this->request['id'] = $pre_request->id;
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Request Record Exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1500',
                    link              									: '#',
                );
                return;
            }
        }
        if( !isset($this->request['id']) ){
            $status = DB::table('request_status')
            ->where('name','=',"Accepted")
            ->first();
            $pre_request = DB::table('request_inspections as ri')
                ->select(
                    'ri.id'
                )
                ->where('ri.status_id','=',$status->id)
                ->where('ri.business_id','=', $this->request['business_id'])
                ->whereDate('ri.request_date','<=', $today)
                ->whereDate('ri.expiration_date','>=', $today)
                ->first();
            if($pre_request){
                $this->request['id'] = -1;
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Request record exist and accepted!',
                    showConfirmButton 									: 'true',
                    timer             									: '1500',
                    link              									: '#',
                );
                return;
            }
       
        }
        $business = DB::table('businesses as b')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bc.name as business_category_name',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'b.is_active'

            )
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_category as bc','bc.id','b.business_category_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('b.id','=',$this->request['business_id'])
            ->first();
        $this->email = $business->email;
        $this->establishment = $business->name;
        $this->owner = $business->last_name.' ,'.$business->first_name.' '.$business->middle_name;
        $this->start_date = date_format(date_create($this->request['request_date']),"M d, Y");
        $this->end_date =  date_format(date_create($this->request['expiration_date']),"M d, Y");
        $this->response_duration = date_format(date_create($this->request['request_date']),"M d, Y").' to '. date_format(date_create($this->request['expiration_date']),"M d, Y");
        $this->schedule_date =  date_format(date_create($this->request['schedule_date']),"M d, Y");
        
        $this->hash = md5($business->name.now());
        while(DB::table('request_inspections')
            ->where('hash','=',$this->hash)
            ->first()){
            $this->hash = md5($business->name.now());
        }
        $this->port = $_SERVER['SERVER_PORT'];
        $this->host_name = $_SERVER['SERVER_NAME'];
        $this->subject = 'OBOS Inspection would like to request to inspect your establishment from '.date_format(date_create($this->schedule_date),"M d, Y ").', to accept please click the accept button, to 
            decline please click the decline button and provide a reason after the redirection. Thank you.'; 
        
        $this->content = 'Sir/Madam:
        <br>
        <br>
        Pursuant to PD 1096, otherwise known as the National Building Code of the Philippines and its IRR, the Building Official shall undertake annual inspection of all buildings/structures and keep an updated record of their status. Also in the performance of his duties, a Building Official may enter any building or its premises at all reasonable times to inspect and determine compliance with the requirements of the NBPC.
        <br>
        <br>
        You are hereby inform that the OBO Inspectorate team will conduct an Annual Inspection of your establishment on  <strong>'.date_format(date_create($this->schedule_date),"M d, Y ").' </strong> , to ensure safety of your building and update the fees and status of your equipment.
        <br>
        <br>
        Please prepare the approve plans (Structural, Electrical, Mechanical, Plumbing & Electronics), Occupancy Permit, Update site Development Plan, and a consolidated list of equipment during the scheduled inspection
        <br>
        <br>
        A certificate of Annual Inspection will be issued to you after we found your building to be safe for use and/or after the compliance of deficiencies and payment of necessary fees have been made.';
        
        $code =1;

        if($this->request['request_type'] && Mail::send('mail.requestToInspect', [
                'code'=>$code,
                'email'=>$this->email,
                'establishment'=>$this->establishment,
                'owner'=>$this->owner,
                'content'=>$this->content,
                'hash'=>$this->hash,
                'port'=>$this->port,
                'host_name'=>$this->host_name,
                'schedule_date'=>$this->schedule_date,
                'response_duration'=>$this->response_duration
                ], 
                    function($message) {
                $message->to($this->email, $this->email)->subject
                ($this->subject);
                $message->from('obosinspection@gmail.com','OBOS INSPECTION');
            })){

            if(isset($this->request['id'])){
                $status = DB::table('request_status')
                    ->where('name','=',"Pending")
                    ->first();
                DB::table('request_inspections')
                ->where('id','=',$this->request['id'])
                ->update([
                    'business_id' =>$this->request['business_id'],
                    'status_id' => $status->id,
                    'schedule_date' =>$this->request['schedule_date'],
                    'request_type' =>  1,
                    'is_responded' =>0,
                    'request_date' =>$this->request['request_date'],
                    'expiration_date' =>$this->request['expiration_date'],
                    'hash' => $this->hash,
                ]);
            }else{
                $status = DB::table('request_status')
                    ->where('name','=',"Pending")
                    ->first();
                DB::table('request_inspections')
                ->insert([
                    'business_id' =>$this->request['business_id'],
                    'status_id' => $status->id,
                    'schedule_date' =>$this->request['schedule_date'],
                    'request_type' =>1,
                    'request_date' =>$this->request['request_date'],
                    'expiration_date' =>$this->request['expiration_date'],
                    'hash' => $this->hash,
                ]);
            }
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Email sent!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $this->dispatch('openModal',$modal_id);
        }else{
            if(isset($this->request['id'])){
                $status = DB::table('request_status')
                    ->where('name','=',"Pending")
                    ->first();
                DB::table('request_inspections')
                ->where('id','=',$this->request['id'])
                ->update([
                    'business_id' =>$this->request['business_id'],
                    'status_id' => $status->id,
                    'schedule_date' =>$this->request['schedule_date'],
                    'request_type' =>0,
                    'is_responded' =>0,
                    'request_date' =>$this->request['request_date'],
                    'expiration_date' =>$this->request['expiration_date'],
                    'hash' => $this->hash,
                ]);
            }else{
                $status = DB::table('request_status')
                    ->where('name','=',"Pending")
                    ->first();
                DB::table('request_inspections')
                ->insert([
                    'business_id' =>$this->request['business_id'],
                    'status_id' => $status->id,
                    'schedule_date' =>$this->request['schedule_date'],
                    'request_type' =>0,
                    'request_date' =>$this->request['request_date'],
                    'expiration_date' =>$this->request['expiration_date'],
                    'hash' => $this->hash,
                ]);
            }
            $this->dispatch('swal:new_page',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully generated!',
                showConfirmButton 									: 'true',
                timer             									: '1500',
                link              									: ($_SERVER['REMOTE_PORT'] == 80? 'https://': 'http://' ).$_SERVER['SERVER_NAME'].'/administrator/request/generate-request-pdf/'.$this->hash.'/'.$this->request['request_date'].'/'.$this->request['expiration_date'],
            );
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function edit($id,$modal_id){
        $request = DB::table('request_inspections as ri')
            ->select(
                'ri.id' ,
                'ri.business_id',
                'ri.status_id' ,
                'ri.request_date' ,
                'ri.expiration_date' ,
                'ri.accepted_date' ,
                'ri.is_responded' ,
                'ri.reason' ,
                'ri.hash' ,
                'ri.schedule_date',
                'rs.name as status_name',
                'ri.request_type',
            )
            ->join('request_status as rs','ri.status_id','rs.id')
            ->where('ri.id','=',$id)
            ->first();
        if($request){
            $this->request  = [
                'id' =>$request->id,
                'business_id' =>$request->business_id,
                'status_id' =>$request->status_id,
                'request_date' =>$request->request_date,
                'expiration_date' =>$request->expiration_date,
                'accepted_date' =>$request->accepted_date,
                'schedule_date' =>$request->schedule_date,
                'request_type' =>$request->request_type,
                'is_responded' =>$request->is_responded,
                'reason' =>$request->reason,
            ];
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_delete($id,$modal_id){
        $status = DB::table('request_status')
        ->where('name','=',"Deleted")
        ->first();
        if(DB::table('request_inspections as ri')
            ->join('request_status as rs','ri.status_id','rs.id')
            ->where('ri.id','=',$id)
            ->update([
                'status_id'=>$status->id,
            ])
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully deleted!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#',
            );
            $this->dispatch('openModal',$modal_id);
            return;
        }
    }
    public function save_decline($id,$modal_id){
        $status = DB::table('request_status')
        ->where('name','=',"Declined")
        ->first();
        if(DB::table('request_inspections as ri')
            ->join('request_status as rs','ri.status_id','rs.id')
            ->where('ri.id','=',$id)
            ->update([
                'status_id'=>$status->id,
            ])
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully deleted!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#',
            );
            $this->dispatch('openModal',$modal_id);
            return;
        }
    }
    

    public function add($id,$modal_id){
        $this->business_categories = DB::table('business_category')
        ->get()
        ->toArray();
        $business = DB::table('request_inspections as ri')
            ->select(
                'ri.id',
                'b.id as business_id',
                'b.img_url',
                'b.name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bc.name as business_category_name',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'b.is_active',
                'ri.schedule_date',
            )
            ->join('businesses as b','b.id','ri.business_id')
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_category as bc','bc.id','b.business_category_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('ri.id','=',$id)
            ->first();
        $last_inspection = DB::table('inspections as i')
            ->join('inspection_status as is','is.id','i.status_id')
            ->where('business_id','=',$business->business_id)
            ->where('is.name','=','Completed')
            ->orderBy('i.id','desc')
            ->first();
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
                'iit.name as inspector_team',
            )
            ->leftjoin('inspector_members as im','p.id','im.member_id')
            ->leftjoin('inspector_teams as iit','iit.id','im.inspector_team_id')
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->join('users as u','u.person_id','p.id')
            ->where('u.is_active',1)
            ->whereNull('it.team_leader_id')
            ->where('pt.name','Inspector')
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
            ->join('users as u','u.person_id','p.id')
            ->where('u.is_active',1)
            ->whereNotNull('it.team_leader_id')
            ->where('pt.name','Inspector')
            ->get()
            ->toArray();
        $this->inspection = [
            'id'=>NULL,
            'request_inspection_id'=>$business->id,
            'business'=>$business,
            'inspector_leaders' =>[],
            'inspector_leader_id'=>NULL,
            'inspector_members' => [],
            'inspector_member_id'=>NULL,
            'last_inspection'=>$last_inspection,
            'business_id' =>$business->business_id,
            'schedule_date'=>date_format(date_create($business->schedule_date),"Y-m-d"),
            'step'=> 1,
            'last_inspection'=> NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function next($modal_id){
        if($this->inspection['step'] == 1){
            if(intval($this->inspection['business_id'])){
                $this->inspection['step']+=1;
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
            if((count($this->inspection['inspector_leaders']) + count($this->inspection['inspector_members']) ) < 2){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please add at least 2 inspectors!',
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
            if( DB::table('inspections')
                ->insert([
                    'status_id' => $status->id , 
                    'business_id' =>$this->inspection['business_id'], 
                    'schedule_date' =>$this->inspection['schedule_date'],
            ])){
                
            
                $status = DB::table('request_status')
                ->where('name','=',"Scheduled")
                ->first();
                DB::table('request_inspections as ri')
                    ->join('request_status as rs','ri.status_id','rs.id')
                    ->where('ri.id','=',$this->inspection['request_inspection_id'])
                    ->update([
                        'status_id'=>$status->id,
                        'accepted_date'=>date_format(date_create(now()),"Y-m-d"),
                ]);
                
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
    public function delete_team_leader($index){
        $temp = [];
        foreach ($this->inspection['inspector_leaders'] as $key => $value) {
            if($key != $index){
               array_push($temp,$value);
            }
        }
        $this->inspection['inspector_leaders'] = $temp;
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
    public function delete_team_member($index){
        $temp = [];
        foreach ($this->inspection['inspector_members'] as $key => $value) {
            if($key != $index){
               array_push($temp,$value);
            }
        }
        $this->inspection['inspector_members'] = $temp;
    }
    public function reissue_request($id,$modal_id){
        $this->business_categories = DB::table('business_category')
        ->get()
        ->toArray();
        $business = DB::table('request_inspections as ri')
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
            ->join('businesses as b','b.id','ri.business_id')
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('ri.id','=',$id)
            ->first();
        $this->request  = [
            'id' =>$id,
            'business_id' =>$business->id,
            'status_id' =>NULL,
            'request_date' =>date_format(date_create(now()),"Y-m-d"),
            'expiration_date' =>NULL,
            'accepted_date' =>NULL,
            'request_type' => 1,
            'schedule_date' =>date_format(date_create(now()),"Y-m-d"),
            'is_responded' =>NULL,
            'reason' =>NULL,
            'business'=> $business,
            'duration'=>7,
          
        ];
        $this->request ['expiration_date'] = date_format(date_add(date_create($this->request ['request_date']),date_interval_create_from_date_string($this->request ['duration']." days")),"Y-m-d");
        $this->dispatch('openModal',$modal_id);
    }
}
