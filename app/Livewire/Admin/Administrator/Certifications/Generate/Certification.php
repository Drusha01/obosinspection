<?php

namespace App\Livewire\Admin\Administrator\Certifications\Generate;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Certification extends Component
{
    public $title = "Generate Certification";
    public $generate ;
    public function mount($id){
        $annual_certificate_inspections = DB::table('annual_certificate_inspections as aci')
            ->select(
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
            ->where('aci.id','=',$id)
            ->first();

        $annual_certificate_inspection_inspectors = DB::table('annual_certificate_inspection_inspectors as acii')
            ->select(
                "acii.id",
                "acii.annual_certificate_inspection_id",
                "acii.person_id",
                "acii.category_id",
                "acii.date_signed",
                "acii.time_in",
                "acii.time_out",
                'acc.name as category_name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'p.contact_number',
                'p.email',
                'p.img_url',
            )
            ->join('persons as p','p.id','acii.person_id')
            ->join('annual_certificate_categories as acc','acc.id','acii.category_id')
            ->where('acii.annual_certificate_inspection_id','=',$id)
            ->get()
            ->toArray();
        $this->generate = [
            'annual_certificate_inspections'=>$annual_certificate_inspections,
            'annual_certificate_inspection_inspectors'=>$annual_certificate_inspection_inspectors
        ];
    }
    public function render()
    {
        return view('livewire.admin.administrator.certifications.generate.certification',[
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}