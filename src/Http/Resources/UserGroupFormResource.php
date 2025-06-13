<?php

namespace Molitor\User\Http\Resources;

use Molitor\Admin\Http\Resources\FormResource;
use Molitor\User\Models\UserGroup;
use Molitor\User\Repositories\PermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupPermissionRepositoryInterface;

class UserGroupFormResource extends FormResource
{
    private array $permissionIds;

    public function __construct(UserGroup $resource = null)
    {
        parent::__construct($resource);
        $this->permissionIds = $this->getPermissionIds();
    }

    public function getPermissionIds(): array
    {
        if ($this->resource) {
            return app(UserGroupPermissionRepositoryInterface::class)->getPermissionIdsByUserGroup($this->resource);
        } else {
            return [];
        }
    }

    protected function getFormValues($request)
    {
        $resource = new UserGroupResource($this->resource);
        $resource->merge([
            'permission_ids' => $this->permissionIds,
        ]);
        return $resource;
    }

    protected function getFormData($request)
    {
        $permissionRepository = app(PermissionRepositoryInterface::class);

        $permissions = [];
        foreach ($permissionRepository->getAll() as $permission) {
            $permissions[] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'value' => in_array($permission->id, $this->permissionIds),
            ];
        }
        return [
            'permissions' => $permissions,
        ];
    }
}
