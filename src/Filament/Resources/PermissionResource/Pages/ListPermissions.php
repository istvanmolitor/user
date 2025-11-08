<?php

namespace Molitor\User\Filament\Resources\PermissionResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Molitor\User\Filament\Resources\PermissionResource;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    public function getBreadcrumb(): string
    {
        return __('user::common.list');
    }

    public function getTitle(): string
    {
        return __('user::permission.title');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('user::permission.create'))
                ->icon('heroicon-o-plus'),
        ];
    }
}

