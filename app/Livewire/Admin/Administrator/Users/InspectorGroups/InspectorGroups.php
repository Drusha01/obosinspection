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
    public $title = "Inspector groups";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'name','active'=> true,'name'=>'Group Name'],
        ['column_name'=> 'first_name','active'=> true,'name'=>'Firstname'],
        ['column_name'=> 'middle_name','active'=> true,'name'=>'Middlename'],
        ['column_name'=> 'last_name','active'=> true,'name'=>'Lastname'],
        ['column_name'=> 'suffx','active'=> true,'name'=>'Suffix'],
        ['column_name'=> 'work_role_name','active'=> true,'name'=>'Work Role'],
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
    public function mount(){

    }
    public function render()
    {
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
            ->join('person_types as pt','p.person_type_id','pt.id')
            ->whereNull('it.team_leader_id')
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
            ->orderBy('id','desc')
            ->paginate(10);
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
            $this->dispatch('openModal',$modal_id);
        }
    }
   
    public function add_designation($id,$modal_id){
        
        $this->dispatch('openModal',$modal_id);
    }
}
