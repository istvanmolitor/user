<?php

declare(strict_types=1);

namespace Molitor\User\DataTables;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Molitor\Admin\DataTables\DataTable;
use Molitor\User\Http\Resources\PermissionGroupResource;
use Molitor\User\Http\Resources\PermissionResource;
use Molitor\User\Models\Permission;
use Molitor\User\Models\PermissionGroup;

class PermissionDataTable extends DataTable
{
    protected function getModelClass(): string
    {
        return Permission::class;
    }

    protected function getResourceClass(): string
    {
        return PermissionResource::class;
    }

    protected function initColumns(): void
    {
        $this->addColumn('name')->setSearchable()->setOrderable();
        $this->addColumn('description')->setSearchable();
    }

    protected function getBaseQuery(): Builder
    {
        return Permission::query()->with(['userGroups', 'permissionGroup']);
    }

    protected function applyFilters(Builder $query): Builder
    {
        $query = parent::applyFilters($query);

        if ($this->request->filled('permission_group_id')) {
            $query->where('permission_group_id', $this->request->integer('permission_group_id'));
        }

        return $query;
    }

    protected function getFilters(): array
    {
        return array_merge(parent::getFilters(), [
            'permission_group_id' => $this->request->input('permission_group_id'),
        ]);
    }

    protected function getResourceAdditionals(LengthAwarePaginator $data): array
    {
        return array_merge(parent::getResourceAdditionals($data), [
            'permission_groups' => PermissionGroupResource::collection(
                PermissionGroup::query()->orderBy('name')->get(['id', 'name'])
            ),
        ]);
    }
}
