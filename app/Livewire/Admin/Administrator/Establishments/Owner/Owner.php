<?php

namespace App\Livewire\Admin\Administrator\Establishments\Owner;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Owner extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Owners";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'first_name','active'=> true,'name'=>'Firstname'],
        ['column_name'=> 'middle_name','active'=> true,'name'=>'Middlename'],
        ['column_name'=> 'suffix','active'=> true,'name'=>'Suffix'],
        ['column_name'=> 'contact_number','active'=> true,'name'=>'Contact #'],
        ['column_name'=> 'email','active'=> true,'name'=>'Email'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $person = [
        'id' => NULL,
        'person_type_id' => 2,
        'brgy_id' => 34717,
        'first_name' => NULL,
        'middle_name' => NULL,
        'last_name' => NULL,
        'suffix' => NULL,
        'contact_number' => NULL,
        'email' => NULL,
        'img_url' => NULL,
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
    public function render()
    {
        $table_data = DB::table('persons as p')
        ->select(
            'p.id',
            'p.first_name',
            'p.middle_name',
            'p.last_name',
            'p.suffix',
            'p.contact_number',
            'p.email',
            'p.img_url',
            'p.is_active',
            "p.date_created",
            "p.date_updated",
        )
        ->join('person_types as pt','pt.id','p.person_type_id')
        ->where('pt.name','=','Owner')
        ->orderBy('id','desc')
        ->paginate(10);
        return view('livewire.admin.administrator.establishments.owner.owner',[
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
            'person_type_id' => 2,
            'brgy_id' => 34717,
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
                'log_details' => 'has added an owner with full name of '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function edit($id,$modal_id){
        if($edit = DB::table('persons as p')
        ->select(
            'p.id',
            'p.first_name',
            'p.middle_name',
            'p.last_name',
            'p.suffix',
            'p.contact_number',
            'p.email',
            'p.img_url',
            'p.is_active',
            "p.date_created",
            "p.date_updated",
        )
        ->join('person_types as pt','pt.id','p.person_type_id')
        ->where('pt.name','=','Owner')
        ->where('p.id','=',$id)
        ->first()){
            $this->person = [
                'id' => $edit->id,
                'first_name' => $edit->first_name,
                'middle_name' => $edit->middle_name,
                'last_name' => $edit->last_name,
                'suffix' => $edit->suffix,
                'contact_number' => $edit->contact_number,
                'email' => $edit->email,
                'img_url' => NULL,
            ];
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_edit($id,$modal_id){
        $edit = DB::table('persons as p')
            ->select(
                'p.id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.contact_number',
                'p.email',
                'p.img_url',
                'p.is_active',
                "p.date_created",
                "p.date_updated",
            )
            ->join('person_types as pt','pt.id','p.person_type_id')
            ->where('pt.name','=','Owner')
            ->where('p.id','=',$id)
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
            if(DB::table('persons')
                ->where('id','<>',$id)
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
        if(DB::table('persons')
            ->where('id','=',$id)
            ->update([
                'first_name' => $this->person['first_name'],
                'middle_name' => $this->person['middle_name'],
                'last_name' => $this->person['last_name'],
                'suffix' => $this->person['suffix'],
                'contact_number' => $this->person['contact_number'],
                'email' => $this->person['email'],
                'img_url' => $person['img_url'],
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
                'log_details' => 'has edited an owner with full name of '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
            ]);
            $this->dispatch('openModal',$modal_id);
    }
    public function save_deactivate($id,$modal_id){
        if(
            DB::table('persons')
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
                'log_details' => 'has deactivated an owner with full name of '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('persons')
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
                'log_details' => 'has activated an owner with full name of '.$this->person['first_name'].' '.$this->person['middle_name'].' '.$this->person['last_name'].' '.$this->person['suffix'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
}
