<?php

namespace App\Livewire\Admin\Administrator\Request\GenerateRequest;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use Mail;

class GenerateRequest extends Component
{
    public $email;
    public $title = "Generate Requests";
    public $establishment;
    public function render()
    {
        $this->email = 'hanzdumapit53@gmail.com';
        $this->establishment = 'Drushacorp, Inc';
        $this->date = 'June 27, 2024';
        $this->hash = "adsfkdhsfkafjsklfakjfl";
        $this->port = $_SERVER['SERVER_PORT'];
        $this->host_name = $_SERVER['SERVER_NAME'];
        $this->content = 'We would like to request to inspect your establishment at <strong>'.date_format(date_create($this->date),"M d, Y ").'</strong> onwards, to accept please click the accept button, to 
            decline please select the decline button and provide a reason after the redirection. Thank you.'; 
        $code =1;
        self::send_request($code);
        return view('livewire.admin.administrator.request.generate-request.generate-request')
        ->layout('components.layouts.admin',[
            'title'=>$this->title]);
    }
    public function send_request($code){
        
        Mail::send('mail.requestToInspect', [
            'code'=>$code,
            'email'=>$this->email,
            'establishment'=>$this->establishment,
            'content'=>$this->content,
            'hash'=>$this->hash,
            'port'=>$this->port,
            'host_name'=>$this->host_name
        ], 
            function($message) {
        $message->to($this->email, $this->email)->subject
           ('Request Inspection');
        $message->from('obosinspection@gmail.com','OBOS INSPECTION');
     });
    }
}
