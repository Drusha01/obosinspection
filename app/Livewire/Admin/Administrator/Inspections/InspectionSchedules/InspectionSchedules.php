<?php

namespace App\Livewire\Admin\Administrator\Inspections\InspectionSchedules;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class InspectionSchedules extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Inspection schedules";
    public $inspector_leaders;
    public $inspector_members;
    public $businesses;
    public $inspection = [
        'id'=>NULL,
        'inspector_leaders' =>[],
        'inspector_members' => [],
        'business_id' =>NULL,
        'schedule_date'=>NULL,
        'step'=> 1,
    ];

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
        ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
        ['column_name'=> 'schedule_date','active'=> true,'name'=>'Schedule'],
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
    public function mount(){
        $this->businesses = DB::table('businesses as b')
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
            ->where('b.is_active','=',1)
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
            )
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
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
            )
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->whereNotNull('it.team_leader_id')
            ->where('pt.name','Inspector')
            ->get()
            ->toArray();
    }
    public function render()
    {
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
            ->join('inspection_status as st','st.id','i.status_id')
            ->join('businesses as b','b.id','i.business_id')
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('st.name','=','Pending')
            ->orderBy('id','desc')
            ->paginate(10);
            // dd($table_data);
        return view('livewire.admin.administrator.inspections.inspection-schedules.inspection-schedules',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $this->inspection = [
            'id'=>NULL,
            'inspector_leaders' =>[],
            'inspector_leader_id'=>NULL,
            'inspector_members' => [],
            'inspector_member_id'=>NULL,
            'business_id' =>NULL,
            'schedule_date'=>date_format(date_create(now()),"Y-m-d"),
            'step'=> 1,
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
    public function update_inspection_data($id,$step){
        
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
            )
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->whereNull('it.team_leader_id')
            ->where('pt.name','Inspector')
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
            )
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->whereNotNull('it.team_leader_id')
            ->where('pt.name','Inspector')
            ->get()
            ->toArray();

        $violations = DB::table('violations')
            ->where('is_active','=',1)
            ->get()
            ->toArray();

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
            ->first();

        $inspection_items = DB::table('inspection_items as ii')
            ->select(
                'c.name as category_name',
                'c.id as category_id',
                'i.name',
                'i.section',
                'i.img_url',
                'i.is_active',
                "ii.id",
                "ii.inspection_id",
                "ii.item_id",
                "ii.equipment_billing_id",
                "ii.power_rating",
                "ii.quantity",
                "eb.fee",
            )
            ->join('items as i','i.id','ii.item_id')
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
                'section' => $value->section,
                'img_url' => $value->img_url,
                'is_active' => $value->is_active,
                "id" => $value->id,
                "inspection_id" => $value->inspection_id,
                "item_id" => $value->item_id,
                "equipment_billing_id" => $value->equipment_billing_id,
                "power_rating" => $value->power_rating,
                "quantity" => $value->quantity,
                "fee" => $value->fee,
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
            )
            ->leftjoin('inspection_inspector_members as iim','p.id','iim.person_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('pt.name','Inspector')
            ->where('iim.inspection_id','=',$id)
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
            )
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
                'description'
            )
            ->join('violations as v','v.id','iv.violation_id')
            ->where('inspection_id','=',$id)
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
                "id" => $value->id,
            ]);
        }
        $inspection_violations = $temp;
        $items = DB::table('items as i')
            ->select(
                'i.id',
                'c.name as category_name',
                'i.name',
                'i.section',
                'i.img_url',
                'i.is_active'
            )
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

        $this->issue_inspection = [
            'id' => $inspection->id,
            'status_id' => $inspection->status_id,
            'business_id' => $inspection->business_id,
            'schedule_date' => $inspection->schedule_date,
            'signage_id' => $inspection->signage_id,
            'signage_billing_fee' => $signage_billing_fee,
            'building_billing_id' => $inspection->building_billing_id,
            'building_billing_fee' => $building_billing_fee,
            'application_type_id' => $inspection->application_type_id,
            'remarks' => $inspection->remarks,
            'date_signed' => $inspection->date_signed,
            'step'=> $step,

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
        ];
        // dd($this->issue_inspection);
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
        // dd($this->issue_inspection);
    }
    public function update_application_type(){
        if(intval($this->issue_inspection['application_type_id'])){
            DB::table('inspections as i')
                ->where('id','=',$this->issue_inspection['id'])
                ->update([
                    'application_type_id'=>$this->issue_inspection['application_type_id']
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
                self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
            }
        }
    }
    public function update_equipment_billing($id,$key){
        DB::table('inspection_items')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->update([
                'equipment_billing_id'=> $this->issue_inspection['inspection_items'][$key]['equipment_billing_id']
            ]);
            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);

    }
    public function update_item_quantity($id,$key){
        DB::table('inspection_items')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->update([
                'quantity'=> $this->issue_inspection['inspection_items'][$key]['quantity']
            ]);
            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_item_power_rating($id,$key){
        DB::table('inspection_items')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->update([
                'power_rating'=> $this->issue_inspection['inspection_items'][$key]['power_rating']
            ]);
            self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    
    public function update_building_billing(){
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
                self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
            }
        }
    }
    public function update_sanitary_quantity($id,$key){
        DB::table('inspection_sanitary_billings')
        ->where('id','=',$id)
        ->where('inspection_id','=',$this->issue_inspection['id'])
        ->update([
            'sanitary_quantity'=> $this->issue_inspection['inspection_sanitary_billings'][$key]['sanitary_quantity']
        ]);
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_signage_billing(){
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
                self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
            }
        }
    }
    public function update_inspection_violation(){
        if(intval($this->issue_inspection['violation_id'])){
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
                self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
            }
        }
    }
    public function update_delete_item($id){
        DB::table('inspection_items')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->delete();
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
    }
    public function update_delete_sanitary($id){
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
        DB::table('inspection_inspector_members')
            ->where('id','=',$id)
            ->where('inspection_id','=',$this->issue_inspection['id'])
            ->delete();
        self::update_inspection_data($this->issue_inspection['id'],$this->issue_inspection['step']);
       
    }
    public function update_delete_violation($id){
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
            $this->dispatch('openModal',$modal_id);
            return 0;
        }
    }
}
