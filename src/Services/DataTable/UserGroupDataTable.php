<?php

declare(strict_types=1);

namespace Molitor\User\Services\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Molitor\DataTable\Services\DataTable;
use Molitor\DataTable\Services\DataTableColumns\BaseDataTableColumn;
use Molitor\DataTable\Services\DataTableColumns\DataTableColumn;
use Molitor\User\Models\UserGroup;

class UserGroupDataTable extends DataTable
{
    public function getModelClass(): string
    {
        return UserGroup::class;
    }

    public function getUrl(): string
    {
        return route('user.group.indexData');
    }

    protected function init(): void
    {
        $this->addDataTableColumn(new DataTableColumn('Név', 'name'));
        $this->addDataTableColumn(new DataTableColumn('Leírás', 'description'));
        $this->actions()
            ->addEdit('user.group.edit')
            ->addDestroy('api.user.group.destroy');
    }

    protected function filter(Builder $builder, string $searchTerm): Builder
    {
        return $this->buildFilter($builder, [
            'name', 'description',
        ], $searchTerm);
    }

    protected function order(Builder $builder, BaseDataTableColumn $orderByColumn = null, $direction = 'asc'): Builder
    {
        return $builder->orderBy($orderByColumn->getName(), $direction);
    }
}
