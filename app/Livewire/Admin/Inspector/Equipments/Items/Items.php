<?php

namespace App\Livewire\Admin\Inspector\Equipments\Items;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Items extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Items";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'category_name','active'=> true,'name'=>'Category name'],
        ['column_name'=> 'name','active'=> true,'name'=>'Item name'],
        ['column_name'=> 'section','active'=> true,'name'=>'Section'],
    ];
    public $item = [
        'id' => NULL,
        'category_id' => NULL,
        'name' => NULL,
        'section' => NULL,
        'img_url' => NULL,
        'is_active' => NULL,
    ];
    public $categories;
    public function mount(){
        $this->categories = DB::table('categories')
            ->where('is_active','=',1)
            ->get()
            ->toArray();
    }
    public function render()
    {
        $table_data = DB::table('items as i')
            ->select(
                'i.id',
                'c.name as category_name',
                'i.name',
                'i.section',
                'i.img_url',
                'i.is_active'
            )
            ->join('categories as c','c.id','i.category_id')
            ->orderBy('id','desc')
            ->paginate(10);
        return view('livewire.admin.inspector.equipments.items.items',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
