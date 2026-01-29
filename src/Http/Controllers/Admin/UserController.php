<?php
namespace Molitor\User\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\User\Http\Requests\StoreUserRequest;
use Molitor\User\Http\Requests\UpdateUserRequest;
use Molitor\User\Http\Resources\UserResource;
use Molitor\User\Http\Resources\UserGroupResource;
use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;

class UserController extends Controller
{
    use HasAdminFilters;

    public function index(Request $request): JsonResponse
    {
        $query = User::with('userGroups');
        $users = $this->applyAdminFilters($query, $request, ['name', 'email'])
            ->paginate(10)
            ->withQueryString();

        return response()->json([
            'data' => UserResource::collection($users->items()),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }
    public function create(): JsonResponse
    {
        return response()->json([
            'user_groups' => UserGroupResource::collection(UserGroup::all()),
        ]);
    }
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt(\Illuminate\Support\Str::random(32)),
            'email_verified_at' => $validated['email_verified'] ?? false ? now() : null,
        ]);
        if (isset($validated['user_groups'])) {
            $user->userGroups()->sync($validated['user_groups']);
        }

        $user->load('userGroups');

        return response()->json([
            'data' => new UserResource($user),
            'message' => __('user::user.messages.created'),
        ], 201);
    }
    public function edit(User $user): JsonResponse
    {
        $user->load('userGroups');

        return response()->json([
            'data' => new UserResource($user),
            'user_groups' => UserGroupResource::collection(UserGroup::all()),
        ]);
    }
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => $validated['email_verified'] ?? false ? now() : null,
        ]);
        if (isset($validated['user_groups'])) {
            $user->userGroups()->sync($validated['user_groups']);
        }

        $user->load('userGroups');

        return response()->json([
            'data' => new UserResource($user),
            'message' => __('user::user.messages.updated'),
        ]);
    }
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => __('user::user.messages.deleted'),
        ]);
    }
}
