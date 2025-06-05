<?php

namespace App\Filament\Resources\CommitteeMemberResource\Pages;

use App\Filament\Resources\CommitteeMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommitteeMembers extends ListRecords
{
    protected static string $resource = CommitteeMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
