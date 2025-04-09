<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\TextColumn;

class RoleColumn extends TextColumn {

    function getState(): mixed {
        $record = $this->record[$this->name];
        return $record->label();
    }

}
