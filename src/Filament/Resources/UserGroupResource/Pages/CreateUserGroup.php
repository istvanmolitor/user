<?php

namespace Molitor\User\Filament\Resources\UserGroupResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Molitor\User\Filament\Resources\UserGroupResource;

class CreateUserGroup extends CreateRecord
{
    protected static string $resource = UserGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return __('user::user-group.create');
    }
}

