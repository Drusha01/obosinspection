<?php

namespace App\Livewire\Admin\Administrator\Request\AcceptedRequest;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class AcceptedRequest extends Component
{
    public $title = "Accepted Requests";
    public function render()
    {
        return view('livewire.admin.administrator.request.accepted-request.accepted-request')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
