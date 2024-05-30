<?php

namespace App\Livewire\Admin\Administrator\Certifications\Certification;

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

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'name','active'=> true,'name'=>'Business name'],
        ['column_name'=> 'barangay','active'=> true,'name'=>'Brgy'],
        ['column_name'=> 'business_type_name','active'=> true,'name'=>'Business Type'],
        ['column_name'=> 'schedule_date','active'=> true,'name'=>'Schedule'],
        ['column_name'=> 'id','active'=> true,'name'=>'Inspection Details'],
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
    public function mount(){
        $this->businesses = DB::table('businesses as b')
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
            ->where('b.is_active','=',1)
            ->get()
            ->toArray();
        $this->application_types = DB::table('application_types')
            ->get()
            ->toArray();
    }

    public function render()
    {
        $table_data = [];
        return view('livewire.admin.administrator.certifications.certification.certification',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $application_type = DB::table('application_types')
            ->where('name','=','Annual')
            ->first();
        $inspectors =  DB::table('users as u')
            ->select(
                'u.id as id',
                'p.id as person_id',
                'u.username',
                'u.is_active',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.img_url',
                "u.date_created",
                "u.date_updated",
                'wr.id as work_role_id',
                'wr.name as work_role_name',
                )
            ->join('persons as p','p.id','u.person_id')
            ->join('person_types as pt', 'pt.id','p.person_type_id')
            ->join('work_roles as wr', 'wr.id','p.work_role_id')
            ->where('pt.name','=','Inspector')
            ->get()
            ->toArray();
        $this->annual_certificate_inspection = [
            'id' => NULL,
            'status_id' => NULL,
            'business_id' => NULL,
            'application_type_id' => $application_type->id,
            'bin' => NULL,
            'occupancy_no' => NULL,
            'date_compiled' => NULL,
            'issued_on' => NULL,
            'step'=> 1,
            'business'=> NULL,

            'inspectors'=>$inspectors,
            'annual_certificate_inspection_inspector' => [],
            'inspector_id'=>NULL,

            'annual_certificate_categories'=> [],
            'annual_certificate_category_id'=>NULL,
        ];
        
        $this->dispatch('openModal',$modal_id);
    }
    public function add_annual_inspector(){
        if($this->annual_certificate_inspection['inspector_id']){
            $valid = true;
            foreach ($this->annual_certificate_inspection['annual_certificate_inspection_inspector'] as $key => $value) {
                if($value->id == $this->annual_certificate_inspection['inspector_id']){
                    $valid = false;
                    break;
                }
            }
            if($valid){
                foreach ($this->annual_certificate_inspection['inspectors'] as $key => $value) {
                    if($value->id == $this->annual_certificate_inspection['inspector_id']){
                        array_push($this->annual_certificate_inspection['annual_certificate_inspection_inspector'],$value);
                        break;
                    }
                }
            }
        }
    }
    public function update_business_information(){
        if($this->annual_certificate_inspection['step'] == 1){
            if(intval($this->annual_certificate_inspection['business_id'])){
                $this->annual_certificate_inspection['business'] = DB::table('businesses as b')
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
                    ->join('persons as p','p.id','b.owner_id')
                    ->join('brgy as brg','brg.id','b.brgy_id')
                    ->join('business_types as bt','bt.id','b.business_type_id')
                    ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                    ->where('b.id','=',$this->annual_certificate_inspection['business_id'])
                    ->first();
            }
        }
    
    }

    public function next($modal_id){
        if($this->annual_certificate_inspection['step'] == 1){
            if(intval($this->annual_certificate_inspection['business_id'])){
                $this->annual_certificate_inspection['step']+=1;
               
            }else{
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Please select business!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }elseif($this->annual_certificate_inspection['step'] == 2){
           
        }elseif($this->annual_certificate_inspection['step'] == 3){
           
        }
    }
    public function prev(){
        $this->annual_certificate_inspection['step']-=1;
    }
}
