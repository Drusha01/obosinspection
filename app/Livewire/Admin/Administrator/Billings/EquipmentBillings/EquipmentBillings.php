<?php

namespace App\Livewire\Admin\Administrator\Billings\EquipmentBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class EquipmentBillings extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Equipment billings";

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'capacity','active'=> true,'name'=>'Capacity'],
        ['column_name'=> 'category_name','active'=> true,'name'=>'Category'],
        ['column_name'=> 'section_name','active'=> true,'name'=>'Section'],
        ['column_name'=> 'fee','active'=> true,'name'=>'Fee'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $equipment_billing = [
        'id' => NULL,
        'category_id' => NULL,
        'section_id' => NULL,
        'capacity' => NULL,
        'fee' => NULL,
        'is_active' => NULL,
    ];

    public $categories;

    
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
        $session = $request->session()->all();
       
        $this->categories = DB::table('categories')
            ->where('is_active','=',1)
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
    }
    public function render()
    {
        if($this->search['search'] != $this->search['search_prev']){
            $this->search['search_prev'] = $this->search['search'];
            $this->resetPage();
        }

        if($this->equipment_billing['category_id']){
            $equipment_billing_sections = DB::table('equipment_billing_sections')
                ->where('is_active','=',1)
                ->where('category_id','=',$this->equipment_billing['category_id'])
                ->get()
                ->toArray();
        }else{
            $equipment_billing_sections = [];
        }
        
        $table_data = DB::table('equipment_billings as eb')
            ->select(
                'eb.id' ,
                'eb.section_id' ,
                'eb.fee' ,
                'ebs.name as section_name' ,
                'c.name as category_name',
                'eb.category_id',
                'eb.capacity',
                'eb.is_active' ,
            )
            ->join('categories as c','c.id','eb.category_id')
            ->join('equipment_billing_sections as ebs','ebs.id','eb.section_id')
            ->where('eb.capacity','like',$this->search['search'] .'%')
            ->orderBy('eb.id','desc')
            ->paginate($this->table_filter['table_rows']);
        return view('livewire.admin.administrator.billings.equipment-billings.equipment-billings',[
            'table_data'=>$table_data,
            'equipment_billing_sections'=>$equipment_billing_sections
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $this->equipment_billing = [
            'id' => NULL,
            'category_id' => NULL,
            'section_id' => NULL,
            'capacity' => NULL,
            'fee' => NULL,
            'is_active' => NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(floatval($this->equipment_billing['fee'])<=0){
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
        if(!intval($this->equipment_billing['category_id'])){
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
        if(!intval($this->equipment_billing['section_id'])){
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
        
        if(DB::table('equipment_billings')
            ->insert([
                'category_id' => $this->equipment_billing['category_id'],
                'section_id' => $this->equipment_billing['section_id'],
                'capacity'=>$this->equipment_billing['capacity'],
                'fee'=>$this->equipment_billing['fee']
            ])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $equipment_billing_sections = DB::table('equipment_billing_sections as ebs')
            ->select(
                'c.name as category_name',
                'ebs.name as section_name'
            )
            ->join('categories as c','c.id','ebs.category_id')
            ->where('ebs.id','=',$this->equipment_billing['section_id'])
            ->first();
            
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has added a equipment billing with section of '.$equipment_billing_sections->section_name.', category of '.$equipment_billing_sections->category_name.' and a capacity of '.$this->equipment_billing['capacity'].' with the fee of '.$this->equipment_billing['fee'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function edit($id,$modal_id){
        $edit =  DB::table('equipment_billings as eb')
            ->select(
                'eb.id' ,
                'eb.section_id' ,
                'eb.fee' ,
                'ebs.name as section_name' ,
                'c.name as category_name',
                'eb.category_id',
                'eb.capacity',
                'eb.is_active' ,
            )
            ->join('categories as c','c.id','eb.category_id')
            ->join('equipment_billing_sections as ebs','ebs.id','eb.section_id')
            ->where('eb.id','=',$id)
            ->first();
        $this->equipment_billing = [
            'id' => $edit->id,
            'category_id' => $edit->category_id,
            'section_id' => $edit->section_id,
            'capacity' => $edit->capacity,
            'fee' => $edit->fee,
            'is_active' => $edit->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        if(floatval($this->equipment_billing['fee'])<=0){
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
        if(!intval($this->equipment_billing['category_id'])){
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
        if(!intval($this->equipment_billing['section_id'])){
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
        if(DB::table('equipment_billings')
            ->where('id','=',$id)
            ->update([
                'category_id' => $this->equipment_billing['category_id'],
                'section_id' => $this->equipment_billing['section_id'],
                'capacity'=>$this->equipment_billing['capacity'],
                'fee'=>$this->equipment_billing['fee']
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
        $equipment_billing_sections = DB::table('equipment_billing_sections as ebs')
        ->select(
            'c.name as category_name',
            'ebs.name as section_name'
        )
        ->join('categories as c','c.id','ebs.category_id')
        ->where('ebs.id','=',$this->equipment_billing['section_id'])
        ->first();
        
        DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has edited a equipment billing with section of '.$equipment_billing_sections->section_name.', category of '.$equipment_billing_sections->category_name.' and a capacity of '.$this->equipment_billing['capacity'].' with the fee of '.$this->equipment_billing['fee'],
            ]);
        $this->dispatch('openModal',$modal_id);
    }

    public function save_deactivate($id,$modal_id){
        if(
            DB::table('equipment_billings')
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
            $equipment_billing_sections = DB::table('equipment_billing_sections as ebs')
            ->select(
                'c.name as category_name',
                'ebs.name as section_name'
            )
            ->join('categories as c','c.id','ebs.category_id')
            ->where('ebs.id','=',$this->equipment_billing['section_id'])
            ->first();
            
            DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has deactivated a equipment billing with section of '.$equipment_billing_sections->section_name.', category of '.$equipment_billing_sections->category_name.' and a capacity of '.$this->equipment_billing['capacity'].' with the fee of '.$this->equipment_billing['fee'],
                ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('equipment_billings')
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
            $equipment_billing_sections = DB::table('equipment_billing_sections as ebs')
            ->select(
                'c.name as category_name',
                'ebs.name as section_name'
            )
            ->join('categories as c','c.id','ebs.category_id')
            ->where('ebs.id','=',$this->equipment_billing['section_id'])
            ->first();
            
            DB::table('activity_logs')
                ->insert([
                    'created_by' => $this->activity_logs['created_by'],
                    'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                    'log_details' => 'has activated a equipment billing with section of '.$equipment_billing_sections->section_name.', category of '.$equipment_billing_sections->category_name.' and a capacity of '.$this->equipment_billing['capacity'].' with the fee of '.$this->equipment_billing['fee'],
                ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
}

