<?php

namespace App\Filament\Resources\SlipResource\Pages;

use App\Filament\Resources\SlipResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSlip extends ViewRecord
{
    protected static string $resource = SlipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
