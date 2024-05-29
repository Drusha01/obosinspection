<?php

namespace App\Livewire\Admin\Administrator\Users\TeamLeaderInspector;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class TeamLeaderInspector extends Component
{
    public $title = "Inspectors team leader";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'username','active'=> true,'name'=>'Username'],
        ['column_name'=> 'first_name','active'=> true,'name'=>'Firstname'],
        ['column_name'=> 'middle_name','active'=> true,'name'=>'Middlename'],
        ['column_name'=> 'last_name','active'=> true,'name'=>'Lastname'],
        ['column_name'=> 'role_name','active'=> true,'name'=>'Role'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $person = [
        'id' => NULL,
        'new'=>true,
        'inspector_id'=>NULL,
        'person_id'=>NULL,
        'person_type_id' => 1,
        'brgy_id' => NULL,
        'first_name' => NULL,
        'middle_name' => NULL,
        'last_name' => NULL,
        'suffix' => NULL,
        'contact_number' => NULL,
        'email' => NULL,
        'img_url' => NULL,
    ];
    public $inspectors;
    public $brgy;
    public function mount(){
        $this->inspectors = DB::table('users as u')
        ->select(
            'u.id as id',
            'u.username',
            'u.is_active',
            'p.first_name',
            'p.middle_name',
            'p.last_name',
            'p.suffix',
            'p.img_url',
            'r.name as role_name',
            "u.date_created",
            "u.date_updated",
        )
        ->join('roles as r', 'r.id','u.role_id')
        ->join('persons as p','p.id','u.person_id')
        ->where('r.name','=','Inspector')
        ->where('u.is_active','=',1)
        ->get()
        ->toArray();

        $city_mun = DB::table('citymun')
            ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
            ->first();
        $this->brgy = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->get()
            ->toArray();
    }
    public function render()
    {
        $table_data = DB::table('users as u')
            ->select(
                'u.id as id',
                'u.username',
                'u.is_active',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.img_url',
                'r.name as role_name',
                "u.date_created",
                "u.date_updated",
            )
            ->join('roles as r', 'r.id','u.role_id')
            ->join('persons as p','p.id','u.person_id')
            ->where('r.name','=','Inspector Team Leader')
            ->paginate(10);
        return view('livewire.admin.administrator.users.team-leader-inspector.team-leader-inspector',[
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
    public function add($modal_id){
        $this->person = [
            'id' => NULL,
            'new'=>true,
            'inspector_id'=>NULL,
            'person_id'=>NULL,
            'person_type_id' => 1,
            'brgy_id' => NULL,
            'first_name' => NULL,
            'middle_name' => NULL,
            'last_name' => NULL,
            'suffix' => NULL,
            'contact_number' => NULL,
            'email' => NULL,
            'img_url' => NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add(){
        dd($this->person);
        if($this->person['new']){

        }else{

        }
    }
}
