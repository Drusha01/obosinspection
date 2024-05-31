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
    public function mount(){
        
    }

    public function render()
    {
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
        ->orderBy('aci.id','desc')
        ->paginate(10);
        return view('livewire.admin.administrator.certifications.certification.certification',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }

}
