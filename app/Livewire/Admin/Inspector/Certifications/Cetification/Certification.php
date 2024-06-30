<?php

namespace App\Livewire\Admin\Inspector\Certifications\Cetification;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Certification extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Certifications";
    public $businesses = [];
    public $application_types = [];
    public $inspectors = [];
    public $annual_certificate_categories = [];
    public $brgy = [];
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
        ['column_name'=> 'Owner','active'=> true,'name'=>'Owner'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
        ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
        ['column_name'=> 'date_compiled','active'=> true,'name'=>'Date Compiled'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];

    public $annual_certificate_inspection = [
        'id' => NULL,
        'status_id' => NULL,
        'business_id' => NULL,
        'application_type_id' => NULL,
        'bin' => NULL,
        'occupancy_no' => NULL,
        'date_compiled' => NULL,
        'issued_on' => NULL,
        'step'=> 1,
        'business'=> NULL,
    ];
    public $annual_certificate_inspection_inspector = [
        'id' => NULL,
        'annual_certificate_inspection_id' => NULL,
        'person_id' => NULL,
        'category_id' => NULL,
        'date_signed' => NULL,
        'time_in' => NULL,
        'time_out' => NULL,
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
        'type' => NULL,
        'type_prev' => NULL,
        'brgy_id'=>NULL,
    ];
    public $search_by = [
        ['name'=>'Name','column_name'=>'b.name'],
        // ['name'=>'Contact','column_name'=>'b.contact_number'],
        // ['name'=>'Email','column_name'=>'b.email'],
    ];
    public $table_filter = [];

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
        $city_mun = DB::table('citymun')
            ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
            ->first();
        $this->brgy = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->orderby('brgyDesc','asc')
            ->get()
            ->toArray();

    }
    public function render(Request $request)
    {
        $session = $request->session()->all();
        $person = DB::table('users as u')
            ->select('u.person_id')
            ->where('u.id','=',$session['id'])
            ->first();
        if($this->search['search'] != $this->search['search_prev']){
            $this->search['search_prev'] = $this->search['search'];
            $this->resetPage();
        }
        if($this->search['type'] != $this->search['type_prev']){
            $this->search['type_prev'] = $this->search['type'];
            if($this->search['type'] == 'b.contact_number'){
                $this->search['search'] = substr($this->search['search'],1);
            }
            $this->resetPage();
        }else{
            if(!$this->search['type']){
                $this->search['type'] = $this->search_by[0]['column_name'];
            }
        }

        if(intval($this->search['brgy_id'] )){
            $table_data = DB::table('annual_certificate_inspections as aci')
                ->select(
                    'aci.id',
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
                    'b.street_address',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'at.name as application_type_name',
                    'aci.bin',
                    'aci.occupancy_no',
                    'aci.date_compiled',
                    'aci.issued_on',
                    'aci.date_created',
                    'aci.date_updated'
                )
                ->join('businesses as b','b.id','aci.business_id')
                ->join('persons as p','p.id','b.owner_id')
                ->join('brgy as brg','brg.id','b.brgy_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                ->join('application_types as at','at.id','aci.application_type_id')
                ->join('annual_certificate_inspection_inspectors as acii','acii.annual_certificate_inspection_id','aci.id')
                ->where('acii.person_id','=',$person->person_id)
                ->where('b.brgy_id','=',$this->search['brgy_id'] )
                ->where($this->search['type'],'like',$this->search['search'] .'%')
                ->orderBy('aci.id','desc')
                ->paginate($this->table_filter['table_rows']);
        }else{
            $table_data = DB::table('annual_certificate_inspections as aci')
                ->select(
                    'aci.id',
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
                    'b.street_address',
                    'b.email',
                    'b.floor_area',
                    'b.signage_area',
                    'at.name as application_type_name',
                    'aci.bin',
                    'aci.occupancy_no',
                    'aci.date_compiled',
                    'aci.issued_on',
                    'aci.date_created',
                    'aci.date_updated'
                )
                ->join('businesses as b','b.id','aci.business_id')
                ->join('persons as p','p.id','b.owner_id')
                ->join('brgy as brg','brg.id','b.brgy_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                ->join('application_types as at','at.id','aci.application_type_id')
                ->join('annual_certificate_inspection_inspectors as acii','acii.annual_certificate_inspection_id','aci.id')
                ->where('acii.person_id','=',$person->person_id)
                ->where($this->search['type'],'like',$this->search['search'] .'%')
                ->orderBy('aci.id','desc')
                ->paginate($this->table_filter['table_rows']);
        }
        return view('livewire.admin.inspector.certifications.cetification.certification',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }

}
