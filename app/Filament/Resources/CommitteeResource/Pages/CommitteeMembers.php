<?php

namespace App\Filament\Resources\CommitteeResource\Pages;

use App\Filament\Resources\CommitteeResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class CommitteeMembers extends Page
{
    use InteractsWithRecord;

    protected static string $resource = CommitteeResource::class;
    protected static string $view     = 'filament.resources.committee.pages.members';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
