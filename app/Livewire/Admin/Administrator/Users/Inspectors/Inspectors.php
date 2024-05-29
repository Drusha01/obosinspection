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
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'username','active'=> true,'name'=>'Username'],
        ['column_name'=> 'first_name','active'=> true,'name'=>'Firstname'],
        ['column_name'=> 'middle_name','active'=> true,'name'=>'Middlename'],
        ['column_name'=> 'last_name','active'=> true,'name'=>'Lastname'],
        ['column_name'=> 'work_role_name','active'=> true,'name'=>'Work Role'],
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
        'current_password'=>NULL,
        'password'=>NULL,
        'cpassword'=> NULL,
    ];
    public function mount(){
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
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                'p.img_url',
                "u.date_created",
                "u.date_updated",
                )
            ->join('persons as p','p.id','u.person_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('pt.name','=','Inspector')
            ->orderBy('id','desc')
            ->paginate(10);
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
        $person = [
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
        ];
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
            ])){
            $person_details = DB::table('persons')
                ->where('email','=',$this->person['email'])
                ->first();

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
                title             									: 'Please enter emailname!',
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
                ->where('p.id','<>',$id)
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
        $person['img_url'] = $edit->img_url;
        if($this->person['img_url']){
            if($this->person['img_url']){
                $person['img_url'] = self::save_image($this->person['img_url'],'profile','persons','img_url');
                if($person['img_url'] == 0){
                    return;
                }
            } 
        }
        if(DB::table('persons as p')
            ->where('p.id','=',$id)
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
            ])){
         
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'Successfully updated!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            $this->dispatch('openModal',$modal_id);
        }
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
            $this->dispatch('openModal',$modal_id);
        }
    }
}
