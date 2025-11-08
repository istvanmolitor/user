<?php

namespace Molitor\User\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Molitor\User\Filament\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string
    {
        return __('user::user.edit');
    }
}

