<?php

namespace App\Support;

class Toast {

    public $livewire;
    private $data;

    public function __construct(private $message, private $title = null) { }

    function wire(){
        $this->livewire = true;
    }

    function get () {
        return $this->data;
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
        return $this;
    }

    function success() {
        $this->set('success');
        return $this;
    }

    function info() {
        $this->set('info');
        return $this;
    }

    function error() {
        $this->set('error');
        return $this;
    }

    function warning() {
        $this->set('warning');
        return $this;
    }

}
