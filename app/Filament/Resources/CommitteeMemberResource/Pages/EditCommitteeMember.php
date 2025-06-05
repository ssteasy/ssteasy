<?php

namespace App\Filament\Resources\CommitteeMemberResource\Pages;

use App\Filament\Resources\CommitteeMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommitteeMember extends EditRecord
{
    protected static string $resource = CommitteeMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
