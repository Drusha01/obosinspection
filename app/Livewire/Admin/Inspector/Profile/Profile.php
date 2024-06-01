<?php

namespace App\Livewire\Admin\Inspector\Profile;


use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Profile extends Component
{
    public $title = "Profile";
    public $user_info;
    public function mount(Request $request){
        self::update_user_data($request);
    }
    public function update_user_data(Request $request){
            $session = $request->session()->all();
            if(isset($session['id']) ){
                $user_details = DB::table('users as u')
                    ->select(
                        'u.id as id',
                        'u.username',
                        'p.first_name',
                        'p.middle_name',
                        'p.last_name',
                        'p.img_url',
                        'p.email',
                        'p.suffix',
                        'p.contact_number',
                        'r.name as role_name',
                        "u.date_created",
                        "u.date_updated",
                    )
                    ->join('roles as r', 'r.id','u.role_id')
                    ->join('persons as p','p.id','u.person_id')
                    ->where('u.id','=',$session['id'])
                    ->first();
                    $this->user_info = [
                        'id' => $user_details->id ,
                        'username'=> $user_details->username ,
                        'first_name'=> $user_details->first_name ,
                        'middle_name'=> $user_details->middle_name ,
                        'last_name'=> $user_details->last_name ,
                        'img_url'=> $user_details->img_url ,
                        'email'=> $user_details->email ,
                        'suffix'=> $user_details->suffix ,
                        'contact_number'=> $user_details->contact_number ,
                        'role_name'=> $user_details->role_name ,
                    ];
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
    public function render(){
        return view('livewire.admin.inspector.profile.profile',[
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function save_edit(){
        $person_id = DB::table('users as u')
            ->select('u.person_id')
            ->where('u.id','=',$this->user_info['id'])
            ->first()->person_id;
        DB::table('persons as p')
            ->where('p.id','=',$person_id)
            ->update([
            'id' => $this->user_info['id'] ,
            'first_name'=> $this->user_info['first_name'] ,
            'middle_name'=> $this->user_info['middle_name'] ,
            'last_name'=> $this->user_info['last_name'] ,
            'email'=> $this->user_info['email'] ,
            'suffix'=> $this->user_info['suffix'] ,
            'contact_number'=> $this->user_info['contact_number'] ,
        ]);
        $this->dispatch('swal:redirect',
            position         									: 'center',
            icon              									: 'success',
            title             									: 'Successfully updated!',
            showConfirmButton 									: 'true',
            timer             									: '1000',
            link              									: '#'
        );
    }
}

