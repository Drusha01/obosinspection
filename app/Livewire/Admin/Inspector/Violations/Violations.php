<?php

namespace App\Livewire\Admin\Inspector\Violations;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Violations extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Violations";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'description','active'=> true,'name'=>'Description name'],
    ];
    public $violation = [
        'id'=> NULL,
        'description'=>NULL,
        'is_active'=>NULL,
    ];
    public function render()
    {
        $table_data = DB::table('violations')
            ->orderBy('id','desc')
            ->paginate(10);

        return view('livewire.admin.inspector.violations.violations',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
