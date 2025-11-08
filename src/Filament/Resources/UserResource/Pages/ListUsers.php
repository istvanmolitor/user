<?php

namespace Molitor\User\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Molitor\User\Filament\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getBreadcrumb(): string
    {
        return __('user::common.list');
    }

    public function getTitle(): string
    {
        return __('user::user.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('user::user.create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}

