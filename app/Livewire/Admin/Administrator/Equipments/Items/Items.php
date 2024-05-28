<?php

namespace App\Livewire\Admin\Administrator\Equipments\Items;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Items extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Items";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'img_url','active'=> true,'name'=>'Image'],
        ['column_name'=> 'category_name','active'=> true,'name'=>'Category name'],
        ['column_name'=> 'name','active'=> true,'name'=>'Item name'],
        ['column_name'=> 'section','active'=> true,'name'=>'Section'],
        ['column_name'=> 'id','active'=> true,'name'=>'Action'],
    ];
    public $item = [
        'id' => NULL,
        'category_id' => NULL,
        'name' => NULL,
        'section' => NULL,
        'img_url' => NULL,
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
        $table_data = DB::table('items as i')
            ->select(
                'i.id',
                'c.name as category_name',
                'i.name',
                'i.section',
                'i.img_url',
                'i.is_active'
            )
            ->join('categories as c','c.id','i.category_id')
            ->orderBy('id','desc')
            ->paginate(10);
        return view('livewire.admin.administrator.equipments.items.items',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function save_image($image_file,$folder_name,$table_name,$column_name){
        if($image_file && file_exists(storage_path().'/app/livewire-tmp/'.$image_file->getfilename())){
            $file_extension =$image_file->getClientOriginalExtension();
            $tmp_name = 'livewire-tmp/'.$image_file->getfilename();
            $size = Storage::size($tmp_name);
            $mime = Storage::mimeType($tmp_name);
            $max_image_size = 20 * 1024*1024; // 5 mb
            $file_extensions = array('image/jpeg','image/png','image/jpg');
            
            if($size<= $max_image_size){
                $valid_extension = false;
                foreach ($file_extensions as $value) {
                    if($value == $mime){
                        $valid_extension = true;
                        break;
                    }
                }
                if($valid_extension){
                    // move
                    $new_file_name = md5($tmp_name).'.'.$file_extension;
                    while(DB::table($table_name)
                    ->where([$column_name=> $new_file_name])
                    ->first()){
                        $new_file_name = md5($tmp_name.rand(1,10000000)).'.'.$file_extension;
                    }
                    if(Storage::move($tmp_name, 'public/content/'.$folder_name.'/'.$new_file_name)){
                        return $new_file_name;
                    }
                }else{
                    $this->dispatch('swal:redirect',
                        position         									: 'center',
                        icon              									: 'warning',
                        title             									: 'Invalid image type!',
                        showConfirmButton 									: 'true',
                        timer             									: '1000',
                        link              									: '#'
                    );
                    return 0;
                }
            }else{
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Image is too large!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            } 
        }
        return 0;
    }
    public function add($modal_id){
        $this->item = [
            'id' => NULL,
            'category_id' => NULL,
            'name' => NULL,
            'section' => NULL,
            'img_url' => NULL,
            'is_active' => NULL,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_add($modal_id){
        if(!strlen($this->item['name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter item name!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(DB::table('items')
                ->where('name','=',$this->item['name'])
                ->first()){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Item Exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(!intval($this->item['category_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Invalid category!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(DB::table('categories')
                ->where('id','=',$this->item['category_id'])
                ->where('is_active','=',1)
                ->first()){

            }else{
                $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Invalid category!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
            }
        }
        $item['img_url'] = 'default.png';
        if($this->item['img_url']){
            if($this->item['img_url']){
                $item['img_url'] = self::save_image($this->item['img_url'],'items','items','img_url');
                if($item['img_url'] == 0){
                    return;
                }
            } 
        }
        if(DB::table('items')
            ->insert([
                'category_id' => $this->item['category_id'],
                'name' => $this->item['name'],
                'section' => $this->item['section'],
                'img_url' => $item['img_url'],
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
        if($item =  DB::table('items as i')
            ->select(
                'i.id',
                'c.name as category_name',
                'i.category_id',
                'i.name',
                'i.section',
                'i.img_url',
                'i.is_active'
            )
            ->join('categories as c','c.id','i.category_id')
            ->where('i.id','=',$id)
            ->first()){

        }
        $this->item = [
            'id' => $item->id,
            'category_id' => $item->category_id,
            'name' => $item->name,
            'section' => $item->section,
            'img_url' => NULL,
            'is_active' => $item->is_active,
        ];
        $this->dispatch('openModal',$modal_id);
    }
    public function save_edit($id,$modal_id){
        $edit =  DB::table('items as i')
            ->select(
                'i.id',
                'c.name as category_name',
                'i.category_id',
                'i.name',
                'i.section',
                'i.img_url',
                'i.is_active'
            )
            ->join('categories as c','c.id','i.category_id')
            ->where('i.id','=',$id)
            ->first();
        if(!strlen($this->item['name'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Please enter item name!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(DB::table('items')
                ->where('id','<>',$id)
                ->where('name','=',$this->item['name'])
                ->first()){
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Item Exist!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        if(!intval($this->item['category_id'])){
            $this->dispatch('swal:redirect',
                position         									: 'center',
                icon              									: 'warning',
                title             									: 'Invalid category!',
                showConfirmButton 									: 'true',
                timer             									: '1000',
                link              									: '#'
            );
            return 0;
        }else{
            if(DB::table('categories')
                ->where('id','=',$this->item['category_id'])
                ->first()){

            }else{
                $this->dispatch('swal:redirect',
                    position         									: 'center',
                    icon              									: 'warning',
                    title             									: 'Invalid category!',
                    showConfirmButton 									: 'true',
                    timer             									: '1000',
                    link              									: '#'
                );
                return 0;
            }
        }
        $item['img_url'] = $edit->img_url;
        if($this->item['img_url']){
            if($this->item['img_url']){
                $item['img_url'] = self::save_image($this->item['img_url'],'items','items','img_url');
                if($item['img_url'] == 0){
                    return;
                }
            } 
        }


        if(
            DB::table('items')
                ->where('id','=',$id)
                ->update([
                    'category_id' => $this->item['category_id'],
                    'name' => $this->item['name'],
                    'section' => $this->item['section'],
                    'img_url' => $item['img_url'],
                ])            
        ){
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
            DB::table('items')
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
            DB::table('items')
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
