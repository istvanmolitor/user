<?php

namespace Molitor\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Molitor\Admin\Http\Resources\FormResource;
use Molitor\User\Models\User;
use Molitor\User\Repositories\MembershipRepositoryInterface;
use Molitor\User\Repositories\UserGroupRepositoryInterface;

class UserFormResource extends FormResource
{

    private array $userGroupIds;

    public function __construct(User $resource = null)
    {
        parent::__construct($resource);
        $this->userGroupIds = $this->getUserGroupIds();
    }

    public function getUserGroupIds(): array
    {
        if ($this->resource) {
            return app(MembershipRepositoryInterface::class)->getUserGroupIdsByUser($this->resource);
        } else {
            return [];
        }
    }

    protected function getFormValues($request)
    {
        $user = new UserResource($this->resource);
        $user->merge([
            'user_group_ids' => $this->userGroupIds
        ]);
        return $user;
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
