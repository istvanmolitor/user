<?php

namespace Molitor\User\Http\Controllers\Api;

use Illuminate\Support\Facades\Hash;
use Molitor\Admin\Http\Controllers\Admin\BaseApiController;
use Molitor\User\Http\Requests\UserCreateRequest;
use Molitor\User\Http\Requests\UserRequest;
use Molitor\User\Http\Resources\UserFormResource;
use Molitor\User\Http\Resources\UserResource;
use Molitor\User\Models\User;
use Molitor\User\Repositories\MembershipRepositoryInterface;
use Molitor\User\Repositories\UserGroupRepositoryInterface;
use Molitor\User\Repositories\UserRepositoryInterface;

class UserApiController extends BaseApiController
{
    public function __construct(
        private UserRepositoryInterface       $userRepository,
        private UserGroupRepositoryInterface  $userGroupRepository,
        private MembershipRepositoryInterface $membershipRepository
    )
    {
    }

    public function index()
    {
        return UserResource::collection($this->userRepository->getAll());
    }

    public function create()
    {
        return new UserFormResource(new User());
    }

    public function edit(User $user)
    {
        return new UserFormResource($user);
    }

    public function store(UserCreateRequest $request)
    {
        $user = User::create([
            'is_banned' => $request->is_banned,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        foreach ($this->userGroupRepository->getAll() as $userGroup) {
            $this->membershipRepository->set($userGroup, $user, in_array($userGroup->id, $request->user_group_ids));
        }

        $resource = new UserFormResource($user);
        $resource->setSuccessMessage('A felhasználói létrejött.');
        $resource->setRedirect(route('user.show', [$user]));
        return $resource;
    }

    public function update(User $user, UserRequest $request)
    {
        $user->name = $request->name;
        $user->is_banned = $request->is_banned;
        $user->save();

        foreach ($this->userGroupRepository->getAll() as $userGroup) {
            $this->membershipRepository->set($userGroup, $user, in_array($userGroup->id, $request->user_group_ids));
        }

        $resource = new UserFormResource($user);
        $resource->setSuccessMessage('A felhasználó el lett mentve.');
        $resource->setRedirect(route('user.show', [$user]));
        return $resource;
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->userRepository->delete($user);

        $resource = new UserFormResource();
        $resource->setSuccessMessage('A felhasználó törölve lett.');
        $resource->setRedirect(route('user.index'));
        return $resource;
    }
}
