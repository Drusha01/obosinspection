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
        ['column_name'=> 'section_name','active'=> true,'name'=>'Section'],
    ];
    public $item = [
        'id' => NULL,
        'category_id' => NULL,
        'name' => NULL,
        'section_name' => NULL,
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
    public $activity_logs = [
        'created_by' => NULL,
        'inspector_team_id' => NULL,
        'log_details' => NULL,
    ];
    public function boot(Request $request){
        $session = $request->session()->all();
        $this->activity_logs['created_by'] = $session['id'];
        $user_details = 
            DB::table('users as u')
            ->select(
                'im.member_id',
                'im.inspector_team_id',
                'it.team_leader_id',
                'it.id',
                )
            ->join('persons as p','p.id','u.id')
            ->leftjoin('inspector_members as im','im.member_id','p.id')
            ->leftjoin('inspector_teams as it','it.team_leader_id','p.id')
            ->where('u.id','=',$session['id'])
            ->first();
        if($user_details->member_id){
            $this->activity_logs['inspector_team_id'] = $user_details->member_id;
        }elseif($user_details->team_leader_id){
            $this->activity_logs['inspector_team_id'] = $user_details->team_leader_id;
        }else{
            $this->activity_logs['inspector_team_id'] = 0;
        }
    }

    public function render()
    {
        $table_data = DB::table('items as i')
            ->select(
                'i.id',
                'c.name as category_name',
                'i.name',
                'i.img_url',
                'i.is_active',
                'ebs.name as section_name'
            )
            ->join('categories as c','c.id','i.category_id')
            ->join('equipment_billing_sections as ebs','ebs.id','i.category_id')
            ->orderBy('id','desc')
            ->paginate(10);
        return view('livewire.admin.inspector.equipments.items.items',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
