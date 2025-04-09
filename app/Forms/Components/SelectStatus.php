<?php

namespace App\Forms\Components;

use App\Enums\Status;
use Filament\Forms\Components\Select;

class SelectStatus extends Select {

    protected function setUp() : void {
        parent::setUp();

        foreach (Status::cases() as $key => $value) {
            $this->options[$value->value] = $value->label();
        }
    }

    public function except(array $items) {
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
