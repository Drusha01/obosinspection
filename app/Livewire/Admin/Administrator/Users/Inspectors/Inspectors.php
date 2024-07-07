<?php

namespace App\Livewire\Admin\Administrator\Users\Inspectors;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Inspectors extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $title = "Inspectors";
    public $brgy;
    public $work_roles;
    public $violation_category = [];
    public $inspector_violation_category = [];
    public $categories = [];
    public $bss_category = [];
    public $inspector_bss_category = [];
    public $inspector_item_category = [];
    public $temp_inspector_category = [];
    public $category_role = [
        'id' => NULL,
        'person_id' => NULL,
        'category_id' => NULL,
    ];
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'signature','active'=> true,'name'=>'E-Signature'],
        ['column_name'=> 'username','active'=> true,'name'=>'Username'],
        ['column_name'=> 'first_name','active'=> true,'name'=>'Firstname'],
        ['column_name'=> 'middle_name','active'=> true,'name'=>'Middlename'],
        ['column_name'=> 'last_name','active'=> true,'name'=>'Lastname'],
        ['column_name'=> 'work_role_name','active'=> true,'name'=>'Work Role'],
        ['column_name'=> 'category_role','active'=> true,'name'=>'Category Role'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $person = [
        'id' => NULL,
        'person_id'=>NULL,
        'person_type_id' => 1,
        'brgy_id' => NULL,
        'work_role_id' => NULL,
        'work_role_id' => NULL,
        'first_name' => NULL,
        'middle_name' => NULL,
        'last_name' => NULL,
        'suffix' => NULL,
        'contact_number' => NULL,
        'email' => NULL,
        'img_url' => NULL,
        'signature' => NULL,
        'current_password'=>NULL,
        'password'=>NULL,
        'cpassword'=> NULL,
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
            ->join('persons as p','p.id','u.person_id')
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
        $city_mun = DB::table('citymun')
            ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
            ->first();
        $this->brgy = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->get()
            ->toArray();
        $this->work_roles = DB::table('work_roles')
            ->where('id','>',2)
            ->where('is_active','=',1)
            ->get()
            ->toArray();
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
    }
    public function render()
    {
        if($this->search['search'] != $this->search['search_prev']){
            $this->search['search_prev'] = $this->search['search'];
            $this->resetPage();
        }
            
        $table_data = DB::table('users as u')
            ->select(
                'u.id as id',
                'u.username',
                'u.is_active',
                'p.id as person_id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.img_url',
                'p.signature',
                "u.date_created",
                "u.date_updated",
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                )
            ->join('persons as p','p.id','u.person_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('pt.name','=','Inspector')
            ->where(DB::raw("CONCAT(p.first_name,' ',p.last_name)"),'like',$this->search['search'] .'%')
            ->orderBy('id','desc')
            ->paginate($this->table_filter['table_rows']);
        return view('livewire.admin.administrator.users.inspectors.inspectors',[
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
            'person_id'=>NULL,
            'person_type_id' => 1,
            'brgy_id' => NULL,
            'work_role_id' => NULL,
            'work_role_id' => NULL,
            'first_name' => NULL,
            'middle_name' => NULL,
            'last_name' => NULL,
            'suffix' => NULL,
            'contact_number' => NULL,
            'email' => NULL,
            'img_url' => NULL,
            'signature' => NULL,
        ];
        $this->category_role = [
            'id' => NULL,
            'person_id' => NULL,
            'category_id' => NULL,
        ];
        $this->violation_category = DB::table('violation_category')
            ->where('is_active','=',1)
            ->get()
            ->toArray();
        $this->temp_inspector_category = [];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(!strlen($this->person['username'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter username!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $temp = DB::table('users as u')
                ->join('roles as r','r.id','u.role_id')
                ->join('persons as p','p.id','u.person_id')
                ->where('r.name','=','Inspector')
                ->where('username','=',$this->person['username'])
                ->first();
            if( $temp){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Username exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(!strlen($this->person['first_name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter firstname!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!strlen($this->person['last_name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter lastname!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->person['brgy_id']) && 
            DB::table('brgy')
                ->where('id','=',$this->person['brgy_id'])
                ->first()
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select barangay!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->person['work_role_id']) && 
            DB::table('work_roles')
                ->where('id','=',$this->person['work_role_id'])
                ->first()
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select work roles!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!strlen($this->person['contact_number'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter contact number!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(strlen($this->person['contact_number']) !=11){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Contact number must be 11 digits!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            for ($i=0; $i < strlen($this->person['contact_number']); $i++) { 
                if($i == 0 ){
                    if(intval($this->person['contact_number'][$i])!= 0 ){
                        $this->dispatch('swal:redirect',
                            position         									: 'center',
                            icon              									: 'warning',
                            title             									: 'First digit must be 0 !',
                            showConfirmButton 									: 'true',
                            timer             									: '1000',
                            link              									: '#'
                        );
                        return 0;
                    }
                }elseif($i == 1  ){
                    if(intval($this->person['contact_number'][$i])!= 9 ){
                        $this->dispatch('swal:redirect',
                            position         									: 'center',
                            icon              									: 'warning',
                            title             									: 'First digit must be 9 !',
                            showConfirmButton 									: 'true',
                            timer             									: '1000',
                            link              									: '#'
                        );
                        return 0;
                    }
                }elseif($i >= 2 ){
                    if((intval($this->person['contact_number'][$i])< 0 || intval($this->person['contact_number'][$i]) > 9)){
                        $this->dispatch('swal:redirect',
                            position         									: 'center',
                            icon              									: 'warning',
                            title             									: 'Invalid contact number !',
                            showConfirmButton 									: 'true',
                            timer             									: '1000',
                            link              									: '#'
                        );
                        return 0;
                    }
                }
            }
        }
        if(!strlen($this->person['email'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter email!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(!filter_var($this->person['email'], FILTER_VALIDATE_EMAIL)) {
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Invalid email!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            if(DB::table('users as u')
                ->join('roles as r','r.id','u.role_id')
                ->join('persons as p','p.id','u.person_id')
                ->where('r.name','=','Inspector')
                ->where('email','=',$this->person['email'])
                ->first()){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Email exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(strlen($this->person['password'])< 8){
            $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Password must be at least 8!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
            return 0;
        }
        if($this->person['cpassword'] != $this->person['password']){
            $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Password doesn\'t match!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
            return 0;
        }
        $person['img_url'] = 'default.png';
        if($this->person['img_url']){
            if($this->person['img_url']){
                $person['img_url'] = self::save_image($this->person['img_url'],'profile','persons','img_url');
                if($person['img_url'] == 0){
                    return;
                }
            } 
        }
        $person['signature'] = NULL;
        if($this->person['signature']){
            if($this->person['signature']){
                $person['signature'] = self::save_image($this->person['signature'],'signature','persons','signature');
                if($person['signature'] == 0){
                    return;
                }
            } 
        }
        if(DB::table('persons')
            ->insert([
                'person_type_id' => $this->person['person_type_id'],
                'brgy_id' => $this->person['brgy_id'],
                'work_role_id' => $this->person['work_role_id'],
                'first_name' => $this->person['first_name'],
                'middle_name' => $this->person['middle_name'],
                'last_name' => $this->person['last_name'],
                'suffix' => $this->person['suffix'],
                'contact_number' => $this->person['contact_number'],
                'email' => $this->person['email'],
                'img_url' => $person['img_url'],
                'signature' => $person['signature'],
            ])){
            $person_details = DB::table('persons')
                ->where('email','=',$this->person['email'])
                ->first();

            foreach ($this->temp_inspector_category as $key => $value) {
                DB::table('inspector_category')
                    ->insert([
                        'person_id' =>  $person_details->id,
                        'category_id' =>  $value->id,
                    ]);
            }
            DB::table('users')
                ->insert([
                    'username'=> $this->person['username'],
                    'password' =>password_hash($this->person['password'], PASSWORD_ARGON2I) ,
                    'role_id'=>1,
                    'person_id'=>$person_details->id
                ]);
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully added!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );

            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has added a new inspector '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }

    public function edit($id,$modal_id){
        if($edit = DB::table('users as u')
            ->select(
                'u.id as id',
                'p.id as person_id',
                'u.username',
                'p.brgy_id',
                'u.is_active',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.email',
                'p.contact_number',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                'p.img_url',
                'p.signature',
                "u.date_created",
                "u.date_updated",
                )
            ->join('persons as p','p.id','u.person_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('pt.name','=','Inspector')
        ->where('u.id','=',$id)
        ->first()){
            $this->person = [
                'id' => $edit->id,
                'person_id' => $edit->person_id,
                'person_type_id' => 1,
                'brgy_id' => $edit->brgy_id,
                'work_role_id' => $edit->work_role_id,
                'username'=>$edit->username,
                'first_name' => $edit->first_name,
                'middle_name' => $edit->middle_name,
                'last_name' => $edit->last_name,
                'suffix' => $edit->suffix,
                'email' => $edit->email,
                'contact_number' => $edit->contact_number,
                'img_url' => NULL,
                'signature' => NULL,
                'current_password'=>NULL,
                'password'=>NULL,
                'cpassword'=> NULL,
            ];
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_edit($id,$modal_id){
        $edit = DB::table('users as u')
        ->select(
            'u.id as id',
            'p.id as person_id',
            'p.brgy_id',
            'u.username',
            'u.is_active',
            'p.first_name',
            'p.middle_name',
            'p.last_name',
            'suffix',
            'p.email',
            'p.contact_number',
            'p.img_url',
            'p.signature',
            'r.name as role_name',
            "u.date_created",
            "u.date_updated",
        )
        ->join('roles as r', 'r.id','u.role_id')
        ->join('persons as p','p.id','u.person_id')
        ->where('r.name','=','Inspector')
        ->where('u.id','=',$id)
        ->first();
        if(!strlen($this->person['first_name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter firstname!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!strlen($this->person['last_name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter lastname!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->person['brgy_id']) && 
            DB::table('brgy')
                ->where('id','=',$this->person['brgy_id'])
                ->first()
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select barangay!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->person['work_role_id']) && 
            DB::table('work_roles')
                ->where('id','=',$this->person['work_role_id'])
                ->first()
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select work roles!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!strlen($this->person['contact_number'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter contact number!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(strlen($this->person['contact_number']) !=11){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Contact number must be 11 digits!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            for ($i=0; $i < strlen($this->person['contact_number']); $i++) { 
                if($i == 0 ){
                    if(intval($this->person['contact_number'][$i])!= 0 ){
                        $this->dispatch('swal:redirect',
                            position         									: 'center',
                            icon              									: 'warning',
                            title             									: 'First digit must be 0 !',
                            showConfirmButton 									: 'true',
                            timer             									: '1000',
                            link              									: '#'
                        );
                        return 0;
                    }
                }elseif($i == 1  ){
                    if(intval($this->person['contact_number'][$i])!= 9 ){
                        $this->dispatch('swal:redirect',
                            position         									: 'center',
                            icon              									: 'warning',
                            title             									: 'First digit must be 9 !',
                            showConfirmButton 									: 'true',
                            timer             									: '1000',
                            link              									: '#'
                        );
                        return 0;
                    }
                }elseif($i >= 2 ){
                    if((intval($this->person['contact_number'][$i])< 0 || intval($this->person['contact_number'][$i]) > 9)){
                        $this->dispatch('swal:redirect',
                            position         									: 'center',
                            icon              									: 'warning',
                            title             									: 'Invalid contact number !',
                            showConfirmButton 									: 'true',
                            timer             									: '1000',
                            link              									: '#'
                        );
                        return 0;
                    }
                }
            }
        }
        if(!strlen($this->person['email'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter email!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(!filter_var($this->person['email'], FILTER_VALIDATE_EMAIL)) {
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Invalid email!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
            $var = DB::table('users as u')
            ->join('roles as r','r.id','u.role_id')
            ->join('persons as p','p.id','u.person_id')
            ->where('r.name','=','Inspector')
            ->where('p.email','=',$this->person['email'])
            ->where('u.person_id','<>',$this->person['person_id'])
            ->first();
            if( $var){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Email exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        $person['img_url'] = $edit->img_url;
        if($this->person['img_url']){
            $person['img_url'] = self::save_image($this->person['img_url'],'profile','persons','img_url');
            if($person['img_url'] == 0){
                return;
            }
        }
        $person['signature'] = $edit->signature;
        if($this->person['signature']){
            $person['signature'] = self::save_image($this->person['signature'],'signature','persons','signature');
            if($person['signature'] == 0){
                return;
            }
        } 
        if(DB::table('persons as p')
            ->where('p.id','=', $edit->person_id)
            ->update([
                'person_type_id' => $this->person['person_type_id'],
                'brgy_id' => $this->person['brgy_id'],
                'work_role_id' => $this->person['work_role_id'],
                'first_name' => $this->person['first_name'],
                'middle_name' => $this->person['middle_name'],
                'last_name' => $this->person['last_name'],
                'suffix' => $this->person['suffix'],
                'contact_number' => $this->person['contact_number'],
                'email' => $this->person['email'],
                'img_url' => $person['img_url'],
                'signature' => $person['signature'],
            ])){
         
            }
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully updated!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has edited an inspector '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
            ]);
            $this->dispatch('openModal',$modal_id);
    }
    public function save_recover_password($id,$modal_id){
        if(strlen($this->person['password'])< 8){
            $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Password must be at least 8!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
            return 0;
        }
        if($this->person['cpassword'] != $this->person['password']){
            $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Password doesn\'t match!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
            return 0;
        }
        if(strlen($this->person['current_password'])< 8){
            $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Password must be at least 8!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
            return 0;
        }

        $edit = DB::table('users as u')
            ->select(
                'u.id as id',
                'u.password as password',
                )
            ->join('persons as p','p.id','u.person_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->where('pt.name','=','Inspector')
            ->where('u.id','=',$id)
            ->first();
        if(password_verify($this->person['current_password'],$edit->password)){
            if($this->person['current_password'] == $this->person['password']){
                $this->dispatch('swal:redirect',
                position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'New password must not be the current password!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }else{
                if(
                    DB::table('users as u')
                    ->where('u.id','=',$id)
                    ->update([
                    'password' =>password_hash($this->person['password'], PASSWORD_ARGON2I) 
                ])){
                    $this->dispatch('swal:redirect',
                        position         									: 'center',
                        icon              									: 'success',
                        title             									: 'Passsword successfully updated!',
                        showConfirmButton 									: 'true',
                        timer             									: '1000',
                        link              									: '#'
                    );
                    $this->dispatch('openModal',$modal_id);
                    DB::table('activity_logs')
                    ->insert([
                        'created_by' => $this->activity_logs['created_by'],
                        'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                        'log_details' => 'has updated a password for inspector '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
                    ]);
                    return 0;
                }
            }
        }else{
            $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Invalid current password!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
            return 0;
        }
    }

    public function save_deactivate($id,$modal_id){
        if(
            DB::table('users')
                ->where('id','=',$id)
                ->update([
                    'is_active'=>0
                ])            
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully updated!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has deactivated inspector '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('users')
                ->where('id','=',$id)
                ->update([
                    'is_active'=>1
                ])            
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully updated!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            DB::table('activity_logs')
            ->insert([
                'created_by' => $this->activity_logs['created_by'],
                'inspector_team_id' => $this->activity_logs['inspector_team_id'],
                'log_details' => 'has activated inspector '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function view_violation_category_role($id,$modal_id){
        $this->category_role = [
            'id' => NULL,
            'person_id' => $id,
            'category_id' => NULL,
        ];
        $this->inspector_violation_category = DB::table('inspector_violation_category as ivc')
            ->select(
                'ivc.id',
                'vc.name',
            )
            ->join('violation_category as vc','vc.id','ivc.category_id')
            ->where('person_id','=',$id)
            ->get()
            ->toArray(); 
        $this->violation_category = DB::table('violation_category')
            ->where('is_active','=',1)
            ->get()
            ->toArray();
        $this->dispatch('openModal',$modal_id);
    }
    public function add_violation_category_role(){
        if(intval($this->category_role['category_id'])){
            $category_role = DB::table('inspector_violation_category')
                ->where('category_id','=',$this->category_role['category_id'])
                ->where('person_id','=',$this->category_role['person_id'])
                ->first();
            if($category_role){

            }else{
                DB::table('inspector_violation_category')
                ->insert([
                    'person_id' => $this->category_role['person_id'],
                    'category_id' => $this->category_role['category_id'],
                ]);
                $this->inspector_violation_category = DB::table('inspector_violation_category as ivc')
                    ->select(
                        'ivc.id',
                        'vc.name',
                    )
                    ->join('violation_category as vc','vc.id','ivc.category_id')
                    ->where('person_id','=',$this->category_role['person_id'])
                    ->get()
                    ->toArray(); 
                $this->violation_category = DB::table('violation_category')
                    ->where('is_active','=',1)
                    ->get()
                    ->toArray();
            }
        }
    }
    public function delete_violation_category_role($id){
        if(DB::table('inspector_violation_category')
            ->where('id','=',$id)
            ->delete()   
        ){
            $this->inspector_violation_category = DB::table('inspector_violation_category as ivc')
                ->select(
                    'ivc.id',
                    'vc.name',
                )
                ->join('violation_category as vc','vc.id','ivc.category_id')
                ->where('person_id','=',$this->category_role['person_id'])
                ->get()
                ->toArray(); 
            $this->violation_category = DB::table('violation_category')
                ->where('is_active','=',1)
                ->get()
                ->toArray();
        }
    }
    public function add_temp_violation_category(){
        $valid = true;
        foreach ($this->temp_inspector_category as $key => $value) {
            if($this->category_role['category_id'] == $value->id){
                $valid = false;
            }
        }
        if($valid){
            $violation_category = DB::table('violation_category')
            ->where('is_active','=',1)
            ->where('id','=',$this->category_role['category_id'])
            ->first();
            array_push($this->temp_inspector_category,$violation_category);
        }
    }
    public function delete_temp_violation_category($id){
        $temp = [];
        foreach ($this->temp_inspector_category as $key => $value) {
            if($id != $value->id){
                array_push($temp,$value);
            }
        }
        $this->temp_inspector_category = $temp;
    }

    

    public function view_item_category_role($id,$modal_id){
        $this->category_role = [
            'id' => NULL,
            'person_id' => $id,
            'category_id' => NULL,
        ];
        $this->inspector_item_category = DB::table('inspector_item_category as ic')
            ->select(
                'ic.id',
                'c.name',
            )
            ->join('categories as c','c.id','ic.category_id')
            ->where('person_id','=',$id)
            ->get()
            ->toArray(); 
        $this->categories = DB::table('categories')
            ->where('is_active','=',1)
            ->get()
            ->toArray();
        $this->dispatch('openModal',$modal_id);
    }
    public function add_item_category_role(){
        if(intval($this->category_role['category_id'])){
            $category_role = DB::table('inspector_item_category')
                ->where('category_id','=',$this->category_role['category_id'])
                ->where('person_id','=',$this->category_role['person_id'])
                ->first();
            if($category_role){

            }else{
                DB::table('inspector_item_category')
                ->insert([
                    'person_id' => $this->category_role['person_id'],
                    'category_id' => $this->category_role['category_id'],
                ]);
                $this->inspector_item_category = DB::table('inspector_item_category as iic')
                    ->select(
                        'iic.id',
                        'c.name',
                    )
                    ->join('categories as c','c.id','iic.category_id')
                    ->where('person_id','=',$this->category_role['person_id'])
                    ->get()
                    ->toArray(); 
                $this->categories = DB::table('categories')
                    ->where('is_active','=',1)
                    ->get()
                    ->toArray();
            }
        }
    }
    public function delete_item_category_role($id){
        if(DB::table('inspector_item_category')
            ->where('id','=',$id)
            ->delete()   
        ){
            $this->inspector_item_category = DB::table('inspector_item_category as iic')
                ->select(
                    'iic.id',
                    'c.name',
                )
                ->join('categories as c','c.id','iic.category_id')
                ->where('person_id','=',$this->category_role['person_id'])
                ->get()
                ->toArray(); 
            $this->categories = DB::table('categories')
                ->where('is_active','=',1)
                ->get()
                ->toArray();
        }
    }

    public function view_bss_category_role($id,$modal_id){
        $this->category_role = [
            'id' => NULL,
            'person_id' => $id,
            'category_id' => NULL,
        ];
        $this->inspector_bss_category = DB::table('inspector_bss_category as ibc')
            ->select(
                'ibc.id',
                'c.name',
            )
            ->join('bss_category as c','c.id','ibc.category_id')
            ->where('person_id','=',$this->category_role['person_id'])
            ->get()
            ->toArray(); 
        $this->bss_category = DB::table('bss_category')
            ->get()
            ->toArray();
        $this->dispatch('openModal',$modal_id);
    }
    public function add_bss_category_role(){
        if(intval($this->category_role['category_id'])){
            $category_role = DB::table('inspector_bss_category')
                ->where('category_id','=',$this->category_role['category_id'])
                ->where('person_id','=',$this->category_role['person_id'])
                ->first();
            if($category_role){

            }else{
                DB::table('inspector_bss_category')
                ->insert([
                    'person_id' => $this->category_role['person_id'],
                    'category_id' => $this->category_role['category_id'],
                ]);
                $this->inspector_bss_category = DB::table('inspector_bss_category as ibc')
                    ->select(
                        'ibc.id',
                        'c.name',
                    )
                    ->join('bss_category as c','c.id','ibc.category_id')
                    ->where('person_id','=',$this->category_role['person_id'])
                    ->get()
                    ->toArray(); 
                $this->bss_category = DB::table('bss_category')
                    ->get()
                    ->toArray();
            }
        }
    }
    public function delete_bss_category_role($id){
        if(DB::table('inspector_bss_category')
            ->where('id','=',$id)
            ->delete()   
        ){
            $this->inspector_bss_category = DB::table('inspector_bss_category as ibc')
                ->select(
                    'ibc.id',
                    'bc.name',
                )
                ->join('bss_category as bc','bc.id','ibc.category_id')
                ->where('person_id','=',$this->category_role['person_id'])
                ->get()
                ->toArray(); 
            $this->bss_category = DB::table('bss_category')
                ->get()
                ->toArray();
        }
    }
}
