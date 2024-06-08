<?php

namespace App\Livewire\Admin\Administrator\Violations;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Violations extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Violations";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'description','active'=> true,'name'=>'Description name'],
        ['column_name'=> 'category_name','active'=> true,'name'=>'Category'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $categories = [];
    public $violation = [
        'id'=> NULL,
        'description'=>NULL,
        'category_id'=>NULL,
        'is_active'=>NULL,
    ];

    public $activity_logs = [
        'created_by' => NULL,
        'inspector_team_id' => NULL,
        'log_details' => NULL,
    ];
    public function booted(Request $request){
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
    public function mount(){
        $this->categories = DB::table('categories')
        ->where('is_active','=',1)
        ->get()
        ->toArray();
    }
    public function render()
    {
        $table_data = DB::table('violations as v')
            ->select(
                'v.id',
                'description',
                'c.name as category_name',
                'v.is_active'
            )
            ->join('categories as c','v.category_id','c.id')
            ->orderBy('id','desc')
            ->paginate(10);

        return view('livewire.admin.administrator.violations.violations',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }

    public function add($modal_id){
        $this->violation = [
            'id'=> NULL,
            'category_id'=>NULL,
            'description'=>NULL,
            'is_active'=>NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(!strlen($this->violation['description'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter violation description!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $edit = DB::table('violations')
                ->where('description','=',$this->violation['description'])
                ->first();
            if($edit){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Violation name exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(!intval($this->violation['category_id'])){
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
        if(DB::table('violations')
            ->insert([
                'description'=>$this->violation['description'],
                'category_id'=>$this->violation['category_id']
            ])){
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
                'log_details' => 'has added a violation with the description of '.$this->violation['description'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }

    public function edit($id,$modal_id){
        $edit = DB::table('violations')
            ->where('id','=',$id)
            ->first();
        $this->violation = [
            'id'=> $edit->id,
            'category_id'=>$edit->category_id,
            'description'=>$edit->description,
            'is_active'=>$edit->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        if(!strlen($this->violation['description'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter violation description!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $edit = DB::table('violations')
                ->where('id','<>',$id)
                ->where('description','=',$this->violation['description'])
                ->first();
            if($edit){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Violation name exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(!intval($this->violation['category_id'])){
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
        if(DB::table('violations')
            ->where('id','=',$id)
            ->update([
                'description'=>$this->violation['description'],
                'category_id'=>$this->violation['category_id']
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
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has edited a violation with the description of '.$this->violation['description'],
            ]);
            $this->dispatch('openModal',$modal_id);
    }
    public function save_deactivate($id,$modal_id){
        if(
            DB::table('violations')
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
                    'log_details' => 'has deactivated a violation with the description of '.$this->violation['description'],
                ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('violations')
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
                'log_details' => 'has activated a violation with the description of '.$this->violation['description'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }

}
