<?php

declare(strict_types=1);

namespace Molitor\User\Services\DataTable;

use Illuminate\Database\Eloquent\Builder;
use Molitor\DataTable\Services\DataTable;
use Molitor\DataTable\Services\DataTableColumns\BaseDataTableColumn;
use Molitor\DataTable\Services\DataTableColumns\DataTableColumn;
use Molitor\User\Models\User;

class UserDataTable extends DataTable
{
    public function getModelClass(): string
    {
        return User::class;
    }

    public function getUrl(): string
    {
        return route('user.indexData');
    }

    protected function init(): void
    {
        $this->addDataTableColumn(new DataTableColumn('E-mail', 'email'));
        $this->addDataTableColumn(new DataTableColumn('Név', 'name'));
        $this->actions()->addAll('user');
    }

    protected function filter(Builder $builder, string $searchTerm): Builder
    {
        return $this->buildFilter($builder, [
            'name', 'email',
        ], $searchTerm);
    }

    protected function order(Builder $builder, BaseDataTableColumn $orderByColumn = null, $direction = 'asc'): Builder
    {
        return $builder->orderBy($orderByColumn->getName(), $direction);
    }
}
