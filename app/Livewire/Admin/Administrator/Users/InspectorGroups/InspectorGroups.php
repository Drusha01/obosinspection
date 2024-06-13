<?php

namespace App\Livewire\Admin\Administrator\Users\InspectorGroups;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class InspectorGroups extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Inspector groups";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'name','active'=> true,'name'=>'Group Name'],
        ['column_name'=> 'Leader','active'=> true,'name'=>'Team Leader'],
        ['column_name'=> 'id','active'=> true,'name'=>'Members'],
        ['column_name'=> 'id','active'=> true,'name'=>'Designated Barangays'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $inspector_team = [
        'id'=>NULL,
        'name'=>NULL,
        'team_leader_id'=>NULL,
        'is_active'=>NULL,
    ];
    public $unassigned_inspectors;
    public $all_inspectors;
    public $designations = [];
    public $brgy = []; 
    public $unassigned_brgy;
    public $members = [];
    public $inspector_members =[];
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
    ];
    public $search_by = [
        ['name'=>'Team name','column_name'=>'it.name'],
        ['name'=>'Leader','column_name'=>"CONCAT(p.first_name,' ',p.last_name)"],
    ];

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
        if($this->search['type'] != $this->search['type_prev']){
            $this->search['type_prev'] = $this->search['type'];
            $this->resetPage();
        }else{
            if(!$this->search['type']){
                $this->search['type'] = $this->search_by[0]['column_name'];
            }
        }
        $this->unassigned_inspectors = DB::table('persons as p')
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
            )
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->leftjoin('inspector_members as im','p.id','im.member_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->whereNull('it.team_leader_id')
            ->whereNull('im.member_id')
            ->where('pt.name','Inspector')
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
            ->leftjoin('inspector_members as im','p.id','im.member_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->whereNull('it.team_leader_id')
            ->whereNull('im.member_id')
            ->where('pt.name','Inspector')
            ->get()
            ->toArray();

        $this->all_inspectors = DB::table('persons as p')
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
                'it.team_leader_id'
            )
            ->leftjoin('inspector_teams as it','p.id','it.team_leader_id')
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->where('pt.name','Inspector')
            ->get()
            ->toArray();
        $table_data = DB::table('inspector_teams as it')
            ->select(
                'u.username',
                'it.id',
                'it.name',
                'it.team_leader_id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                'it.is_active'
                )
            ->join('persons as p','p.id','it.team_leader_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('users as u','u.person_id','p.id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where(DB::raw($this->search['type']),'like',$this->search['search'] .'%')
            ->orderBy('id','desc')
            ->paginate($this->table_filter['table_rows']);
        return view('livewire.admin.administrator.users.inspector-groups.inspector-groups',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $this->inspector_team = [
            'id'=>NULL,
            'name'=>NULL,
            'team_leader_id'=>NULL,
            'is_active'=>NULL,
        ];
        $this->dispatch('openModal',$modal_id);  
    }
    public function save_add($modal_id){
        if(!strlen($this->inspector_team['name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter name!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $edit = DB::table('inspector_teams')
                ->where('name','=',$this->inspector_team['name'])
                ->first();
            if($edit){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Group name exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(!intval($this->inspector_team['team_leader_id'])){
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
        if( DB::table('inspector_teams')
            ->insert([
                'name'=>$this->inspector_team['name'],
                'team_leader_id'=>$this->inspector_team['team_leader_id'],
        ])
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has added '.$this->inspector_team['name'], 
            ]);
            $this->dispatch('openModal',$modal_id);  
        }
    }
    public function edit($id,$modal_id){
        if($edit =  DB::table('inspector_teams as it')
        ->select(
            'it.id',
            'it.name',
            'it.team_leader_id',
            'p.first_name',
            'p.middle_name',
            'p.last_name',
            'p.suffix',
            'wr.id as work_role_id',
            'wr.name as work_role_name',
            'it.is_active'
            )
        ->join('persons as p','p.id','it.team_leader_id')
        ->join('person_types as pt', 'pt.id','p.person_type_id')
        ->join('work_roles as wr', 'wr.id','p.work_role_id')
        ->where('it.id','=',$id)
        ->first()){
            $this->inspector_team = [
                'id'=>$edit->id,
                'name'=>$edit->name,
                'team_leader_id'=>$edit->team_leader_id,
                'is_active'=>$edit->is_active,
            ];
            $this->dispatch('openModal',$modal_id);  
        }
    }
    public function save_edit($id,$modal_id){
        if(!strlen($this->inspector_team['name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter name!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $edit = DB::table('inspector_teams')
                ->where('id','<>',$id)
                ->where('name','=',$this->inspector_team['name'])
                ->first();
            if($edit){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Group name exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(!intval($this->inspector_team['team_leader_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select team leader!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(DB::table('inspector_teams as it')
            ->where('it.team_leader_id','=',$this->inspector_team['team_leader_id'])
            ->where('it.id','<>',$id)
            ->first()){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please select unassigned team leader!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }else{
                $temp = DB::table('inspector_teams as it')
                ->select(
                    'it.id',
                    'it.name',
                    'it.team_leader_id',
                    'p.first_name',
                    'p.middle_name',
                    'p.last_name',
                    'p.suffix',
                    'wr.id as work_role_id',
                    'wr.name as work_role_name',
                    'it.is_active'
                    )
                ->join('persons as p','p.id','it.team_leader_id')
                ->join('person_types as pt', 'pt.id','p.person_type_id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                ->whereNotNull('it.team_leader_id')
                ->where('it.team_leader_id','=',$this->inspector_team['team_leader_id'])
                ->where('it.id','<>',$id)
                ->first();
                if($temp){
                    $this->dispatch('swal:redirect',
                        position         									: 'center',
                        icon              									: 'warning',
                        title             									: 'Please select unassigned team leader!',
                        showConfirmButton 									: 'true',
                        timer             									: '1000',
                        link              									: '#'
                    );
                    return 0;
                }
            }
        }
        if(
            DB::table('inspector_teams')
            ->where('id','=',$id)
            ->update([
                'name'=>$this->inspector_team['name'],
                'team_leader_id'=>$this->inspector_team['team_leader_id'],
            ])
        ){
            
        }
        $this->dispatch('swal:redirect',
            position         									: 'center',
            icon              									: 'success',
            title             									: 'Successfully updated!',
            showConfirmButton 									: 'true',
            timer             									: '1000',
            link              									: '#'
        );
        DB::table('activity_logs')
        ->insert([
            'created_by' => $this->activity_logs['created_by'],
            'inspector_team_id' => $this->activity_logs['inspector_team_id'],
            'log_details' => 'has edited '.$this->inspector_team['name'], 
        ]);
        $this->dispatch('openModal',$modal_id);  
    }
    public function save_deactivate($id,$modal_id){
        if(
            DB::table('inspector_teams')
                ->where('id','=',$id)
                ->update([
                    'is_active'=>0
                ])            
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully updated!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deactivated '.$this->inspector_team['name'], 
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('inspector_teams')
                ->where('id','=',$id)
                ->update([
                    'is_active'=>1
                ])            
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully updated!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has activated '.$this->inspector_team['name'], 
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
   
    public function add_designation($id,$modal_id){
        $city_mun = DB::table('citymun')
            ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
            ->first();
        
        $this->brgy = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->orderBy('brgyDesc','asc')
            ->get()
            ->toArray();
        $this->designations = DB::table('team_target_barangays as ttb')
            ->select(
                'ttb.id',
                'b.brgyDesc',
                'ttb.brgy_id'
                )
            ->join('brgy as b','b.id','ttb.brgy_id')
            ->where('ttb.inspector_team_id','=',$id)
            ->get()
            ->toArray();
        if($edit =  DB::table('inspector_teams as it')
            ->select(
                'it.id',
                'it.name',
                'it.team_leader_id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                'it.is_active'
                )
            ->join('persons as p','p.id','it.team_leader_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('it.id','=',$id)
            ->first()){
                $this->inspector_team = [
                    'id'=>$edit->id,
                    'name'=>$edit->name,
                    'team_leader_id'=>$edit->team_leader_id,
                    'brgy_id'=>NULL,
                    'is_active'=>$edit->is_active,
                ];
                $this->dispatch('openModal',$modal_id);  
        }
    }
    public function save_add_designation(){
        if(intval($this->inspector_team['brgy_id'])){
            $temp = DB::table('team_target_barangays as ttb')
            ->where('ttb.inspector_team_id','=',$this->inspector_team['id'])
            ->where('ttb.brgy_id','=',$this->inspector_team['brgy_id'])
            ->first();
            if($temp){

            }else{
                DB::table('team_target_barangays')
                    ->insert([
                    'brgy_id'=>$this->inspector_team['brgy_id'],
                    'inspector_team_id'=>$this->inspector_team['id']
                ]);
                $barangays = DB::table('brgy')
                ->where('id','=',$this->inspector_team['brgy_id'])
                ->first();
                DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has added a barangay '.$barangays->brgyDesc.' designation to '.$this->inspector_team['name'], 
                ]);
            }
        }else{
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'No barangay selected!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return;
        }
        $this->designations = DB::table('team_target_barangays as ttb')
            ->select(
                'ttb.id',
                'b.brgyDesc',
                'ttb.brgy_id'
                )
            ->join('brgy as b','b.id','ttb.brgy_id')
            ->where('ttb.inspector_team_id','=',$this->inspector_team['id'])
            ->get()
            ->toArray();
    }
    public function delete_designation($id,$brgy_id){

        $barangays = DB::table('brgy')
                ->where('id','=',$brgy_id)
                ->first();
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deleted a barangay '.$barangays->brgyDesc.' designation to '.$this->inspector_team['name'], 
            ]);
        DB::table('team_target_barangays')
            ->where('id','=',$id)
            ->delete();
        $this->designations = DB::table('team_target_barangays as ttb')
            ->select(
                'ttb.id',
                'b.brgyDesc',
                'ttb.brgy_id'
                )
            ->join('brgy as b','b.id','ttb.brgy_id')
            ->where('ttb.inspector_team_id','=',$this->inspector_team['id'])
            ->get()
            ->toArray();
    }
    public function view_members($id,$modal_id){
        $this->members = DB::table('inspector_members as im')
            ->select(
                'im.id',
                'im.member_id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                )
            ->join('persons as p','p.id','im.member_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('im.inspector_team_id','=',$id)
            ->get()
            ->toArray();

        if($edit =  DB::table('inspector_teams as it')
            ->select(
                'it.id',
                'it.name',
                'it.team_leader_id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                'it.is_active'
                )
            ->join('persons as p','p.id','it.team_leader_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('it.id','=',$id)
            ->first()){
                $this->inspector_team = [
                    'id'=>$edit->id,
                    'name'=>$edit->name,
                    'team_leader_id'=>$edit->team_leader_id,
                    'member_id'=>NULL,
                    'is_active'=>$edit->is_active,
                ];
            $this->dispatch('openModal',$modal_id);  
        }
    }
    public function save_add_member(){
        if(intval($this->inspector_team['member_id'])){
            $member = DB::table('inspector_members')
            ->where('inspector_team_id','=',$this->inspector_team['id'])
            ->where('member_id','=',$this->inspector_team['member_id'])
            ->first();
            if($member){

            }else{
                if(DB::table('inspector_members')
                    ->insert([
                        'inspector_team_id'=>$this->inspector_team['id'],
                        'member_id'=> $this->inspector_team['member_id']
                ])){
                    $members =DB::table('inspector_members as im')
                        ->select(
                            'im.id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'wr.id as work_role_id',
                            'wr.name as work_role_name',
                            )
                        ->join('persons as p','p.id','im.member_id')
                        ->join('person_types as pt', 'pt.id','p.person_type_id')
                        ->join('work_roles as wr', 'wr.id','p.work_role_id')
                        ->where('p.id','=',$this->inspector_team['member_id'])
                        ->first();
                    DB::table('activity_logs')
                    ->insert([
                        'created_by' => $this->activity_logs['created_by'],
                        'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                        'log_details' => 'has added a member ( '.$members->first_name.' '.$members->middle_name.' '.$members->last_name.' '.$members->suffix.' ) to '.$this->inspector_team['name'], 
                    ]);
                    $this->inspector_team['member_id'] = NULL;
                    $this->members =DB::table('inspector_members as im')
                        ->select(
                            'im.id',
                            'im.member_id',
                            'p.first_name',
                            'p.middle_name',
                            'p.last_name',
                            'p.suffix',
                            'wr.id as work_role_id',
                            'wr.name as work_role_name',
                            )
                        ->join('persons as p','p.id','im.member_id')
                        ->join('person_types as pt', 'pt.id','p.person_type_id')
                        ->join('work_roles as wr', 'wr.id','p.work_role_id')
                        ->where('im.inspector_team_id','=',$this->inspector_team['id'])
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
                        ->leftjoin('inspector_members as im','p.id','im.member_id')
                        ->join('person_types as pt','p.person_type_id','pt.id')
                        ->join('work_roles as wr', 'wr.id','p.work_role_id')
                        ->whereNull('it.team_leader_id')
                        ->whereNull('im.member_id')
                        ->where('pt.name','Inspector')
                        ->get()
                        ->toArray();
            
                }
            }
        }
    }
    public function delete_member($id,$member_id){
        if(DB::table('inspector_members')
            ->where('id','=',$id)
            ->delete()
        ){
            $members =DB::table('persons as p')
                ->select(
                    'p.first_name',
                    'p.middle_name',
                    'p.last_name',
                    'p.suffix',
                    )
                ->where('p.id','=',$member_id)
                ->first();
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deleted a member ( '.$members->first_name.' '.$members->middle_name.' '.$members->last_name.' '.$members->suffix.' ) to '.$this->inspector_team['name'], 
            ]);
            $this->members =DB::table('inspector_members as im')
                ->select(
                    'im.id',
                    'im.member_id',
                    'p.first_name',
                    'p.middle_name',
                    'p.last_name',
                    'p.suffix',
                    'wr.id as work_role_id',
                    'wr.name as work_role_name',
                    )
                ->join('persons as p','p.id','im.member_id')
                ->join('person_types as pt', 'pt.id','p.person_type_id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                ->where('im.inspector_team_id','=',$this->inspector_team['id'])
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
                ->leftjoin('inspector_members as im','p.id','im.member_id')
                ->join('person_types as pt','p.person_type_id','pt.id')
                ->join('work_roles as wr', 'wr.id','p.work_role_id')
                ->whereNull('it.team_leader_id')
                ->whereNull('im.member_id')
                ->where('pt.name','Inspector')
                ->get()
                ->toArray();
            
        }

    }
}
