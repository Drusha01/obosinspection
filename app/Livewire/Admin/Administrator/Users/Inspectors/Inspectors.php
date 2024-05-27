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
    public $users_filter = [
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
    public function mount(){
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
        $users_data = DB::table('users as u')
            ->select(
                'u.id as id',
                'u.username',
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
            ->where('r.name','=','Inspector')
            ->paginate(10);
        return view('livewire.admin.administrator.users.inspectors.inspectors',[
            'users_data'=>$users_data
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
    public function add_person($modal_id){
        $this->person = [
            'id' => NULL,
            'person_type_id' => 1,
            'brgy_id' => NULL,
            'username'=>NULL,
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
    public function save_add_person($modal_id){
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
                        dd($i);
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
            if(DB::table('persons')
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
}
