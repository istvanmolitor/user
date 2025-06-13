<?php

declare(strict_types=1);

namespace Molitor\User\Services\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Molitor\DataTable\Services\DataTable;
use Molitor\DataTable\Services\DataTableColumns\BaseDataTableColumn;
use Molitor\DataTable\Services\DataTableColumns\DataTableColumn;
use Molitor\User\Models\Permission;

class PermissionDataTable extends DataTable
{
    public function getModelClass(): string
    {
        return Permission::class;
    }

    public function getUrl(): string
    {
        return route('permission.indexData');
    }

    protected function init(): void
    {
        $this->addDataTableColumn(new DataTableColumn('Név', 'name'));
        $this->addDataTableColumn(new DataTableColumn('Leírás', 'description'));
        $this->actions()
            ->addEdit('permission.edit')
            ->addDestroy('api.permission.destroy');
    }

    protected function filter(Builder $builder, string $searchTerm): Builder
    {
        return $this->buildFilter($builder, [
            'name', 'description'
        ], $searchTerm);
    }

    protected function order(Builder $builder, BaseDataTableColumn $orderByColumn = null, $direction = 'asc'): Builder
    {
        return $builder->orderBy($orderByColumn->getName(), $direction);
    }
}
