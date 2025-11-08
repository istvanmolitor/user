<?php

namespace Molitor\User\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Molitor\User\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return __('user::user.create');
    }
}

