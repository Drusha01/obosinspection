<?php

namespace App\Livewire\Admin\Administrator\Certifications\Certification;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Certification extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Certifications";
    public function render()
    {
        $table_data = [];
        return view('livewire.admin.administrator.certifications.certification.certification',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
