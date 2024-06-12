<?php

namespace App\Livewire\Admin\Administrator\Billings\SignageBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class SignageBillings extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Signage billings";

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'display_type_name','active'=> true,'name'=>'Display type'],
        ['column_name'=> 'sign_type_name','active'=> true,'name'=>'Sign Type'],
        ['column_name'=> 'fee','active'=> true,'name'=>'Fee'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $signage_billing = [
        'id' => NULL,
        'display_type_id' => NULL,
        'sign_type_id' => NULL,
        'fee' => NULL,
        'is_active' => NULL,
    ];

    public $signage_billing_display_types;
    public $signage_billing_types;

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
        $this->signage_billing_display_types = DB::table('signage_billing_display_types')
            ->where('is_active','=',1)
            ->get()
            ->toArray();
        $this->signage_billing_types = DB::table('signage_billing_types')
            ->where('is_active','=',1)
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

        $table_data = DB::table('signage_billings as sb')
            ->select(
                'sb.id',
                'sbdt.name as display_type_name',
                'sbt.name as sign_type_name',
                'sb.fee',
                'sb.is_active'
            )
            ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
            ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
            ->where('sbdt.name','like',$this->search['search'] .'%')
            ->orderBy('sb.id','desc')
            ->paginate($this->table_filter['table_rows']);
        return view('livewire.admin.administrator.billings.signage-billings.signage-billings',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $this->signage_billing = [
            'id' => NULL,
            'display_type_id' => NULL,
            'sign_type_id' => NULL,
            'fee' => NULL,
            'is_active' => NULL,
        ];
        $this->dispatch('openModal',$modal_id);   
    }

    public function save_add($modal_id){
        if(floatval($this->signage_billing['fee'])<=0){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Fee must be greater than 0!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->signage_billing['display_type_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select category!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->signage_billing['sign_type_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select section!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        
        if(DB::table('signage_billings')
            ->insert([
                'sign_type_id' => $this->signage_billing['sign_type_id'],
                'display_type_id' => $this->signage_billing['display_type_id'],
                'fee'=>$this->signage_billing['fee']
            ])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $edit =  DB::table('signage_billings as sb')
            ->select(
                'sb.id',
                'sbdt.name as display_type_name',
                'sbt.name as sign_type_name',
                'sb.display_type_id',
                'sb.sign_type_id',
                'sb.fee',
                'sb.is_active'
            )
            ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
            ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
            ->orderBy('sb.id','desc')
            ->first();
            $this->signage_billing = [
                'id' => $edit->id,
                'display_type_id' => $edit->display_type_id,
                'sign_type_id' => $edit->sign_type_id,
                'fee' => $edit->fee,
                'is_active' => $edit->is_active,
            ];
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has added a signage billing with display type of '.$edit->display_type_name.', with sign type of '.$edit->sign_type_name.' and fee of '.$edit->fee,
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function edit($id,$modal_id){
        $edit =  DB::table('signage_billings as sb')
            ->select(
                'sb.id',
                'sbdt.name as display_type_name',
                'sbt.name as sign_type_name',
                'sb.display_type_id',
                'sb.sign_type_id',
                'sb.fee',
                'sb.is_active'
            )
            ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
            ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
            ->where('sb.id','=',$id)
            ->first();
        $this->signage_billing = [
            'id' => $edit->id,
            'display_type_id' => $edit->display_type_id,
            'sign_type_id' => $edit->sign_type_id,
            'fee' => $edit->fee,
            'is_active' => $edit->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        if(floatval($this->signage_billing['fee'])<=0){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Fee must be greater than 0!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->signage_billing['display_type_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select category!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->signage_billing['sign_type_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select section!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(DB::table('signage_billings')
            ->where('id','=',$id)
            ->update([
                'sign_type_id' => $this->signage_billing['sign_type_id'],
                'display_type_id' => $this->signage_billing['display_type_id'],
                'fee'=>$this->signage_billing['fee']
            ])){
            }
        $this->dispatch('swal:redirect',
            position         									: 'center',
            icon              									: 'success',
            title             									: 'Successfully updated!',
            showConfirmButton 									: 'true',
            timer             									: '1000',
            link              									: '#'
        );
        $edit =  DB::table('signage_billings as sb')
            ->select(
                'sb.id',
                'sbdt.name as display_type_name',
                'sbt.name as sign_type_name',
                'sb.display_type_id',
                'sb.sign_type_id',
                'sb.fee',
                'sb.is_active'
            )
            ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
            ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
            ->orderBy('sb.id','desc')
            ->where('sb.id','=',$id)
            ->first();
            $this->signage_billing = [
                'id' => $edit->id,
                'display_type_id' => $edit->display_type_id,
                'sign_type_id' => $edit->sign_type_id,
                'fee' => $edit->fee,
                'is_active' => $edit->is_active,
            ];
        DB::table('activity_logs')
        ->insert([
            'created_by' => $this->activity_logs['created_by'],
            'inspector_team_id' => $this->activity_logs['inspector_team_id'],
            'log_details' => 'has edited a signage billing with display type of '.$edit->display_type_name.', with sign type of '.$edit->sign_type_name.' and fee of '.$edit->fee,
        ]);
        $this->dispatch('openModal',$modal_id);
    }


    public function save_deactivate($id,$modal_id){
        if(
            DB::table('signage_billings')
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
            $edit =  DB::table('signage_billings as sb')
            ->select(
                'sb.id',
                'sbdt.name as display_type_name',
                'sbt.name as sign_type_name',
                'sb.display_type_id',
                'sb.sign_type_id',
                'sb.fee',
                'sb.is_active'
            )
            ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
            ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
            ->orderBy('sb.id','desc')
            ->where('sb.id','=',$id)
            ->first();
            $this->signage_billing = [
                'id' => $edit->id,
                'display_type_id' => $edit->display_type_id,
                'sign_type_id' => $edit->sign_type_id,
                'fee' => $edit->fee,
                'is_active' => $edit->is_active,
            ];
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deactivated a signage billing with display type of '.$edit->display_type_name.', with sign type of '.$edit->sign_type_name.' and fee of '.$edit->fee,
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('signage_billings')
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
            $edit =  DB::table('signage_billings as sb')
            ->select(
                'sb.id',
                'sbdt.name as display_type_name',
                'sbt.name as sign_type_name',
                'sb.display_type_id',
                'sb.sign_type_id',
                'sb.fee',
                'sb.is_active'
            )
            ->join('signage_billing_types as sbt','sbt.id','sb.sign_type_id')
            ->join('signage_billing_display_types as sbdt','sbdt.id','sb.display_type_id')
            ->orderBy('sb.id','desc')
            ->where('sb.id','=',$id)
            ->first();
            $this->signage_billing = [
                'id' => $edit->id,
                'display_type_id' => $edit->display_type_id,
                'sign_type_id' => $edit->sign_type_id,
                'fee' => $edit->fee,
                'is_active' => $edit->is_active,
            ];
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has activated a signage billing with display type of '.$edit->display_type_name.', with sign type of '.$edit->sign_type_name.' and fee of '.$edit->fee,
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
}
