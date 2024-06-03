<?php

namespace App\Livewire\Admin\Administrator\Inspections\Generate;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Generate extends Component
{
    public $title = "Generate PDF"; 
    public $issue_inspection;
    public function mount($id){
        self::update_inspection_data($id);
        if($this->issue_inspection){
        }else{
            return redirect()->route('administrator-inspections-inspection-schedules');
        }
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
        DB::table('activity_logs')
        ->insert([
            'created_by' => $this->activity_logs['created_by'],
            'inspector_team_id' => $this->activity_logs['inspector_team_id'],
            'log_details' => 'has generated a inspection equipments pdf for '.$this->issue_inspection['inspection_business_name'],
        ]);
        return view('livewire.admin.administrator.inspections.generate.generate')
            ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function update_inspection_data($id){
        
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
        $signafge_billings = $temp;
        
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
                'b.street_address',
                'st.name as status_name',
                'i.schedule_date',
                "i.status_id",
                "i.business_id",
                "i.signage_id",
                "i.building_billing_id",
                "i.application_type_id",
                "i.remarks",
                "i.date_signed",
                'at.name as application_type_name',
            )
            ->join('inspection_status as st','st.id','i.status_id')
            ->join('businesses as b','b.id','i.business_id')
            ->leftjoin('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->leftjoin('application_types as at','i.application_type_id','at.id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('i.id','=',$id)
            ->first();
        $inspection_building_billings = [];
        if(isset($inspection->building_billing_id)){
            $inspection_building_billings = DB::table('building_billings as bb')
                ->select(
                    "bb.id",
                    "bb.section_id",
                    'bbs.name as section_name',
                    "bb.property_attribute",
                    "bb.fee",
                )
                ->join('building_billing_sections as bbs','bbs.id','bb.section_id')
                ->where('bb.id','=',$inspection->building_billing_id)
                ->get()
                ->toArray();
        }
        $inspection_signage_billings = [];
        if(isset($inspection->signage_id)){
            $inspection_signage_billings = DB::table('signage_billings as sb')
                ->select(
                    'sb.id',
                    'sbdt.name as display_type_name',
                    'sbt.name as sign_type_name',
                    'sb.fee',
                    'sb.is_active'
                )
                ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
                ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
                ->where('sb.id','=',$inspection->signage_id)
                ->get()
                ->toArray();
        }

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
        $inspectors = [];
        // foreach ($inspection_inspector_members as $key => $value) {
        //     array_push($inspectors,$value);
        // }
        foreach ($inspector_team_leaders as $key => $value) {
            array_push($inspectors,$value);
        }

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
            ->whereNull('remarks')
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
        $building_billing_fee = 0;
        if(isset($inspection->building_billing_id)){
            $building_billing = DB::table('building_billings')
                ->where('id','=',$inspection->building_billing_id)
                ->first();
            if($building_billing){
                $building_billing_fee = $building_billing->fee;
            }
        }
        
        $signage_billing_fee = 0;
        if(isset($inspection->signage_id)){
            $signage_billing = DB::table('signage_billings')
            ->where('id','=',$inspection->signage_id)
            ->first();
            if($signage_billing){
                $signage_billing_fee = $signage_billing->fee;
            }
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
            'inspection'=>$inspection,

            'inspection_business_name' => $inspection->business_name. ' ( '.$inspection->business_type_name.' )',
            'inspection_items' =>$inspection_items,
            'inspection_inspector_members' =>$inspection_inspector_members,
            'inspection_inspector_team_leaders' =>$inspection_inspector_team_leaders,
            'inspection_violations' =>$inspection_violations,
            'inspection_sanitary_billings' =>$inspection_sanitary_billings,
            'inspection_signage_billings' =>$inspection_signage_billings,
            'inspection_building_billings'  =>$inspection_building_billings,
            'item_id'=> NULL,
            'violatio_id'=> NULL,
            'sanitary_billing_id'=> NULL,
            'inspector_leader_id'=> NULL,
            'inspector_member_id'=> NULL,
            'inspectors'=>$inspectors,

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
}
