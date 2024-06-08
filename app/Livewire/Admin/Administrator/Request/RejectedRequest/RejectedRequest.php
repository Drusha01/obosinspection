<?php

namespace App\Livewire\Admin\Administrator\Request\RejectedRequest;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class RejectedRequest extends Component
{
    public $title = "Rejected Requests";
    public function render()
    {
        return view('livewire.admin.administrator.request.rejected-request.rejected-request')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
