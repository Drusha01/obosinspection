<?php

namespace App\Livewire\Admin\Administrator\Request\Response;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Response extends Component
{
    public $title = "Request Response";
    public function mount(Request $request,$response,$hash){
        if($response == 'accept'){
            // accept
        }else if($response == 'decline'){
            // decline 
        }
    }
    public function render()
    {
        return view('livewire.admin.administrator.request.response.response')
        ->layout('components.layouts.guest',[
            'title'=>$this->title]);
    }
}
