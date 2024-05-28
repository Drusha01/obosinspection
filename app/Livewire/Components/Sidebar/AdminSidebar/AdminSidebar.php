<?php

namespace App\Livewire\Components\Sidebar\AdminSidebar;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSidebar extends Component
{
    public function render(Request $request)
    {
        $session = $request->session()->all();
        $user_details = DB::table('users as u')
                ->select(
                    'u.id',
                    'u.password',
                    'u.username',
                    'r.name as role_name',
                    )
                ->where('u.id','=',$session['id'])
                ->join('roles as r','u.role_id','r.id')
                ->first();
        return view('livewire.components.sidebar.admin-sidebar.admin-sidebar',[
            'user_details'=>$user_details
        ]);
    }
}
