<?php

namespace App\Livewire\Authentication;

use Livewire\Component;

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
    public function login(){
        dd($this->user);
    }
}
