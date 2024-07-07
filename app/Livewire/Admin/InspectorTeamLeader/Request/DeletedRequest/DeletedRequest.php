<?php

namespace App\Livewire\Admin\InspectorTeamLeader\Request\DeletedRequest;


use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class DeletedRequest extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Deleted Requests";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'business_name','active'=> true,'name'=>'Business Name'],
        ['column_name'=> 'status_name','active'=> true,'name'=>'Status'],
        ['column_name'=> 'request_date','active'=> true,'name'=>'Request Range'],
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
    public function render(Request $request)
    {
        $session = $request->session()->all();
        $person = DB::table('users as u')
            ->select('u.person_id')
            ->where('u.id','=',$session['id'])
            ->first();
        if($this->search['search'] != $this->search['search_prev']){
            $this->search['search_prev'] = $this->search['search'];
            $this->resetPage();
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
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')

            ->leftjoin('team_target_barangays as ttb','ttb.brgy_id','b.brgy_id')
            ->leftjoin('brgy as brg','brg.id','ttb.brgy_id')
            ->join('inspector_teams as it','it.id','ttb.inspector_team_id')
            ->where('it.team_leader_id','=',$person->person_id)

            ->where('rs.name','=','Deleted')
            ->where('b.name','like',$this->search['search'] .'%')
            ->orderBy('ri.id','desc')
            ->paginate($this->table_filter['table_rows']);
        
        return view('livewire.admin.inspector-team-leader.request.deleted-request.deleted-request',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
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
