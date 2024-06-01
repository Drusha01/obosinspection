<?php

namespace App\Livewire\Admin\Administrator\ActivityLogs;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class ActivityLogs extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $title = "Activity logs";
    public $filter = [
        ['column_name'=> 'id','active'=> true,'name'=>'#'],
        ['column_name'=> 'log_details','active'=> true,'name'=>'Activity log'],
        ['column_name'=> 'date_created','active'=> true,'name'=>'Date time'],
    ];
    
    public $activity_logs = [
        'created_by' => NULL,
        'inspector_team_id' => NULL,
        'log_details' => NULL,
    ];
    public function booted(Request $request){
        $session = $request->session()->all();
        $this->activity_logs['created_by'] = $session['id'];
        $user_details = 
            DB::table('users as u')
            ->select(
                'im.member_id',
                'im.inspector_team_id',
                'it.team_leader_id',
                'it.id',
                )
            ->join('persons as p','p.id','u.id')
            ->leftjoin('inspector_members as im','im.member_id','p.id')
            ->leftjoin('inspector_teams as it','it.team_leader_id','p.id')
            ->where('u.id','=',$session['id'])
            ->first();
        if($user_details->member_id){
            $this->activity_logs['inspector_team_id'] = $user_details->member_id;
        }elseif($user_details->team_leader_id){
            $this->activity_logs['inspector_team_id'] = $user_details->team_leader_id;
        }else{
            $this->activity_logs['inspector_team_id'] = 0;
        }
    }
    public function render()
    {
        $table_data = DB::table('activity_logs as al')
            ->select(
                'al.id',
                'p.first_name',
                'p.middle_name',
                'p.last_name',
                'p.suffix',
                'al.log_details',
                'al.date_created',
                'al.date_updated'
            )
            ->join('persons as p','p.id','al.created_by')
            ->orderBy('id','desc')
            ->paginate(10);

        return view('livewire.admin.administrator.activity-logs.activity-logs',[
            'table_data'=>$table_data
        ])
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
}
