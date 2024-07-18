<?php

namespace App\Livewire\Admin\Administrator\Inspections\UpcomingInspections;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;


class UpcomingInspections extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Upcoming Inspections";

    public $max_date = (11 * 30) + 5;
    public $inspector_leaders;
    public $inspector_members;
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
    public $businesses = [];

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
        ['column_name'=> 'first_name','active'=> true,'name'=>'Owner'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
        ['column_name'=> 'business_category_name','active'=> true,'name'=>'Business Category'],
        ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
        ['column_name'=> 'occupancy_classification_name','active'=> true,'name'=>'Char of Occu'],
        ['column_name'=> 'contact_number','active'=> true,'name'=>'Contact #'],
        ['column_name'=> 'email','active'=> true,'name'=>'Email'],

        ['column_name'=> 'last_inspected_date','active'=> true,'name'=>'Last Inspected'],
        ['column_name'=> 'last_inspected_date_count','active'=> true,'name'=>'Days delayed'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];

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
    public $search = [
        'search'=> NULL,
        'search_prev'=> NULL,
        'type' => NULL,
        'type_prev' => NULL,
        'brgy_id'=>NULL,
        'business_category_id'=>NULL,
    ];
    public $search_by = [
        ['name'=>'Name','column_name'=>'b.name'],
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
    public function mount(Request $request){
        $session = $request->session()->all();
        $this->business_categories = DB::table('business_category')
        ->get()
        ->toArray();
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
        $this->brgy = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->orderBy('brgyDesc','asc')
            ->get()
            ->toArray();
    }

    public function render()
    {
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
        $status_id = DB::table('inspection_status as ii')
            ->where('name','=','Completed')
            ->first()->id;
        $businesses = DB::table('businesses as b')
            ->select(
                DB::raw('DISTINCT(b.id)'),
                'b.name',
                DB::raw('max(i.schedule_date)')
            )
            ->join('inspections as i','b.id','i.business_id')
            ->groupby('b.id')
            ->where('i.status_id','=',$status_id)
            ->orderBy('i.schedule_date','asc')
            ->get()
            ->toArray();
            if($this->search['brgy_id']){
                if($this->search['business_category_id']){
                    $table_data = DB::table('businesses as b')
                    ->select(
                        'i.id',
                        'b.id as business_id',
                        'b.img_url',
                        'b.name',
                        'bc.name as business_category_name',
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
                        DB::raw('max(i.schedule_date) as schedule_date'),
                        DB::raw('DATEDIFF(NOW(),max(i.schedule_date)) as date_count'),
                        DB::raw('DATEDIFF(NOW(),max(i.schedule_date))-365 as date_count_minus_year'),
                    )
                    ->join('inspections as i','b.id','i.business_id')
                    ->join('inspection_status as st','st.id','i.status_id')
                    ->leftjoin('persons as p','p.id','b.owner_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('business_category as bc','bc.id','b.business_category_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->leftjoin('brgy as brg','brg.id','b.brgy_id')
                    ->groupby('b.id')
                    ->where('i.status_id','=',$status_id)
                    ->where('b.business_category_id','=',$this->search['brgy_id'])
                    ->where('b.business_category_id','=',$this->search['business_category_id'])
                    ->where($this->search['type'],'like',$this->search['search'] .'%')
                    ->having( DB::raw('DATEDIFF(NOW(),max(i.schedule_date))'),'>',$this->max_date)
                    ->orderBy( DB::raw('max(i.schedule_date)'),'asc')
                    ->paginate($this->table_filter['table_rows']);
                }else{
                    $table_data = DB::table('businesses as b')
                    ->select(
                        'i.id',
                        'b.id as business_id',
                        'b.img_url',
                        'b.name',
                        'bc.name as business_category_name',
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
                        DB::raw('max(i.schedule_date) as schedule_date'),
                        DB::raw('DATEDIFF(NOW(),max(i.schedule_date)) as date_count'),
                        DB::raw('DATEDIFF(NOW(),max(i.schedule_date))-365 as date_count_minus_year'),
                    )
                    ->join('inspections as i','b.id','i.business_id')
                    ->join('inspection_status as st','st.id','i.status_id')
                    ->leftjoin('persons as p','p.id','b.owner_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('business_category as bc','bc.id','b.business_category_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->leftjoin('brgy as brg','brg.id','b.brgy_id')
                    ->groupby('b.id')
                    ->where('i.status_id','=',$status_id)
                    ->where('b.business_category_id','=',$this->search['brgy_id'])
                    ->where($this->search['type'],'like',$this->search['search'] .'%')
                    ->having( DB::raw('DATEDIFF(NOW(),max(i.schedule_date))'),'>',$this->max_date)
                    ->orderBy( DB::raw('max(i.schedule_date)'),'asc')
                    ->paginate($this->table_filter['table_rows']);
                }
            }else{
                if($this->search['business_category_id']){
                    $table_data = DB::table('businesses as b')
                        ->select(
                            'i.id',
                            'b.id as business_id',
                            'b.img_url',
                            'b.name',
                            'bc.name as business_category_name',
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
                            DB::raw('max(i.schedule_date) as schedule_date'),
                            DB::raw('DATEDIFF(NOW(),max(i.schedule_date)) as date_count'),
                            DB::raw('DATEDIFF(NOW(),max(i.schedule_date))-365 as date_count_minus_year'),
                        )
                        ->join('inspections as i','b.id','i.business_id')
                        ->join('inspection_status as st','st.id','i.status_id')
                        ->leftjoin('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('business_category as bc','bc.id','b.business_category_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->leftjoin('brgy as brg','brg.id','b.brgy_id')
                        ->groupby('b.id')
                        ->where('i.status_id','=',$status_id)
                        ->where('b.business_category_id','=',$this->search['business_category_id'])
                        ->where($this->search['type'],'like',$this->search['search'] .'%')
                        ->having( DB::raw('DATEDIFF(NOW(),max(i.schedule_date))'),'>',$this->max_date)
                        ->orderBy( DB::raw('max(i.schedule_date)'),'asc')
                        ->paginate($this->table_filter['table_rows']);
                }else{
                    $table_data = DB::table('businesses as b')
                        ->select(
                            'i.id',
                            'b.id as business_id',
                            'b.img_url',
                            'b.name',
                            'bc.name as business_category_name',
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
                            DB::raw('max(i.schedule_date) as schedule_date'),
                            DB::raw('DATEDIFF(NOW(),max(i.schedule_date)) as date_count'),
                            DB::raw('DATEDIFF(NOW(),max(i.schedule_date))-365 as date_count_minus_year'),
                        )
                        ->join('inspections as i','b.id','i.business_id')
                        ->join('inspection_status as st','st.id','i.status_id')
                        ->leftjoin('persons as p','p.id','b.owner_id')
                        ->join('business_types as bt','bt.id','b.business_type_id')
                        ->join('business_category as bc','bc.id','b.business_category_id')
                        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                        ->leftjoin('brgy as brg','brg.id','b.brgy_id')
                        ->groupby('b.id')
                        ->where('i.status_id','=',$status_id)
                        ->where($this->search['type'],'like',$this->search['search'] .'%')
                        ->having( DB::raw('DATEDIFF(NOW(),max(i.schedule_date))'),'>',$this->max_date)
                        ->orderBy( DB::raw('max(i.schedule_date)'),'asc')
                        ->paginate($this->table_filter['table_rows']);
                    }
                }
        return view('livewire.admin.administrator.inspections.upcoming-inspections.upcoming-inspections',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id,$business_id){
        $this->business_categories = DB::table('business_category')
        ->get()
        ->toArray();
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
        $this->businesses =  DB::table('businesses as b')
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
        ->where('b.id','=',$business_id)
        ->get()
        ->toArray();
        $this->inspection = [
            'id'=>NULL,
            'inspector_leaders' =>[],
            'inspector_leader_id'=>NULL,
            'inspector_members' => [],
            'inspector_member_id'=>NULL,
            'business_id' =>$business_id,
            'schedule_date'=>date_format(date_create(now()),"Y-m-d"),
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
    public function delete_team_leader($index){
        $temp = [];
        foreach ($this->inspection['inspector_leaders'] as $key => $value) {
            if($key != $index){
               array_push($temp,$value);
            }
        }
        $this->inspection['inspector_leaders'] = $temp;
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
}
