<?php

declare(strict_types=1);

namespace Molitor\User\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Molitor\Admin\DataTables\DataTable;
use Molitor\User\Http\Resources\UserResource;
use Molitor\User\Models\User;

class UserDataTable extends DataTable
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    protected function getResourceClass(): string
    {
        return UserResource::class;
    }

    protected function initColumns(): void
    {
        $this->addColumn('name')->setSearchable()->setOrderable();
        $this->addColumn('email')->setSearchable()->setOrderable();
    }

    public function query(Builder $query): Builder
    {
        return $query->with('userGroups');
    }
}
