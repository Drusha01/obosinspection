<?php

namespace App\Livewire\Admin\Administrator\Billings\BuildingBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BuildingBillings extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Building billings";

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'section_name','active'=> true,'name'=>'Section'],
        ['column_name'=> 'property_attribute','active'=> true,'name'=>'Prty Attr'],
        ['column_name'=> 'fee','active'=> true,'name'=>'Fee'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $building_billing = [
        'id' => NULL,
        'section_id' => NULL,
        'property_attribute' => NULL,
        'fee' => NULL,
        'is_active' => NULL,
    ];

    public $building_billing_sections;

    public function mount(){
        $this->building_billing_sections = DB::table('building_billing_sections')
            ->where('is_active','=',1)
            ->get()
            ->toArray();
    }

    public function render()
    {
        $table_data = DB::table('building_billings as bb')
            ->select(
                'bb.id' ,
                'bb.section_id' ,
                'bbs.name as section_name' ,
                'bb.property_attribute' ,
                'bb.fee' ,
                'bb.is_active' ,
            )
            ->join('building_billing_sections as bbs','bbs.id','bb.section_id')
            ->orderBy('bb.id','desc')
            ->paginate(10);
        return view('livewire.admin.administrator.billings.building-billings.building-billings',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $this->building_billing = [
            'id'=> NULL,
            'section_id' => NULL,
            'property_attribute' => NULL,
            'fee'=>NULL,
            'is_active'=>NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(floatval($this->building_billing['fee'])<=0){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Fee must be greater than 0!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!strlen($this->building_billing['property_attribute'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter property attribute!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(DB::table('building_billings')
            ->insert([
                'section_id' => $this->building_billing['section_id'],
                'property_attribute'=>$this->building_billing['property_attribute'],
                'fee'=>$this->building_billing['fee']
            ])){
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
        $edit = DB::table('building_billings as bb')
            ->select(
                'bb.id' ,
                'bb.section_id' ,
                'bbs.name as section_name' ,
                'bb.property_attribute' ,
                'bb.fee' ,
                'bb.is_active' ,
            )
            ->join('building_billing_sections as bbs','bbs.id','bb.section_id')
            ->where('bb.id','=',$id)
            ->first();
        $this->building_billing = [
            'id'=> $edit->id,
            'section_id' => $edit->section_id,
            'property_attribute' => $edit->property_attribute,
            'fee'=>$edit->fee,
            'is_active'=>$edit->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        if(floatval($this->building_billing['fee'])<=0){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Fee must be greater than 0!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!strlen($this->building_billing['property_attribute'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter property attribute!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(DB::table('building_billings')
            ->where('id','=',$id)
            ->update([
                'section_id' => $this->building_billing['section_id'],
                'property_attribute'=>$this->building_billing['property_attribute'],
                'fee'=>$this->building_billing['fee']
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
        $this->dispatch('openModal',$modal_id);
    }

    public function save_deactivate($id,$modal_id){
        if(
            DB::table('building_billings')
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
            DB::table('building_billings')
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

