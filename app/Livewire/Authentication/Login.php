<?php

namespace App\Livewire\Authentication;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Login extends Component
{
    public $title = "Login";
    public $user = [
        'username'=> NULL,
        'password'=>NULL,
    ];
    public function render()
    {
        return view('livewire.authentication.login')
        ->layout('components.layouts.guest',[
            'title'=>$this->title]);
    }
    public function login(Request $request){
        $data = $request->session()->all();
        if(!isset($data['user_id'])){ 
            if(!strlen($this->user['username'])){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Username field required!',
                    showConfirmButton 									: 'true',
                    timer             									: '1500',
                    link              									: 'admin/dashboard'
                );
                return ;
            }
            if(!strlen($this->user['username'])){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Password field required!',
                    showConfirmButton 									: 'true',
                    timer             									: '1500',
                    link              									: 'admin/dashboard'
                );
                return ;
            }
            $user_details = DB::table('users as u')
                ->select(
                    'u.id',
                    'u.password',
                    'u.username',
                    'r.name as role_name',
                    )
                ->where('u.username','=',$this->user['username'])
                ->join('roles as r','u.role_id','r.id')
                ->first();
            if( $user_details && password_verify($this->user['password'],$user_details->password)){
                $request->session()->regenerate();
                $request->session()->put('id', $user_details->id);
                $this->dispatch('swal:redirect',
                    position          									: 'center',
                    icon              									: 'success',
                    title            									: 'Welcome back '.$user_details->role_name.' !',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '/'
                );
                
            }else{
                $this->dispatch('swal:redirect',
                    position          									: 'center',
                    icon              									: 'warning',
                    title            									: 'Invalid credentials!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
            }
        }else{
            return redirect('/');
        }
    }
}
