<?php

namespace App\Livewire\Admin\Administrator\Request\GenerateRequest;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class GenerateRequest extends Component
{
    public $title = "Generate Requests";
    public function render()
    {
        return view('livewire.admin.administrator.request.generate-request.generate-request')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
