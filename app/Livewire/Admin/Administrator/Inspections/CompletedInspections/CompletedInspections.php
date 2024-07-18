<?php

namespace App\Livewire\Admin\Administrator\Inspections\CompletedInspections;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class CompletedInspections extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Completed inspections";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'id','active'=> true,'name'=>'ID'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
        ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
        ['column_name'=> 'schedule_date','active'=> true,'name'=>'Schedule'],
        ['column_name'=> 'id','active'=> true,'name'=>'Generate'],
        ['column_name'=> 'or_number','active'=> true,'name'=>'Payment OR'],
        ['column_name'=> 'or_number','active'=> true,'name'=>'Claimed'],
        ['column_name'=> 'id','active'=> true,'name'=>'Violation'],
        ['column_name'=> 'id','active'=> true,'name'=>'Inspection Details'],
    ];
    public $annual_certificate_inspection = [
        'id' => NULL,
        'status_id' => NULL,
        'business_id' => NULL,
        'application_type_id' => NULL,
        'bin' => NULL,
        'occupancy_no' => NULL,
        'date_compiled' => NULL,
        'issued_on' => NULL,
        'step'=> 1,
        'business'=> NULL,

        'businesses'=> [],
        'application_types'=> [],

        'inspectors'=>[],
        'annual_certificate_inspection_inspector' => [],
        'inspector_id'=>NULL,

        'annual_certificate_categories'=> [],
        'annual_certificate_category_id'=>NULL,
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
    public function mount(Request $request){
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
        if(intval($this->search['brgy_id']) ){
            $table_data = DB::table('inspections as i')
                ->select(
                    'i.id',
                    'i.or_number',
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
                ->join('inspection_status as st','st.id','i.status_id')
                ->join('businesses as b','b.id','i.business_id')
                ->leftjoin('persons as p','p.id','b.owner_id')
                ->join('brgy as brg','brg.id','b.brgy_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                ->where('st.name','=','Completed')
                ->where('b.brgy_id','=',$this->search['brgy_id'] )
                ->where($this->search['type'],'like',$this->search['search'] .'%')
                ->orderBy('i.id','desc')
                ->paginate($this->table_filter['table_rows']);
        }else{
            $table_data = DB::table('inspections as i')
                ->select(
                    'i.id',
                    'i.or_number',
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
                ->join('inspection_status as st','st.id','i.status_id')
                ->join('businesses as b','b.id','i.business_id')
                ->leftjoin('persons as p','p.id','b.owner_id')
                ->join('brgy as brg','brg.id','b.brgy_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                ->where('st.name','=','Completed')
                ->where($this->search['type'],'like',$this->search['search'] .'%')
                ->orderBy('i.id','desc')
                ->paginate($this->table_filter['table_rows']);
        }
         
        return view('livewire.admin.administrator.inspections.completed-inspections.completed-inspections',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function update_complied_violation($id){
        $inspection_violation = DB::table('inspection_violations')
            ->where('id','=',$id)
            ->first();

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
        if(isset($inspection_violation->remarks)){
            DB::table('inspection_violations')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->update([
                'remarks'=>NULL
            ]);
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has updated a violation ( '.$var->description.', remarks: Not complied )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
            ]);
        }else{
            DB::table('inspection_violations')
                ->where('id','=',$id)
                ->where('inspection_id','=',$this->issue_inspection['id'])
                ->update([
                    'remarks'=>1
                ]);
            DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has updated a violation ( '.$var->description.', remarks: complied )  for '.$edit->name.' (' .$edit->business_type_name. ') ',
            ]);
        }
        
       
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
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
                // ->join('inspector_category as ic','p.id','ic.person_id')
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
                // ->leftjoin('inspector_category as ic','p.id','ic.person_id')
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
                ->get()
                ->toArray();

            $violations = DB::table('violations as v')
                ->select(
                    'v.id',
                    'description',
                    'ivc.name as category_name',
                    'ivc.id as category_id',
                    'v.is_active',
                )
                ->join('violation_category as ivc','v.category_id','ivc.id')
                ->where('v.is_active','=',1)
                ->orderBy(DB::raw('LOWER(ivc.name)'),'asc')
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
        
            $violations = DB::table('violations as v')
                ->select(
                    'v.id',
                    'description',
                    'ivc.name as category_name',
                    'ivc.id as category_id',
                    'v.is_active',
                )
                ->join('violation_category as ivc','v.category_id','ivc.id')
                ->where('v.is_active','=',1)
                ->orderBy(DB::raw('LOWER(ivc.name)'),'asc')
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
                    'section_name'=>$value->section_name
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
                // ->join('inspector_category as ic','p.id','ic.person_id')
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
                // ->leftjoin('inspector_category as ic','p.id','ic.person_id')
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
                    )
                ->join('equipment_billing_sections as ebs','ebs.id','i.category_id')
                ->join('categories as c','c.id','i.category_id')
                ->where('i.is_active','=',1)
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
        $violation_category = DB::table('violation_category')
            ->get()
            ->toArray();
        
        $steps = [
            ['name'=>'Details'],
            ['name'=>'Items'],
            ['name'=>'Building'],
            ['name'=>'Sanitary'],
            ['name'=>'Signage'],
            ['name'=>'Leaders'],
            ['name'=>'Members'],
            ['name'=>'Violation'],
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
            'email_inspection_inspector_team_leaders'=>$email_inspection_inspector_team_leaders,
            'email_inspection_inspector_members'=>$email_inspection_inspector_members,
            'violation_category'=> $violation_category,

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
    public function generate_cert($id,$modal_id){
        // validation.... cannot create if it has violation/s
        $violations = DB::table('inspection_violations as iv')
            ->where('iv.inspection_id','=',$id)
            ->whereNull('remarks')
            ->get()
            ->toArray();
        if(count($violations)){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: "Inspection has violation/s hence you cannot generate certificate \n\n This needs to be approved by administrator!",
                showConfirmButton 									: 'true',
                timer             									: '3000',
                link              									: '#'
            );
            return 0 ;
        }
        $application_types = DB::table('application_types')
            ->get()
            ->toArray();

        $application_type = DB::table('application_types')
            ->where('name','=','Annual')
            ->first();

        $business = DB::table('inspections as i')
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
                'oc.character_of_occupancy_group',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'b.is_active'

            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('i.id','=',$id)
            ->first();

        $inspection_members = DB::table('inspection_inspector_members as iim')
            ->select(
                'p.id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.img_url',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                'it.name as inspector_team'
                )
            ->join('persons as p','p.id','iim.person_id')
            ->leftjoin('inspector_members as im','im.member_id','iim.person_id')
            ->leftjoin('inspector_teams as it','it.id','im.inspector_team_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('iim.inspection_id','=',$id)
            ->get()
            ->toArray();

        $inspection_team_leaders = DB::table('inspection_inspector_team_leaders as iitl')
            ->select(
                'p.id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.img_url',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                'it.name as inspector_team'
                )
            ->join('persons as p','p.id','iitl.person_id')
            ->leftjoin('inspector_members as im','im.member_id','iitl.person_id')
            ->leftjoin('inspector_teams as it','it.team_leader_id','iitl.person_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('iitl.inspection_id','=',$id)
            ->get()
            ->toArray();
        $inspectors = [];
        foreach ($inspection_members as $key => $value) {
            array_push($inspectors,$value);
        }
        foreach ($inspection_team_leaders as $key => $value) {
            array_push($inspectors,$value);
        }
        $annual_certificate_categories = DB::table('annual_certificate_categories as acc')
            ->get()
            ->toArray();

        $this->annual_certificate_inspection = [
            'id' => NULL,
            'business_id' => $business->id,
            'application_type_id' => $application_type->id,
            'bin' => NULL,
            'occupancy_no' => NULL,
            'date_compiled' => NULL,
            'issued_on' => NULL,
            'step'=> 1,
            'business'=> $business,

            'application_types'=> $application_types,

            'inspectors'=>$inspectors,
            'annual_certificate_inspection_inspector' => [],
            'inspector_id'=>NULL,

            'annual_certificate_categories'=> $annual_certificate_categories,
            'annual_certificate_category_id'=>NULL,
        ];
        
        $this->dispatch('openModal',$modal_id);
    }

    public function add_annual_inspector(){
        if(intval($this->annual_certificate_inspection['inspector_id'])){
            $valid = true;
            // foreach ($this->annual_certificate_inspection['annual_certificate_inspection_inspector'] as $key => $value) {
            //     if($value['content']->id == $this->annual_certificate_inspection['inspector_id']){
            //         $valid = false;
            //         break;
            //     }
            // }
            if($valid){
                foreach ($this->annual_certificate_inspection['inspectors'] as $key => $value) {
                    if($value->id == $this->annual_certificate_inspection['inspector_id']){
                        array_push($this->annual_certificate_inspection['annual_certificate_inspection_inspector'],[
                            'content'=>$value,
                            'category_id'=>NULL,
                        ]);
                        break;
                    }
                }
                $this->annual_certificate_inspection['inspector_id'] = NULL;
            }
        }
    }
    public function delete_annual_inspector($id){
        $temp = [];
        foreach ($this->annual_certificate_inspection['annual_certificate_inspection_inspector'] as $key => $value) {
            if($value['content']->id != $id){
                array_push($temp,[
                    'content'=>$value['content'],
                    'category_id'=>$value['category_id'],
                ]);
            }
        }
        $this->annual_certificate_inspection['annual_certificate_inspection_inspector'] = $temp;
    }
    public function update_business_information(){
        if($this->annual_certificate_inspection['step'] == 1){
            if(intval($this->annual_certificate_inspection['business_id'])){
                $this->annual_certificate_inspection['business'] = DB::table('businesses as b')
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
                        'oc.character_of_occupancy_group',
                        'b.contact_number',
                        'b.email',
                        'b.floor_area',
                        'b.signage_area',
                        'b.is_active'

                    )
                    ->join('persons as p','p.id','b.owner_id')
                    ->join('brgy as brg','brg.id','b.brgy_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->where('b.id','=',$this->annual_certificate_inspection['business_id'])
                    ->first();
            }
        }
    
    }

    public function next_annual($modal_id){
        if($this->annual_certificate_inspection['step'] == 1){
            if(intval($this->annual_certificate_inspection['business_id'])){
                $this->annual_certificate_inspection['step']+=1;
               
            }else{
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please select business!',
                    showConfirmButton 									: 'true',
                    timer             									: '1500',
                    link              									: '#'
                );
                return 0;
            }
        }elseif($this->annual_certificate_inspection['step'] == 2){
            if(!count($this->annual_certificate_inspection['annual_certificate_inspection_inspector'])){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please add at least one (1) inspector!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            foreach ($this->annual_certificate_inspection['annual_certificate_inspection_inspector'] as $key => $value) {
                if(!isset($value['category_id'])){
                    $this->dispatch('swal:redirect',
                        position         									: 'center',
                        icon              									: 'warning',
                        title             									: 'Please category for '.$value['content']->first_name.' '.$value['content']->middle_name.' '.$value['content']->last_name.' '.$value['content']->suffix.' ( '.$value['content']->work_role_name.' ) ',
                        showConfirmButton 									: 'true',
                        timer             									: '1500',
                        link              									: '#'
                    );
                    return;
                }
            }
            $this->annual_certificate_inspection['step']+=1;
        }elseif($this->annual_certificate_inspection['step'] == 3){
            if(!isset( $this->annual_certificate_inspection['date_compiled'])){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please input date compiled!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            // insert here
            if(DB::table('annual_certificate_inspections')
            ->insert([
                'business_id'  => $this->annual_certificate_inspection['business_id'],
                'application_type_id'  => $this->annual_certificate_inspection['application_type_id'],
                'bin'  => $this->annual_certificate_inspection['bin'],
                'occupancy_no'  => $this->annual_certificate_inspection['occupancy_no'],
                'date_compiled'  => $this->annual_certificate_inspection['date_compiled'],
                'issued_on'  => $this->annual_certificate_inspection['issued_on'],
            ])){
                $temp = DB::table('annual_certificate_inspections')
                ->orderBy('id','desc')
                ->first();
                foreach ($this->annual_certificate_inspection['annual_certificate_inspection_inspector'] as $key => $value) {
                    DB::table('annual_certificate_inspection_inspectors')
                    ->insert([
                        'annual_certificate_inspection_id'=> $temp->id,
                        'person_id' => $value['content']->id,
                        'category_id' => $value['category_id'],
                    ]);
                }
                $this->dispatch('swal:new_page',
                    position         									: 'center',
                    icon              									: 'success',
                    title             									: 'Successfully added!',
                    showConfirmButton 									: 'true',
                    timer             									: '1500',
                    link              									: ($_SERVER['REMOTE_PORT'] == 80? 'https://': 'http://' ).$_SERVER['SERVER_NAME'].'/administrator/certifications/generate/'.$temp->id
                );
            }
        }
    }
    public function prev_annual(){
        $this->annual_certificate_inspection['step']-=1;
    }
    public function save_image($image_file,$folder_name,$table_name,$column_name){
        if($image_file && file_exists(storage_path().'/app/livewire-tmp/'.$image_file->getfilename())){
            $file_extension =$image_file->getClientOriginalExtension();
            $tmp_name = 'livewire-tmp/'.$image_file->getfilename();
            $size = Storage::size($tmp_name);
            $mime = Storage::mimeType($tmp_name);
            $max_image_size = 20 * 1024*1024; // 5 mb
            $file_extensions = array('image/jpeg','image/png','image/jpg');
            
            if($size<= $max_image_size){
                $valid_extension = false;
                foreach ($file_extensions as $value) {
                    if($value == $mime){
                        $valid_extension = true;
                        break;
                    }
                }
                if($valid_extension){
                    // move
                    $new_file_name = md5($tmp_name).'.'.$file_extension;
                    while(DB::table($table_name)
                    ->where([$column_name=> $new_file_name])
                    ->first()){
                        $new_file_name = md5($tmp_name.rand(1,10000000)).'.'.$file_extension;
                    }
                    if(Storage::move($tmp_name, 'public/content/'.$folder_name.'/'.$new_file_name)){
                        return $new_file_name;
                    }
                }else{
                    $this->dispatch('swal:redirect',
                        position         									: 'center',
                        icon              									: 'warning',
                        title             									: 'Invalid image type!',
                        showConfirmButton 									: 'true',
                        timer             									: '1000',
                        link              									: '#'
                    );
                    return 0;
                }
            }else{
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Image is too large!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            } 
        }
        return 0;
    }

    public function reopenModal(){
        $this->dispatch('openModal','issueModaltoggler');
    }
    public function update_violation_proof_data($id){
        $inspection_violation_contents = DB::table('inspection_violation_contents as ivc')
            ->where('inspection_violation_id','=',$id)
            ->get()
            ->toArray();
        $violation = DB::table('inspection_violations as iv')
            ->select(
                'iv.id',
                'v.id as violation_id',
                'v.description',
                'iv.added_by',
            )
            ->join('violations as v','v.id','iv.violation_id')
            ->where('iv.id','=',$id)
            ->first();
        $this->violation_contents = [
            'inspection_violation_contents'=> $inspection_violation_contents,
            'violation' =>$violation,
            'photos'=> NULL,
            'inspection_violation_id'=>$id,
        ];
    }
    public $violation_contents = [
        'inspection_violation_contents'=> [],
        'violation' =>NULL,
        'photos'=> NULL,
    ];
    public function view_violation_proof($id,$modal_id){
        self::update_violation_proof_data($id);
        $this->dispatch('openModal',$modal_id);
    }


   
    public function update_violation_validated_proof_data($id){
        $inspection_violation_validated_contents = DB::table('inspection_violation_validated_contents as ivc')
            ->where('inspection_violation_id','=',$id)
            ->get()
            ->toArray();
        $violation = DB::table('inspection_violations as iv')
            ->select(
                'iv.id',
                'v.id as violation_id',
                'v.description',
                'iv.added_by',
            )
            ->join('violations as v','v.id','iv.violation_id')
            ->where('iv.id','=',$id)
            ->first();
        $this->violation_validated_contents = [
            'inspection_violation_validated_contents'=> $inspection_violation_validated_contents,
            'violation' =>$violation,
            'photos'=> NULL,
            'inspection_violation_id'=>$id,
        ];
    }
    public $violation_validated_contents = [
        'inspection_violation_validated_contents'=> [],
        'violation' =>NULL,
        'photos'=> NULL,
    ];
    public function view_violation_validated_proof($id,$modal_id){
        self::update_violation_validated_proof_data($id);
        $this->dispatch('openModal',$modal_id);
    }

    public function upload_photos(){
        if(isset($this->violation_validated_contents['photos'])){
            foreach ($this->violation_validated_contents['photos'] as $photo) {
                if($photo){
                    $save_photo = self::save_image($photo,'validatedproof','inspection_violation_validated_contents','img_url');
                    DB::table('inspection_violation_validated_contents')
                    ->insert([
                        'inspection_id'=> 0,
                        'inspection_violation_id'=> $this->violation_validated_contents['violation']->id,
                        'img_url'=> $save_photo,
                    ]);
                } 
            }
            self::update_violation_validated_proof_data($this->violation_validated_contents['inspection_violation_id']);
        }else{
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select an image!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
    }
    public function delete_proof_photo($id){
        $temp = DB::table('inspection_violation_validated_contents as ivc')
            ->where('id','=',$id)
            ->first();
        DB::table('inspection_violation_validated_contents as ivc')
            ->where('id','=',$id)
            ->delete();
        if(file_exists(storage_path('app/public/content/validatedproof/'.$temp->img_url))){
            unlink(storage_path('app/public/content/validatedproof/'.$temp->img_url));
        }
        self::update_violation_validated_proof_data($this->violation_validated_contents['inspection_violation_id']);
    }



    public $export = [
        'export_type'=> NULL,
        'step'=>1,
        'violation_all'=>true,
        'with_violation'=>true,
        'complied'=> 'All',
        'without_violation'=>true,
        'all_year'=>true,
        'years' => [],
        'all_brgy' => true,
        'brgy'=> [],
        'columns'=> [
            ['column_name'=> 'id','active'=> true,'name'=>'#'],
            ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
            ['column_name'=> 'first_name','active'=> true,'name'=>'Owner'],
            ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
            ['column_name'=> 'business_category_name','active'=> true,'name'=>'Business Category'],
            ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
            ['column_name'=> 'occupancy_classification_name','active'=> true,'name'=>'Char of Occu'],
            ['column_name'=> 'contact_number','active'=> true,'name'=>'Contact #'],
            ['column_name'=> 'email','active'=> true,'name'=>'Email'],
            ['column_name'=> 'floor_area','active'=> true,'name'=>'Floor Area'],
            ['column_name'=> 'signage_area','active'=> true,'name'=>'Signage Area'],
            ['column_name'=> 'id','active'=> true,'name'=>'Application Type'],
            ['column_name'=> 'schedule_date','active'=> true,'name'=>'Schedule'],
            ['column_name'=> 'remarks','active'=> true,'name'=>'Violation'],
            ['column_name'=> 'remarks','active'=> true,'name'=>'IsComplied?'],
        
        ],

    ];
    public function update_violation_all(){
        if($this->export['violation_all']){
            $this->export['with_violation'] = true;
            $this->export['without_violation'] = true;
        }else{
            $this->export['with_violation'] = false;
            $this->export['without_violation'] = false;
        }
    }
    public function update_violation_all_var(){
        if($this->export['with_violation'] &&  $this->export['without_violation']){
            $this->export['violation_all'] = true;
        }else{
            $this->export['violation_all'] = false;
        }
    }
    public function update_all_year(){
        foreach ($this->export['years'] as $key => $value) {
            $this->export['years'][$key]['selected'] = $this->export['all_year'];
            foreach ($value['month'] as $m_key => $m_value) {
                if(! $m_value['no_value']){
                    $this->export['years'][$key]['month'][$m_key]['selected'] = false;
                }else{
                    $this->export['years'][$key]['month'][$m_key]['selected'] = $this->export['all_year'];
                }
            }
        }
    }

    public function prev_export(){
        $this->export['step']--;
    }
    public function update_export_years($year){
        foreach ($this->export['years'] as $key => $value) {
            if($year == $value['year']){
                foreach ($value['month'] as $m_key => $m_value) {
                    if(! $m_value['no_value']){
                        $this->export['years'][$key]['month'][$m_key]['selected'] = false;
                    }else{
                        $this->export['years'][$key]['month'][$m_key]['selected'] = $value['selected'];
                    }
                }
            }
        }
    }
    public function update_all_brgy(){
        foreach ($this->export['brgy'] as $key => $value) {
            $this->export['brgy'][$key]['selected'] = $this->export['all_brgy'] ;
        }
    }
    public function next_export(){
        $this->export['step']++;
        if($this->export['step'] == 1){
            $status_id = DB::table('inspection_status')
                ->where('name','=','Completed')
                ->first()->id;
            $month_years = DB::table('inspections as i')
            ->select(
                DB::raw('YEAR(i.schedule_date) as year'),
                DB::raw('MONTH(i.schedule_date) as month_num'),
            )
            ->where('status_id','=',$status_id)
            ->groupby(DB::raw('YEAR(i.schedule_date), MONTH(i.schedule_date)'))
            ->orderBy(DB::raw('YEAR(i.schedule_date)'),'desc')
            ->orderBy(DB::raw('MONTH(i.schedule_date)'),'asc')
            ->get()
            ->toArray();

            $years = DB::table('inspections as i')
            ->select(
                DB::raw('DISTINCT(YEAR(i.schedule_date)) as year'),
            )
            ->where('status_id','=',$status_id)
            ->orderBy(DB::raw('YEAR(i.schedule_date)'),'desc')
            ->get()
            ->toArray();

            $temp = [];
            $month_name = ['Jan','Feb', 'Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
            foreach ($years as $key => $value) {
                $temp_month = [];
                for ($i=1; $i < 13; $i++) { 
                    array_push($temp_month,[
                        'month_name'=>$month_name[$i-1],
                        'month'=>$i,
                        'selected'=>false,
                        'no_value'=>false,
                        ]);
                }
                foreach ($month_years as $my_key => $my_value) {
                    if($value->year == $my_value->year){
                        $temp_month[$my_value->month_num-1]['selected'] = true;
                        $temp_month[$my_value->month_num-1]['no_value'] = true;
                    }
                }
                array_push($temp,[
                    'year'=>$value->year,
                    'month'=>$temp_month,
                    'selected'=>true,
                ]);
            }
            $this->export['all_year'] = true;
            $this->export['years'] = $temp;
        }elseif($this->export['step'] == 2){
            $valid = false;
            foreach ($this->export['years'] as $key => $value) {
                if($value['selected']){
                    $valid = true;
                    break;
                }
            }
            if(!$valid){
                $this->export['step']--;
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please select at least one year!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            $temp_years = [];
            foreach ($this->export['years'] as $key => $value) {
                if($value['selected']){
                    array_push($temp_years,$value['year']);
                }
            }
            $status_id = DB::table('inspection_status')
                ->where('name','=','Completed')
                ->first()->id;

            $brgy = DB::table('inspections as i')
            ->select(
                DB::raw('DISTINCT(b.brgy_id) as brgy_id'),
                'brg.brgyDesc as brgy_desc',
            )
            ->join('businesses as b','i.business_id','b.id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->where('status_id','=',$status_id)
            ->whereIn(DB::raw('YEAR(i.schedule_date)'),  $temp_years)
            ->orderBy('brg.brgyDesc','asc')
            ->get()
            ->toArray();
            $temp = [];
            foreach ($brgy as $key => $value) {
                array_push($temp,[
                    'brgy_id'=>$value->brgy_id,
                    'brgy_desc'=>$value->brgy_desc,
                    'selected'=>true,
                ]);
            }
            $this->export['brgy'] = $temp;
        }elseif($this->export['step'] == 3){
            $valid = false;
            foreach ($this->export['brgy'] as $key => $value) {
                if($value['selected']){
                    $valid = true;
                    break;
                }
            }
            if(!$valid){
                $this->export['step']--;
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please select at least one barangay!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }elseif($this->export['step'] == 4){
            
        }elseif($this->export['step'] == 5){
            $content_header = [];
            foreach ($this->export['columns'] as $key => $value) {
                # code...
            }
            if($export['export_type'] == 'EXCEL'){
                $export = new ExporterController([
                    $header,
                    $content
                ]);
                
                return Excel::download($export, $file_name.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            }elseif($export['export_type']){
                $export = new ExporterController([
                    $header,
                    $content
                ]);
                return Excel::download($export, $file_name.'.csv', \Maatwebsite\Excel\Excel::CSV);
            }
        }
    }
    public function export_file($modal_id){
        $this->export = [
            'export_type'=> NULL,
            'step'=>1,
            'violation_all'=>true,
            'with_violation'=>true,
            'complied'=> 'All',
            'without_violation'=>true,
            'all_year'=>true,
            'years' => [],
            'all_brgy' => true,
            'brgy'=> [],
            'columns'=> [
                ['column_name'=> 'id','active'=> true,'name'=>'#'],
                ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
                ['column_name'=> 'first_name','active'=> true,'name'=>'Owner'],
                ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
                ['column_name'=> 'business_category_name','active'=> true,'name'=>'Business Category'],
                ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
                ['column_name'=> 'occupancy_classification_name','active'=> true,'name'=>'Char of Occu'],
                ['column_name'=> 'contact_number','active'=> true,'name'=>'Contact #'],
                ['column_name'=> 'email','active'=> true,'name'=>'Email'],
                ['column_name'=> 'floor_area','active'=> true,'name'=>'Floor Area'],
                ['column_name'=> 'signage_area','active'=> true,'name'=>'Signage Area'],
                ['column_name'=> 'id','active'=> true,'name'=>'Application Type'],
                ['column_name'=> 'schedule_date','active'=> true,'name'=>'Schedule'],
                ['column_name'=> 'remarks','active'=> true,'name'=>'Violation'],
                ['column_name'=> 'remarks','active'=> true,'name'=>'IsComplied?'],
            
            ],
        ];
        $this->export['step'] = 0;
        $this->dispatch('openModal',$modal_id);
        self::next_export();
    }
    public $payment_or = NULL;
    public $inspection_id = NULL;
    public function payment($id,$modal_id){
        $this->inspection_id = $id;
        $payment_or = DB::table('inspections')
        ->select(
            'or_number'
        )
        ->where('id','=',$this->inspection_id)
        ->first();
        $this->payment_or = $payment_or->or_number;
        $this->dispatch('openModal',$modal_id);
    }
    public function update_or_number($id,$modal_id){
        if(intval($this->payment_or)>0){
            DB::table('inspections')
                ->where('id','=',$this->inspection_id)
                ->update([
                    'or_number'=>$this->payment_or
                ]);
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Official Receipt saved!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $this->dispatch('openModal',$modal_id);
        }else{
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please input a valid Official Receipt!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
    }
}
