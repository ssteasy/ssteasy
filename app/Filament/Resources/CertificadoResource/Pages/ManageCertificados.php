<?php

namespace App\Filament\Resources\CertificadoResource\Pages;

use App\Filament\Resources\CertificadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCertificados extends ManageRecords
{
    protected static string $resource = CertificadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
