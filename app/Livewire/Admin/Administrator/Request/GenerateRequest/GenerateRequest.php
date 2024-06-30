<?php

namespace App\Livewire\Admin\Administrator\Request\GenerateRequest;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use Mail;

class GenerateRequest extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $email;
    public $title = "Generate Requests";
    public $establishment;
    public $subject ;
    public $owner;
    public $business = [];

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'business_name','active'=> true,'name'=>'Business Name'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Barangay'],
        ['column_name'=> 'status_name','active'=> true,'name'=>'Status'],
        ['column_name'=> 'request_date','active'=> true,'name'=>'Request Range'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    
    public $activity_logs = [
        'created_by' => NULL,
        'inspector_team_id' => NULL,
        'log_details' => NULL,
    ];
    public $search = [
        'search'=> NULL,
        'search_prev'=> NULL,
    ];
    public $request  = [
        'id' =>NULL,
        'business_id' =>NULL,
        'status_id' =>NULL,
        'request_date' =>NULL,
        'expiration_date' =>NULL,
        'accepted_date' =>NULL,
        'is_responded' =>NULL,
        'reason' =>NULL,
    ];
    public $brgy = [];

    public $modal = [
        'search'=>NULL,
        'search_prev'=> NULL,
        'brgy_id'=> NULL,
        'prev_brgy_id'=> NULL,
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
                ->where('b.brgy_id','=',$this->modal['brgy_id'] )
                ->where('b.name','like',$this->modal['search'] .'%')
                ->limit(15)
                ->get()
                ->toArray();
        }else{
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
                ->where('b.name','like',$this->modal['search'] .'%')
                ->limit(15)
                ->get()
                ->toArray();
        }
        $table_data = DB::table('request_inspections as ri')
            ->select(
                'ri.id',
                'b.img_url',
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
                'ri.reason',
                'brg.brgyDesc as barangay',
            )
            ->join('request_status as rs','rs.id','ri.status_id')
            ->join('businesses as b','b.id','ri.business_id')
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('rs.name','=','Pending')
            ->where('b.name','like',$this->search['search'] .'%')
            ->where('ri.expiration_date', '>=', date('Y-m-d'))
            ->orderBy('ri.id','desc')
            ->paginate($this->table_filter['table_rows']);
        return view('livewire.admin.administrator.request.generate-request.generate-request',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
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
        
        $this->hash = md5($business->name.now());
        while(DB::table('request_inspections')
            ->where('hash','=',$this->hash)
            ->first()){
            $this->hash = md5($business->name.now());
        }
        $this->port = $_SERVER['SERVER_PORT'];
        $this->host_name = $_SERVER['SERVER_NAME'];
        $this->subject = 'OBOS Inspection would like to request to inspect your establishment from '.date_format(date_create($this->start_date),"M d, Y ").' to '.date_format(date_create($this->end_date),"M d, Y ").', to accept please click the accept button, to 
            decline please click the decline button and provide a reason after the redirection. Thank you.'; 
        
        $this->content = 'Sir/Madam:
        <br>
        <br>
        Pursuant to PD 1096, otherwise known as the National Building Code of the Philippines and its IRR, the Building Official shall undertake annual inspection of all buildings/structures and keep an updated record of their status. Also in the performance of his duties, a Building Official may enter any building or its premises at all reasonable times to inspect and determine compliance with the requirements of the NBPC.
        <br>
        <br>
        You are hereby inform that the OBO Inspectorate team will conduct an Annual Inspection of your establishment on  <strong>'.date_format(date_create($this->start_date),"M d, Y ").' to '.date_format(date_create($this->end_date),"M d, Y ").' </strong> , to ensure safety of your building and update the fees and status of your equipment.
        <br>
        <br>
        Please prepare the approve plans (Structural, Electrical, Mechanical, Plumbing & Electronics), Occupancy Permit, Update site Development Plan, and a consolidated list of equipment during the scheduled inspection
        <br>
        <br>
        A certificate of Annual Inspection will be issued to you after we found your building to be safe for use and/or after the compliance of deficiencies and payment of necessary fees have been made.';
        
        $code =1;

        if(Mail::send('mail.requestToInspect', [
                'code'=>$code,
                'email'=>$this->email,
                'establishment'=>$this->establishment,
                'owner'=>$this->owner,
                'content'=>$this->content,
                'hash'=>$this->hash,
                'port'=>$this->port,
                'host_name'=>$this->host_name
                ], 
                    function($message) {
                $message->to($this->email, $this->email)->subject
                ($this->subject);
                $message->from('obosinspection@gmail.com','OBOS INSPECTION');
            })){
            $status = DB::table('request_status')
                ->where('name','=',"Pending")
                ->first();
            DB::table('request_inspections')
            ->insert([
                'business_id' =>$this->request['business_id'],
                'status_id' => $status->id,
                'request_date' =>$this->request['request_date'],
                'expiration_date' =>$this->request['expiration_date'],
                'hash' => $this->hash,
            ]);
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Email sent!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $this->dispatch('openModal',$modal_id);
        }
    }

    public function generate_request($modal_id){
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
            'is_responded' =>NULL,
            'reason' =>NULL,
            'duration'=>10,
        ];
        $this->request ['expiration_date'] = date_format(date_add(date_create($this->request ['request_date']),date_interval_create_from_date_string($this->request ['duration']." days")),"Y-m-d");
        $this->dispatch('openModal',$modal_id);
        // dd( $this->request);
    }
    public function update_expiration_date(){
        $this->request ['expiration_date'] = date_format(date_add(date_create($this->request ['request_date']),date_interval_create_from_date_string($this->request ['duration']." days")),"Y-m-d");
    }
    public function update_duration(){
        $interval =  date_diff(date_create($this->request['request_date']),date_create($this->request['expiration_date']));
        $duration =  $interval->format('%d');
        if($interval->format('%r')){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Invalid date!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            self::update_expiration_date();
            return 0;
        }else{
            $this->request ['duration'] = $interval->format('%d');
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
                'rs.name as status_name',
            )
            ->join('request_status as rs','ri.status_id','rs.id')
            ->where('rs.name','=','Pending')
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
            ->where('rs.name','=','Pending')
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

}
