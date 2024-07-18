<?php

namespace App\Livewire\Components\Header\TopHeader;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopHeader extends Component
{
    public $user_details= [];
    public function mount(Request $request){
        $session = $request->session()->all();
        if(isset($session['id']) ){
            $this->user_details = DB::table('users as u')
                ->select(
                    'u.id as id',
                    'u.username',
                    'p.first_name',
                    'p.middle_name',
                    'p.last_name',
                    'p.img_url',
                    'r.name as role_name',
                    "u.date_created",
                    "u.date_updated",
                )
                ->join('roles as r', 'r.id','u.role_id')
                ->join('persons as p','p.id','u.person_id')
                ->where('u.id','=',$session['id'])
                ->first();
        }
    }
    public function render(Request $request){
        $session = $request->session()->all();
        $user_det = [];
        if($user_det = DB::table('inspector_teams as it')
        ->join('persons as p','p.id','it.team_leader_id')
        ->join('users as u','u.person_id','p.id')
        ->where('u.id','=',$session['id'])
        ->first()){
            $user_det->role_name = "Inspector Team Leader";
          
        }else{
            $user_det = DB::table('users as u')
                ->select(
                    'u.id',
                    'u.password',
                    'u.username',
                    'r.name as role_name',
                    )
                ->where('u.id','=',$session['id'])
                ->join('roles as r','u.role_id','r.id')
                ->first();
        }
        return view('livewire.components.header.top-header.top-header',[
               'user_det'=>$user_det
        ]);
    }
}
