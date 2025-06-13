<?php

namespace Molitor\User\Http\Resources;

use Molitor\Admin\Http\Resources\FormResource;
use Molitor\User\Models\Permission;
use Molitor\User\Repositories\UserGroupPermissionRepositoryInterface;
use Molitor\User\Repositories\UserGroupRepositoryInterface;

class PermissionFormResource extends FormResource
{
    private array $userGroupIds;

    public function __construct(Permission $resource = null)
    {
        parent::__construct($resource);
        $this->userGroupIds = $this->getUserGroupIds();
    }

    public function getUserGroupIds(): array
    {
        if ($this->resource) {
            return app(UserGroupPermissionRepositoryInterface::class)->getUserGroupIdsByPermission($this->resource);
        } else {
            return [];
        }
    }

    protected function getFormValues($request): array
    {
        $resource = new PermissionResource($this->resource);
        return array_merge($resource->toArray($request), [
            'user_group_ids' => $this->userGroupIds,
        ]);
    }

    protected function getFormData($request)
    {
        $userGroupRepository = app(UserGroupRepositoryInterface::class);

        $userGroups = [];
        foreach ($userGroupRepository->getAll() as $userGroup) {
            $userGroups[] = [
                'id' => $userGroup->id,
                'name' => $userGroup->name,
                'value' => in_array($userGroup->id, $this->userGroupIds),
            ];
        }
        return [
            'userGroups' => $userGroups,
        ];
    }
}
