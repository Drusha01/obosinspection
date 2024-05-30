<?php

namespace App\Livewire\Admin\Administrator\BarangayLocations;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class BarangayLocations extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Barangay locations";

    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'brgyDesc','active'=> true,'name'=>'Barangay'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $barangay = [
        'id'=> NULL,
        'brgyDesc'=>NULL,
        'is_active'=>NULL,
    ];
    public function render()
    {
        $city_mun = DB::table('citymun')
            ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
            ->first();
            

        $table_data = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->orderBy('id','desc')
            ->paginate(10);
        return view('livewire.admin.administrator.barangay-locations.barangay-locations',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function add($modal_id){
        $city_mun = DB::table('citymun')
            ->where('citymunDesc','=','GENERAL SANTOS CITY (DADIANGAS)')
            ->first();
        $edit = DB::table('brgy')
            ->where('citymunCode','=',$city_mun->citymunCode)
            ->first();
        $this->barangay = [
            'id'=> NULL,
            'brgyDesc'=>NULL,
            "brgyCode"=>$edit->brgyCode,
            "regCode"=>$edit->regCode,
            "provCode"=>$edit->provCode,
            "citymunCode"=>$edit->citymunCode,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(!strlen($this->barangay['brgyDesc'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter barangay brgyDesc!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $edit = DB::table('brgy')
                ->where('brgyDesc','=',$this->barangay['brgyDesc'])
                ->first();
            if($edit){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Violation name exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(DB::table('brgy')
            ->insert([
                'brgyDesc'=>$this->barangay['brgyDesc'],
                "brgyCode"=>$this->barangay['brgyCode'],
                "regCode"=>$this->barangay['regCode'],
                "provCode"=>$this->barangay['provCode'],
                "citymunCode"=>$this->barangay['citymunCode'],
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
        $edit = DB::table('brgy')
            ->where('id','=',$id)
            ->first();
        $this->barangay = [
            'id'=> $edit->id,
            'brgyDesc'=>$edit->brgyDesc,
            'is_active'=>$edit->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        if(!strlen($this->barangay['brgyDesc'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter barangagay description!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            $edit = DB::table('brgy')
                ->where('id','<>',$id)
                ->where('brgyDesc','=',$this->barangay['brgyDesc'])
                ->first();
            if($edit){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Barangagay description name exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(DB::table('brgy')
            ->where('id','=',$id)
            ->update([
                'brgyDesc'=>$this->barangay['brgyDesc']
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
            DB::table('brgy')
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
            DB::table('brgy')
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
