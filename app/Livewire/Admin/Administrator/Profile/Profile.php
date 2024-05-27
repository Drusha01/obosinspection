<?php

namespace App\Livewire\Admin\Administrator\Profile;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Profile extends Component
{
    public $title = "Profile";
    public function render()
    {
        return view('livewire.admin.administrator.profile.profile')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
