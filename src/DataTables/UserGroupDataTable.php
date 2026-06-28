<?php

declare(strict_types=1);

namespace Molitor\User\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Molitor\Admin\DataTables\DataTable;
use Molitor\User\Http\Resources\UserGroupResource;
use Molitor\User\Models\UserGroup;

class UserGroupDataTable extends DataTable
{
    protected function getModelClass(): string
    {
        return UserGroup::class;
    }

    protected function getResourceClass(): string
    {
        return UserGroupResource::class;
    }

    protected function getSearchPlaceholder(): string
    {
        return 'Keresés név alapján...';
    }

    protected function initColumns(): void
    {
        $this->addColumn('name')->setSearchable()->setOrderable();
        $this->addColumn('description')->setSearchable();
    }

    public function query(Builder $query): Builder
    {
        return $query->with('permissions');
    }
}
