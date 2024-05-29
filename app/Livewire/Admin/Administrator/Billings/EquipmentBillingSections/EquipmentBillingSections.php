<?php

namespace App\Livewire\Admin\Administrator\Billings\EquipmentBillingSections;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class EquipmentBillingSections extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $title = "Equipment billing sections";

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'name','active'=> true,'name'=>'Name'],
        ['column_name'=> 'category_name','active'=> true,'name'=>'Category name'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $equipment_billing_section = [
        'id'=> NULL,
        'name'=>NULL,
        'category_id'=>NULL,
        'is_active'=>NULL,
    ];


    public function render()
    {
        $table_data = DB::table('equipment_billing_sections as ebs')
            ->select(
                'ebs.id',
                'ebs.name',
                'ebs.category_id',
                'c.name as category_name'
            )
            ->join('categories as c','c.id','ebs.category_id')
            ->orderBy('id','desc')
            ->paginate(10);
        return view('livewire.admin.administrator.billings.equipment-billing-sections.equipment-billing-sections',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }

    public function add($modal_id){
        $this->equipment_billing_section = [
            'id'=> NULL,
            'name'=>NULL,
            'is_active'=>NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(!strlen($this->equipment_billing_section['name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter name!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $edit = DB::table('equipment_billing_sections')
                ->where('name','=',$this->equipment_billing_section['name'])
                ->first();
            if($edit){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Equipment billing section name exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(DB::table('equipment_billing_sections')
            ->insert([
                'name'=>$this->equipment_billing_section['name']
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
        $edit = DB::table('equipment_billing_sections')
            ->where('id','=',$id)
            ->first();
        $this->equipment_billing_section = [
            'id'=> $edit->id,
            'name'=>$edit->name,
            'is_active'=>$edit->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        if(!strlen($this->equipment_billing_section['name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter name!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $edit = DB::table('equipment_billing_sections')
                ->where('id','<>',$id)
                ->where('name','=',$this->equipment_billing_section['name'])
                ->first();
            if($edit){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Equipment billing section name exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(DB::table('equipment_billing_sections')
            ->where('id','=',$id)
            ->update([
                'name'=>$this->equipment_billing_section['name']
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
            DB::table('equipment_billing_sections')
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
            DB::table('equipment_billing_sections')
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
