<?php

namespace App\Livewire\Admin\Administrator\Billings\BuildingBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BuildingBillings extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Building billings";

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'property_attribute','active'=> true,'name'=>'Prty Attr'],
        ['column_name'=> 'section_name','active'=> true,'name'=>'Section'],
        ['column_name'=> 'fee','active'=> true,'name'=>'Fee'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $building_billing = [
        'id' => NULL,
        'section_id' => NULL,
        'property_attribute' => NULL,
        'fee' => NULL,
        'is_active' => NULL,
    ];

    public $building_billing_sections;

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
        $this->building_billing_sections = DB::table('building_billing_sections')
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

        $table_data = DB::table('building_billings as bb')
            ->select(
                'bb.id' ,
                'bb.section_id' ,
                'bbs.name as section_name' ,
                'bb.property_attribute' ,
                'bb.fee' ,
                'bb.is_active' ,
            )
            ->join('building_billing_sections as bbs','bbs.id','bb.section_id')
            ->where('bb.property_attribute','like',$this->search['search'] .'%')
            ->orderBy('bb.id','desc')
            ->paginate($this->table_filter['table_rows']);
        return view('livewire.admin.administrator.billings.building-billings.building-billings',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $this->building_billing = [
            'id'=> NULL,
            'section_id' => NULL,
            'property_attribute' => NULL,
            'fee'=>NULL,
            'is_active'=>NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(floatval($this->building_billing['fee'])<=0){
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
        if(!strlen($this->building_billing['property_attribute'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter property attribute!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(DB::table('building_billings')
            ->insert([
                'section_id' => $this->building_billing['section_id'],
                'property_attribute'=>$this->building_billing['property_attribute'],
                'fee'=>$this->building_billing['fee']
            ])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );

            $building_billing_sections = DB::table('building_billing_sections')
            ->where('id','=',$this->building_billing['section_id'])
            ->first();
            
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has added a building billing with section of '.$building_billing_sections->name.' and an attribute of '.$this->building_billing['property_attribute'].' with the fee of '.$this->building_billing['fee'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function edit($id,$modal_id){
        $edit = DB::table('building_billings as bb')
            ->select(
                'bb.id' ,
                'bb.section_id' ,
                'bbs.name as section_name' ,
                'bb.property_attribute' ,
                'bb.fee' ,
                'bb.is_active' ,
            )
            ->join('building_billing_sections as bbs','bbs.id','bb.section_id')
            ->where('bb.id','=',$id)
            ->first();
        $this->building_billing = [
            'id'=> $edit->id,
            'section_id' => $edit->section_id,
            'property_attribute' => $edit->property_attribute,
            'fee'=>$edit->fee,
            'is_active'=>$edit->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        if(floatval($this->building_billing['fee'])<=0){
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
        if(!strlen($this->building_billing['property_attribute'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter property attribute!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(DB::table('building_billings')
            ->where('id','=',$id)
            ->update([
                'section_id' => $this->building_billing['section_id'],
                'property_attribute'=>$this->building_billing['property_attribute'],
                'fee'=>$this->building_billing['fee']
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
        $building_billing_sections = DB::table('building_billing_sections')
        ->where('id','=',$this->building_billing['section_id'])
        ->first();
        
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has edited a building billing with section of '.$building_billing_sections->name.' and an attribute of '.$this->building_billing['property_attribute'].' with the fee of '.$this->building_billing['fee'],
            ]);
        $this->dispatch('openModal',$modal_id);
    }

    public function save_deactivate($id,$modal_id){
        if(
            DB::table('building_billings')
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
            $building_billing_sections = DB::table('building_billing_sections')
            ->where('id','=',$this->building_billing['section_id'])
            ->first();
            
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deactivated a building billing with section of '.$building_billing_sections->name.' and an attribute of '.$this->building_billing['property_attribute'].' with the fee of '.$this->building_billing['fee'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('building_billings')
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
            $building_billing_sections = DB::table('building_billing_sections')
            ->where('id','=',$this->building_billing['section_id'])
            ->first();
            
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has activated a building billing with section of '.$building_billing_sections->name.' and an attribute of '.$this->building_billing['property_attribute'].' with the fee of '.$this->building_billing['fee'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
}

