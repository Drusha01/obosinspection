<?php

namespace App\Livewire\Admin\Administrator\Users\TeamLeaderInspector;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class TeamLeaderInspector extends Component
{
    public $title = "Inspectors team leader";
    public $users_filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'username','active'=> true,'name'=>'Username'],
        ['column_name'=> 'first_name','active'=> true,'name'=>'Firstname'],
        ['column_name'=> 'middle_name','active'=> true,'name'=>'Middlename'],
        ['column_name'=> 'last_name','active'=> true,'name'=>'Lastname'],
        ['column_name'=> 'role_name','active'=> true,'name'=>'Role'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public function render()
    {
        $users_data = DB::table('users as u')
            ->select(
                'u.id as id',
                'u.username',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'r.name as role_name',
                "u.date_created",
                "u.date_updated",
            )
            ->join('roles as r', 'r.id','u.role_id')
            ->join('persons as p','p.id','u.person_id')
            ->where('r.name','=','Administrator')
            ->paginate(10);
        return view('livewire.admin.administrator.users.team-leader-inspector.team-leader-inspector')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
