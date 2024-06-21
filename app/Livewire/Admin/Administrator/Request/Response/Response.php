<?php

namespace App\Livewire\Admin\Administrator\Request\Response;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Response extends Component
{
    public $title = "Request Response";
    public $request = [];
    public $reponse;
    public $request_inspection;
    public $reason;
    public $content;
    public function mount(Request $request,$response,$hash){
        
        $request = DB::table('request_inspections as ri')
            ->select(
                'ri.id' ,
                'ri.business_id',
                'ri.status_id' ,
                'ri.request_date' ,
                'ri.expiration_date' ,
                'ri.accepted_date' ,
                'ri.is_responded' ,
                'ri.reason' ,
                'ri.hash' ,
                'rs.name as status_name',
            )
            ->join('request_status as rs','ri.status_id','rs.id')
            ->where('ri.hash','=',$hash)
            ->where('rs.name','<>','Deleted')
            ->first();
        $business = [];
        if($request){
            $this->content = 'OBOS Inspection would like to request to inspect your establishment from <strong>'.date_format(date_create($request->request_date),"M d, Y ").' to '.date_format(date_create($request->expiration_date),"M d, Y ").'</strong>, to accept please click the accept button. Thank you.'; 
            $business = DB::table('businesses as b')
                ->select(
                    'b.id',
                    'b.img_url',
                    'b.name',
                    'b.business_category_id',
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
                )
                ->join('persons as p','p.id','b.owner_id')
                ->join('brgy as brg','brg.id','b.brgy_id')
                ->join('business_types as bt','bt.id','b.business_type_id')
                ->join('occupancy_classifications as oc','oc.id','b.occupancy_classification_id')
                ->where('b.id','=',$request->business_id)
                ->first();
        }

        $this->request_inspection = [
            'request'=> $request ,
            'business'=> $business
        ];
        $this->reponse = $response;
    }
    public function render()
    {
        return view('livewire.admin.administrator.request.response.response')
        ->layout('components.layouts.response',[
            'title'=>$this->title]);
    }
    public function decline(){
        if(strlen($this->reason)<=0){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter reason!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return;
        }
        $status = DB::table('request_status')
        ->where('name','=',"Declined")
        ->first();
        if(DB::table('request_inspections as ri')
            ->where('ri.hash','=',$this->request_inspection['request']->hash)
            ->update([
                'is_responded'=>1,
                'status_id'=>$status->id,
                'reason'=>$this->reason,
                'accepted_date'=>date_format(date_create(now()),"Y-m-d"),
            ])
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'You have successfully declined!',
                showConfirmButton 									: 'true',
                timer             									: '3000',
                link              									: '/request/decline/'.$this->request_inspection['request']->hash,
            );
            return;
        }

    }
    public function accept(){
        $status = DB::table('request_status')
        ->where('name','=',"Accepted")
        ->first();
        if(DB::table('request_inspections as ri')
            ->where('ri.hash','=',$this->request_inspection['request']->hash)
            ->update([
                'is_responded'=>1,
                'status_id'=>$status->id,
                'accepted_date'=>date_format(date_create(now()),"Y-m-d"),
            ])
        ){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'success',
                title             									: 'You have successfully accepted!',
                showConfirmButton 									: 'true',
                timer             									: '3000',
                link              									: '/request/accept/'.$this->request_inspection['request']->hash,
            );
            return;
        }
    }
}
