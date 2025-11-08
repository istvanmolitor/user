<?php

namespace Molitor\User\Filament\Resources\UserGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Molitor\User\Filament\Resources\UserGroupResource;

class ListUserGroups extends ListRecords
{
    protected static string $resource = UserGroupResource::class;

    public function getBreadcrumb(): string
    {
        return __('user::common.list');
    }

    public function getTitle(): string
    {
        return __('user::user-group.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('user::user-group.create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}

