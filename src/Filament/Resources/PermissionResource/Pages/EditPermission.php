<?php

namespace Molitor\User\Filament\Resources\PermissionResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Molitor\User\Filament\Resources\PermissionResource;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return __('user::permission.edit');
    }
}

