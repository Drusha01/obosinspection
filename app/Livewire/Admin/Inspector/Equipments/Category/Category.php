<?php

namespace App\Livewire\Admin\Inspector\Equipments\Category;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Category extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Category";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Category name'],
    ];
    public $category = [
        'id'=> NULL,
        'img_url'=>NULL,
        'is_active'=>NULL,
        'name'=>NULL
    ];
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
    public $search = [
        'search'=> NULL,
        'search_prev'=> NULL,
    ];

    public $table_filter;
    public function save_filter(Request $request){
        $session = $request->session()->all();
        $table_filter = DB::table('table_filters')
        ->where('id',$this->table_filter['id'])
        ->first();
        if($table_filter){
            DB::table('table_filters')
            ->where('id',$this->table_filter['id'])
            ->update([
                'table_rows'=>$this->table_filter['table_rows'],
                'filter'=>json_encode($this->table_filter['filter']),
            ]);
            $table_filter = DB::table('table_filters')
                ->where('id',$this->table_filter['id'])
                ->first();
            $temp_filter = [];
            foreach (json_decode($table_filter->filter) as $key => $value) {
                array_push($temp_filter,[
                    'column_name'=>$value->column_name,
                    'active'=>$value->active,
                    'name'=>$value->name,
                ]);
            }
            $this->table_filter = [
                'id'=>$table_filter->id,
                'path'=>$table_filter->path,
                'table_rows'=>$table_filter->table_rows,
                'filter'=>$temp_filter,
            ];
        }
        $this->dispatch('swal:redirect',
            position         									: 'center',
            icon              									: 'success',
            title             									: 'Successfully updated!',
            showConfirmButton 									: 'true',
            timer             									: '1000',
            link              									: '#'
        );
    }
    public function mount(Request $request){
        $session = $request->session()->all();
        $table_filter = DB::table('table_filters')
        ->where('user_id',$session['id'])
        ->where('path','=',$request->path())
        ->first();
        if($table_filter){
            $temp_filter = [];
            foreach (json_decode($table_filter->filter) as $key => $value) {
                array_push($temp_filter,[
                    'column_name'=>$value->column_name,
                    'active'=>$value->active,
                    'name'=>$value->name,
                ]);
            }
            $this->table_filter = [
                'id'=>$table_filter->id,
                'path'=>$table_filter->path,
                'table_rows'=>$table_filter->table_rows,
                'filter'=>$temp_filter,
            ];
        }else{
            DB::table('table_filters')
            ->insert([
                'user_id' =>$session['id'],
                'path' =>$request->path(),
                'table_rows' =>10,
                'filter'=> json_encode($this->filter)
            ]);
            $table_filter = DB::table('table_filters')
            ->where('user_id',$session['id'])
            ->where('path','=',$request->path())
            ->first();
            $temp_filter = [];
            foreach (json_decode($table_filter->filter) as $key => $value) {
                array_push($temp_filter,[
                    'column_name'=>$value->column_name,
                    'active'=>$value->active,
                    'name'=>$value->name,
                ]);
            }
            $this->table_filter = [
                'id'=>$table_filter->id,
                'path'=>$table_filter->path,
                'table_rows'=>$table_filter->table_rows,
                'filter'=>$temp_filter,
            ];
        }

        $this->user_info = DB::table('inspector_teams as it')
        ->join('persons as p','p.id','it.team_leader_id')
        ->join('users as u','u.person_id','p.id')
        ->where('u.id','=',$session['id'])
        ->first();
    }
    public function render()
    {
        if($this->search['search'] != $this->search['search_prev']){
            $this->search['search_prev'] = $this->search['search'];
            $this->resetPage();
        }
        $table_data = DB::table('categories')
            ->where('name','like',$this->search['search'] .'%')
            ->orderBy('id','desc')
            ->paginate($this->table_filter['table_rows']);
        return view('livewire.admin.inspector.equipments.category.category',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    
    public function save_image($image_file,$folder_name,$table_name,$column_name){
        if($image_file && file_exists(storage_path().'/app/livewire-tmp/'.$image_file->getfilename())){
            $file_extension =$image_file->getClientOriginalExtension();
            $tmp_name = 'livewire-tmp/'.$image_file->getfilename();
            $size = Storage::size($tmp_name);
            $mime = Storage::mimeType($tmp_name);
            $max_image_size = 20 * 1024*1024; // 5 mb
            $file_extensions = array('image/jpeg','image/png','image/jpg');
            
            if($size<= $max_image_size){
                $valid_extension = false;
                foreach ($file_extensions as $value) {
                    if($value == $mime){
                        $valid_extension = true;
                        break;
                    }
                }
                if($valid_extension){
                    // move
                    $new_file_name = md5($tmp_name).'.'.$file_extension;
                    while(DB::table($table_name)
                    ->where([$column_name=> $new_file_name])
                    ->first()){
                        $new_file_name = md5($tmp_name.rand(1,10000000)).'.'.$file_extension;
                    }
                    if(Storage::move($tmp_name, 'public/content/'.$folder_name.'/'.$new_file_name)){
                        return $new_file_name;
                    }
                }else{
                    $this->dispatch('swal:redirect',
                        position         									: 'center',
                        icon              									: 'warning',
                        title             									: 'Invalid image type!',
                        showConfirmButton 									: 'true',
                        timer             									: '1000',
                        link              									: '#'
                    );
                    return 0;
                }
            }else{
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Image is too large!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            } 
        }
        return 0;
    }
}
