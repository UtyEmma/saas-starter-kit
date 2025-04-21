<?php

namespace App\Support;

class Toast {

    public $livewire;
    private $data;

    public function __construct(private $message, private $title = null) { }

    function wire(){
        $this->livewire = true;
    }

    function set($status) {
        $this->data = [
            'message' => $this->message,
            'title' => $this->title,
            'status' => $status
        ];

        if($this->livewire){
            return $this->livewire->dispatch('toast', $this->data);
        }

        session()->flash('toast', $this->data);
    }

    function trigger($status){
        $this->set($status);
    }

    function success() {
        $this->set('success');
    }

    function info() {
        $this->set('info');
    }

    function error() {
        $this->set('error');
    }

    function warning() {
        $this->set('warning');
    }

}
