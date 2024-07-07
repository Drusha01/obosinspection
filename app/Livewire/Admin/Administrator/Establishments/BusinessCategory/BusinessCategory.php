<?php

namespace App\Livewire\Admin\Administrator\Establishments\BusinessCategory;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BusinessCategory extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Business Categories";
    public $request_lists = [];
    public $business_category_list = [];
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'name','active'=> true,'name'=>'Description'],
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
        $table_data = DB::table('business_category as bt')
            ->where('bt.name','like',$this->search['search'] .'%')
            ->orderBy('id','desc')
            ->paginate($this->table_filter['table_rows']);
        return view('livewire.admin.administrator.establishments.business-category.business-category',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    
    public function add($modal_id){
        $this->business_category = [
            'id' => NULL,
            'name' => NULL,
            'is_active'=> NULL,
        ];
        $this->dispatch('openModal',$modal_id);   
    }
    public function save_add($modal_id){
        if(strlen($this->business_category['name'])<=0){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter description!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(DB::table('business_category')
            ->where('name','=',$this->business_category['name'])
            ->first()){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Description Exist!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(
            DB::table('business_category')
                ->insert([
                    'name'=>$this->business_category['name']
                ])
        ){
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has added a business category '.$this->business_category['name'],
            ]);
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $this->dispatch('openModal',$modal_id);  
            return 0;
        }
    }
    public function edit($id,$modal_id){
        if($edit = DB::table('business_category')
            ->where('id','=',$id)
            ->first()){
            $this->business_category = [
                'id' => $edit->id,
                'name' => $edit->name,
                'is_active'=> $edit->is_active,
            ];
            $this->dispatch('openModal',$modal_id); 
        }
    }
    public function save_edit($id,$modal_id){
        if(strlen($this->business_category['name'])<=0){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter description!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(DB::table('business_category')
            ->where('id','<>',$id)
            ->where('name','=',$this->business_category['name'])
            ->first()){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Description Exist!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(
            DB::table('business_category')
                ->where('id','=',$id)
                ->update([
                    'name'=>$this->business_category['name']
                ])
        ){
        }
        $edit = DB::table('business_category')
        ->where('id','=',$id)
        ->first();
        DB::table('activity_logs')
        ->insert([
            'created_by' => $this->activity_logs['created_by'],
            'inspector_team_id' => $this->activity_logs['inspector_team_id'],
            'log_details' => 'has edited a business category '.$edit->name,
        ]);
        $this->dispatch('swal:redirect',
            position         									: 'center',
            icon              									: 'success',
            title             									: 'Successfully updated!',
            showConfirmButton 									: 'true',
            timer             									: '1000',
            link              									: '#'
        );
        $this->dispatch('openModal',$modal_id);  
        return 0;
    }
    public function save_deactivate($id,$modal_id){
        if(
            DB::table('business_category')
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
            $edit = DB::table('business_category')
            ->where('id','=',$id)
            ->first();
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deactivated a business category '.$edit->name,
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('business_category')
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
            $edit = DB::table('business_category')
            ->where('id','=',$id)
            ->first();
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has activated a business category '.$edit->name,
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function request_list_modal($modal_id){

        $this->business_category_list = DB::table('business_category')
            ->get()
            ->toArray();
        $this->request_lists = DB::table('request_business_categories as rbc')
            ->select(
                'rbc.id',
                'bc.name',
            )
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
}
