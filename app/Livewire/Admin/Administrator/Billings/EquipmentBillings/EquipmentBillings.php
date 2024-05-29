<?php

namespace App\Livewire\Admin\Administrator\Billings\EquipmentBillings;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class EquipmentBillings extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Equipment billings";

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'category_name','active'=> true,'name'=>'Category'],
        ['column_name'=> 'section_name','active'=> true,'name'=>'Section'],
        ['column_name'=> 'capacity','active'=> true,'name'=>'Capacity'],
        ['column_name'=> 'fee','active'=> true,'name'=>'Fee'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $equipment_billing = [
        'id' => NULL,
        'category_id' => NULL,
        'section_id' => NULL,
        'capacity' => NULL,
        'fee' => NULL,
        'is_active' => NULL,
    ];

    public $categories;


    public function mount(){
       
        $this->categories = DB::table('categories')
            ->where('is_active','=',1)
            ->get()
            ->toArray();
    }
    public function render()
    {
        // dd($this->equipment_billing['category_id']);
        
        if($this->equipment_billing['category_id']){
            $equipment_billing_sections = DB::table('equipment_billing_sections')
                ->where('is_active','=',1)
                ->where('category_id','=',$this->equipment_billing['category_id'])
                ->get()
                ->toArray();
        }else{
            $equipment_billing_sections = [];
        }
        
        $table_data = DB::table('equipment_billings as eb')
            ->select(
                'eb.id' ,
                'eb.section_id' ,
                'eb.fee' ,
                'ebs.name as section_name' ,
                'c.name as category_name',
                'eb.category_id',
                'eb.capacity',
                'eb.is_active' ,
            )
            ->join('categories as c','c.id','eb.category_id')
            ->join('equipment_billing_sections as ebs','ebs.id','eb.section_id')
            ->orderBy('eb.id','desc')
            ->paginate(10);
        return view('livewire.admin.administrator.billings.equipment-billings.equipment-billings',[
            'table_data'=>$table_data,
            'equipment_billing_sections'=>$equipment_billing_sections
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $this->equipment_billing = [
            'id' => NULL,
            'category_id' => NULL,
            'section_id' => NULL,
            'capacity' => NULL,
            'fee' => NULL,
            'is_active' => NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(floatval($this->equipment_billing['fee'])<=0){
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
        if(!intval($this->equipment_billing['category_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select category!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->equipment_billing['section_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select section!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        
        if(DB::table('equipment_billings')
            ->insert([
                'category_id' => $this->equipment_billing['category_id'],
                'section_id' => $this->equipment_billing['section_id'],
                'capacity'=>$this->equipment_billing['capacity'],
                'fee'=>$this->equipment_billing['fee']
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
        $edit =  DB::table('equipment_billings as eb')
            ->select(
                'eb.id' ,
                'eb.section_id' ,
                'eb.fee' ,
                'ebs.name as section_name' ,
                'c.name as category_name',
                'eb.category_id',
                'eb.capacity',
                'eb.is_active' ,
            )
            ->join('categories as c','c.id','eb.category_id')
            ->join('equipment_billing_sections as ebs','ebs.id','eb.section_id')
            ->where('eb.id','=',$id)
            ->first();
        $this->equipment_billing = [
            'id' => $edit->id,
            'category_id' => $edit->category_id,
            'section_id' => $edit->section_id,
            'capacity' => $edit->capacity,
            'fee' => $edit->fee,
            'is_active' => $edit->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        if(floatval($this->equipment_billing['fee'])<=0){
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
        if(!intval($this->equipment_billing['category_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select category!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(!intval($this->equipment_billing['section_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please select section!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }
        if(DB::table('equipment_billings')
            ->where('id','=',$id)
            ->update([
                'category_id' => $this->equipment_billing['category_id'],
                'section_id' => $this->equipment_billing['section_id'],
                'capacity'=>$this->equipment_billing['capacity'],
                'fee'=>$this->equipment_billing['fee']
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
            DB::table('equipment_billings')
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
            DB::table('equipment_billings')
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

