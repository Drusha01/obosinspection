<?php

namespace App\Livewire\Admin\Administrator\Request\NoresponseRequest;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class NoresponseRequest extends Component
{
    public $title = "No Response Requests";
    public function render()
    {
        return view('livewire.admin.administrator.request.noresponse-request.noresponse-request')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
