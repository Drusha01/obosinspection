<?php

namespace App\Livewire\Admin\Administrator\Profile;

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
    public function render(){
        return view('livewire.admin.administrator.profile.profile',[
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
