<?php

namespace App\Livewire\Admin\InspectorTeamLeader\Request\GeneratePdf;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class GeneratePdf extends Component
{
    public $title = "Generate Request PDF";

    public $email;
    public $establishment;
    public $subject ;
    public $owner;
    public $owner_f1;
    public $business = [];

    public $request = [

    ];
    public function mount($hash,$start_date,$end_date){
        $business = DB::table('request_inspections as ri')
            ->select(
                'b.id',
                'b.img_url',
                'b.name',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'brg.brgyDesc as barangay',
                'bc.name as business_category_name',
                'bt.name as business_type_name',
                'oc.character_of_occupancy as occupancy_classification_name',
                'b.contact_number',
                'b.email',
                'b.floor_area',
                'b.signage_area',
                'b.is_active',
                'schedule_date',
            )
            ->join('businesses as b','b.id','ri.business_id')
            ->join('persons as p','p.id','b.owner_id')
            ->join('brgy as brg','brg.id','b.brgy_id')
            ->join('business_category as bc','bc.id','b.business_category_id')
            ->join('business_types as bt','bt.id','b.business_type_id')
            ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
            ->where('ri.hash','=',$hash)
            ->first();
        $this->email = $business->email;
        $this->establishment = $business->name;
        $this->owner = $business->last_name.' ,'.$business->first_name.' '.$business->middle_name;
        $this->owner_f1 = $business->first_name.' '.$business->middle_name.' '.$business->last_name;
        
        $this->start_date = date_format(date_create($start_date),"M d, Y");
        $this->end_date =  date_format(date_create($end_date),"M d, Y");
        $this->schedule_date =  date_format(date_create($business->schedule_date),"M d, Y");
        
    }
    public function render()
    {
        return view('livewire.admin.inspector-team-leader.request.generate-pdf.generate-pdf',[
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
