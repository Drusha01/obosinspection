<?php

namespace App\Livewire\Admin\InspectorTeamLeader\Establishments\Businesses;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Businesses extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Businesses";
    public $histfilter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
        ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
        ['column_name'=> 'schedule_date','active'=> true,'name'=>'Schedule'],
        ['column_name'=> 'id','active'=> true,'name'=>'Generate'],
    ];
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
        ['column_name'=> 'first_name','active'=> true,'name'=>'Owner'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
        ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
        ['column_name'=> 'occupancy_classification_name','active'=> true,'name'=>'Char of Occu'],
        ['column_name'=> 'contact_number','active'=> true,'name'=>'Contact #'],
        ['column_name'=> 'email','active'=> true,'name'=>'Email'],
        ['column_name'=> 'floor_area','active'=> true,'name'=>'Floor Area'],
        ['column_name'=> 'signage_area','active'=> true,'name'=>'Signage Area'],
        ['column_name'=> 'history','active'=> true,'name'=>'History'],
    ];
    public $business = [
        'id' => NULL,
        'owner_id' => NULL,
        'brgy_id' => NULL,
        'occupancy_classification_id' => NULL,
        'business_type_id'=>NULL,
        'img_url' => NULL,
        'name' => NULL,
        'street_address' => NULL,
        'email' => NULL,
        'contact_number' => NULL,
        'floor_area' => NULL,
        'signage_area' => NULL,
    ];
    public $brgy;
    public $occupancy_classifications;
    public $owners;
    public $business_types;
    public $history = [];
    public function mount(){
        $city_mun = DB::table('citymun')
            ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
            ->first();
        $this->brgy = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->get()
            ->toArray();
        $this->owners = DB::table('persons as p')
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
        ->where('is_active','=',1)
        ->get()
        ->toArray();
        $this->occupancy_classifications = DB::table('occupancy_classifications')
            ->where('is_active','=',1)
            ->get()
            ->toArray();
        $this->business_types = DB::table('business_types')
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
        $table_data = DB::table('businesses as b')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'b.is_active'

            )
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->orderBy('id','desc')
            ->paginate(10);
        return view('livewire.admin.inspector-team-leader.establishments.businesses.businesses',[
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
        $this->business = [
            'id' => NULL,
            'owner_id' => NULL,
            'brgy_id' => NULL,
            'occupancy_classification_id' => NULL,
            'business_type_id'=>NULL,
            'img_url' => NULL,
            'name' => NULL,
            'street_address' => NULL,
            'email' => NULL,
            'contact_number' => NULL,
            'floor_area' => NULL,
            'signage_area' => NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(!strlen($this->business['name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter business name!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->business['owner_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Invalid owner!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
           // no validation
        }

        if(!intval($this->business['business_type_id']) && 
            DB::table('business_types')
                ->where('id','=',$this->business['business_type_id'])
                ->first()
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select business_type!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->business['brgy_id']) && 
            DB::table('brgy')
                ->where('id','=',$this->business['brgy_id'])
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
        $var =   DB::table('occupancy_classifications')
        ->where('id','=',$this->business['occupancy_classification_id'])
        ->where('is_active','=',1)
        ->first();
        if(
            $var 
        ){
        }else {
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select occupancy classification!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!strlen($this->business['contact_number'])){
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
            if(strlen($this->business['contact_number']) !=11){
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
            for ($i=0; $i < strlen($this->business['contact_number']); $i++) { 
                if($i == 0 ){
                    if(intval($this->business['contact_number'][$i])!= 0 ){
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
                    if(intval($this->business['contact_number'][$i])!= 9 ){
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
                    if((intval($this->business['contact_number'][$i])< 0 || intval($this->business['contact_number'][$i]) > 9)){
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
        if(!strlen($this->business['email'])){
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
            if(!filter_var($this->business['email'], FILTER_VALIDATE_EMAIL)) {
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
            if(DB::table('businesses')
                ->where('email','=',$this->business['email'])
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

        $business['img_url'] = 'default.png';
        if($this->business['img_url']){
            if($this->business['img_url']){
                $business['img_url'] = self::save_image($this->business['img_url'],'business','businesses','img_url');
                if($business['img_url'] == 0){
                    return;
                }
            } 
        }
        if(DB::table('businesses')
            ->insert([
                'owner_id' => $this->business['owner_id'],
                'brgy_id' => $this->business['brgy_id'],
                'occupancy_classification_id' => $this->business['occupancy_classification_id'],
                'business_type_id'=>$this->business['business_type_id'],
                'img_url' => $business['img_url'],
                'name' => $this->business['name'],
                'street_address' => $this->business['street_address'],
                'email' => $this->business['email'],
                'contact_number' => $this->business['contact_number'],
                'floor_area' => $this->business['floor_area'],
                'signage_area' => $this->business['signage_area'],
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
                'log_details' => 'has added an business with the business name of '.$this->business['name'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function edit($id,$modal_id){

        if($edit = DB::table('businesses as b')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'b.is_active',
                'b.brgy_id',
                'b.owner_id',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',



            )
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('b.id','=',$id)
            ->first()){
            $this->business = [
                'id' => $edit->id,
                'owner_id' => $edit->owner_id,
                'brgy_id' => $edit->brgy_id,
                'occupancy_classification_id' => $edit->occupancy_classification_id,
                'business_type_id'=>$edit->business_type_id,
                'img_url' => NULL,
                'name' => $edit->name,
                'street_address' => $edit->street_address,
                'email' => $edit->email,
                'contact_number' => $edit->contact_number,
                'floor_area' => $edit->floor_area,
                'signage_area' => $edit->signage_area,
            ];
            $this->dispatch('openModal',$modal_id);
        }
    }    

    public function save_edit($id,$modal_id){
        $edit = DB::table('businesses as b')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'b.is_active',
                'b.brgy_id',
                'b.owner_id',
                'b.occupancy_classification_id',
                'b.business_type_id',
                'b.street_address',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',



            )
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('b.id','=',$id)
            ->first();
        if(!strlen($this->business['name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter business name!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->business['owner_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Invalid owner!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
           // no validation
        }

        if(!intval($this->business['business_type_id']) && 
            DB::table('business_types')
                ->where('id','=',$this->business['business_type_id'])
                ->first()
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select business_type!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->business['brgy_id']) && 
            DB::table('brgy')
                ->where('id','=',$this->business['brgy_id'])
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
        $var =   DB::table('occupancy_classifications')
        ->where('id','=',$this->business['occupancy_classification_id'])
        ->where('is_active','=',1)
        ->first();
        if(
            $var 
        ){
        }else {
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select occupancy classification!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!strlen($this->business['contact_number'])){
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
            if(strlen($this->business['contact_number']) !=11){
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
            for ($i=0; $i < strlen($this->business['contact_number']); $i++) { 
                if($i == 0 ){
                    if(intval($this->business['contact_number'][$i])!= 0 ){
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
                    if(intval($this->business['contact_number'][$i])!= 9 ){
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
                    if((intval($this->business['contact_number'][$i])< 0 || intval($this->business['contact_number'][$i]) > 9)){
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
        if(!strlen($this->business['email'])){
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
            if(!filter_var($this->business['email'], FILTER_VALIDATE_EMAIL)) {
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
            if(DB::table('businesses')
                ->where('id','<>',$id)
                ->where('email','=',$this->business['email'])
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

        $business['img_url'] = $edit->img_url;
        if($this->business['img_url']){
            if($this->business['img_url']){
                $business['img_url'] = self::save_image($this->business['img_url'],'business','businesses','img_url');
                if($business['img_url'] == 0){
                    return;
                }
            } 
        }
        if(DB::table('businesses')
            ->where('id','=',$id)
            ->update([
                'owner_id' => $this->business['owner_id'],
                'brgy_id' => $this->business['brgy_id'],
                'occupancy_classification_id' => $this->business['occupancy_classification_id'],
                'business_type_id'=>$this->business['business_type_id'],
                'img_url' => $business['img_url'],
                'name' => $this->business['name'],
                'street_address' => $this->business['street_address'],
                'email' => $this->business['email'],
                'contact_number' => $this->business['contact_number'],
                'floor_area' => $this->business['floor_area'],
                'signage_area' => $this->business['signage_area'],
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
            'log_details' => 'has edited an business with the business name of '.$this->business['name'],
        ]);
        $this->dispatch('openModal',$modal_id);
    }





    public function save_deactivate($id,$modal_id){
        if(
            DB::table('businesses')
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
                'log_details' => 'has deactivated an business with the business name of '.$this->business['name'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function save_activate($id,$modal_id){
        if(
            DB::table('businesses')
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
                'log_details' => 'has activated an business with the business name of '.$this->business['name'],
            ]);
            $this->dispatch('openModal',$modal_id);
        }
    }
    public function viewHistory($id,$modal_id){
        $this->history = DB::table('inspections as i')
        ->select(
            'i.id',
            'b.img_url',
            'b.name',
            'p.first_name',
            'p.middle_name',
            'p.last_name',
            'p.suffix',
            'brg.brgyDesc as barangay',
            'bt.name as business_type_name',
            'oc.character_of_occupancy as occupancy_classification_name',
            'b.contact_number',
            'b.email',
            'b.floor_area',
            'b.signage_area',
            'b.is_active',
            'st.name as status_name',
            'i.schedule_date',
        )
        ->leftjoin('inspection_inspector_members as iim','iim.inspection_id','i.id')
        ->join('inspection_status as st','st.id','i.status_id')
        ->join('businesses as b','b.id','i.business_id')
        ->leftjoin('persons as p','p.id','b.owner_id')
        ->join('brgy as brg','brg.id','b.brgy_id')
        ->join('business_types as bt','bt.id','b.business_type_id')
        ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
        ->where('i.business_id','=',$id)
        ->where('st.name','=','Completed')
        ->orderBy('i.id','desc')
        ->get()
        ->toArray();
        $this->dispatch('openModal',$modal_id);
    }
    public function generate_cert($id,$modal_id){
        // validation.... cannot create if it has violation/s
        $violations = DB::table('inspection_violations as iv')
            ->where('iv.inspection_id','=',$id)
            ->whereNull('remarks')
            ->get()
            ->toArray();
        if(count($violations)){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: "Inspection has violation/s hence you cannot generate certificate \n\n This needs to be approved by administrator!",
                showConfirmButton 									: 'true',
                timer             									: '3000',
                link              									: '#'
            );
            return 0 ;
        }
        $application_types = DB::table('application_types')
            ->get()
            ->toArray();

        $application_type = DB::table('application_types')
            ->where('name','=','Annual')
            ->first();

        $business = DB::table('inspections as i')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'oc.character_of_occupancy_group',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'b.is_active'

            )
            ->join('businesses as b','b.id','i.business_id')
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('i.id','=',$id)
            ->first();

        $inspection_members = DB::table('inspection_inspector_members as iim')
            ->select(
                'p.id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.img_url',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                )
            ->join('persons as p','p.id','iim.person_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('iim.inspection_id','=',$id)
            ->get()
            ->toArray();
        
        $inspection_team_leaders = DB::table('inspection_inspector_team_leaders as iitl')
            ->select(
                'p.id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.img_url',
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                )
            ->join('persons as p','p.id','iitl.person_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('iitl.inspection_id','=',$id)
            ->get()
            ->toArray();
        $inspectors = [];
        foreach ($inspection_members as $key => $value) {
            array_push($inspectors,$value);
        }
        foreach ($inspection_team_leaders as $key => $value) {
            array_push($inspectors,$value);
        }
        $annual_certificate_categories = DB::table('annual_certificate_categories as acc')
            ->get()
            ->toArray();

        $this->annual_certificate_inspection = [
            'id' => NULL,
            'business_id' => $business->id,
            'application_type_id' => $application_type->id,
            'bin' => NULL,
            'occupancy_no' => NULL,
            'date_compiled' => NULL,
            'issued_on' => NULL,
            'step'=> 1,
            'business'=> $business,

            'application_types'=> $application_types,

            'inspectors'=>$inspectors,
            'annual_certificate_inspection_inspector' => [],
            'inspector_id'=>NULL,

            'annual_certificate_categories'=> $annual_certificate_categories,
            'annual_certificate_category_id'=>NULL,
        ];
        
        $this->dispatch('openModal',$modal_id);
    }
}
