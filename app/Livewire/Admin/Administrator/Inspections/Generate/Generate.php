<?php

namespace App\Livewire\Admin\Administrator\Inspections\Generate;

use Livewire\Component;

class Generate extends Component
{
    public $title = "Generate PDF"; 
    public function render()
    {
        return view('livewire.admin.administrator.inspections.generate.generate')
            ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
