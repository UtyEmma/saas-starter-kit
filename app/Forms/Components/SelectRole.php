<?php

namespace App\Forms\Components;

use App\Enums\Roles;
use Filament\Forms\Components\Select;
use Illuminate\Support\Arr;

class SelectRole extends Select {

    protected function setUp() : void {
        parent::setUp();
        foreach (Roles::cases() as $key => $value) {
            $this->options[$value->value] = $value->label();
        }
    }

    public function except(mixed $items) {
        foreach($items as $item) {
            $this->options = array_filter($this->options, function($option) use($item) {
                return $item->value !== $option;
            }, ARRAY_FILTER_USE_KEY);
        }

        return $this;
    }

    public function only(mixed $items) {
        foreach($items as $item) {
            $this->options = array_filter($this->options, function($option) use($item) {
                return $item->value == $option;
            }, ARRAY_FILTER_USE_KEY);
        }

        return $this;
    }


}
