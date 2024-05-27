<?php

namespace App\Livewire\Components\Sidebar\AdminSidebar;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSidebar extends Component
{
    public function render()
    {
        return view('livewire.components.sidebar.admin-sidebar.admin-sidebar');
    }
}
