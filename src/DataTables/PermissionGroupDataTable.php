<?php

declare(strict_types=1);

namespace Molitor\User\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Molitor\Admin\DataTables\DataTable;
use Molitor\User\Http\Resources\PermissionGroupResource;
use Molitor\User\Models\PermissionGroup;

class PermissionGroupDataTable extends DataTable
{
    protected function getModelClass(): string
    {
        return PermissionGroup::class;
    }

    protected function getResourceClass(): string
    {
        return PermissionGroupResource::class;
    }

    protected function initColumns(): void
    {
        $this->addColumn('name')->setSearchable()->setOrderable();
    }

    protected function getBaseQuery(): Builder
    {
        return PermissionGroup::query()->withCount('permissions');
    }
}
