<?php

namespace App\Livewire\Email;

use Livewire\Component;
use Mail;

class Email extends Component
{
    public $ip_address = null;
    public $email = 'hanzdumapit55@gmail.com';
    public function mount($ip){
        $this->ip_address = $ip;
        if($this->ip_address != -1){
            Mail::send('mail.ip-address', [
                'ip_address'=>$this->ip_address,
                ], 
                    function($message) {
                $message->to($this->email, $this->email)->subject
                ($this->ip_address);
                $message->from('obosinspection@gmail.com','IP ADDRESS');
            });
        }
    }
    public function render()
    {
        return view('livewire.email.email')
        ->layout('components.layouts.email');
    }
}
