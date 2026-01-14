<?php
namespace Molitor\User\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Molitor\Admin\Controllers\BaseAdminController;
use Molitor\User\Http\Requests\StoreUserRequest;
use Molitor\User\Http\Requests\UpdateUserRequest;
use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;
class UserController extends BaseAdminController
{
    public function index(Request $request): Response
    {
        $users = User::with('userGroups')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->input('sort'), function ($query, $sort) use ($request) {
                $direction = $request->input('direction', 'asc');
                $query->orderBy($sort, $direction);
            }, function ($query) {
                $query->orderBy('name', 'asc');
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('User/Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }
    public function create(): Response
    {
        return Inertia::render('User/Admin/Users/Create', [
            'userGroups' => UserGroup::all(),
        ]);
    }
    public function store(StoreUserRequest $request)
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
        return redirect()->route('user.admin.users.index')
            ->with('success', __('user::user.messages.created'));
    }
    public function edit(User $user): Response
    {
        $user->load('userGroups');
        return Inertia::render('User/Admin/Users/Edit', [
            'user' => $user,
            'userGroups' => UserGroup::all(),
        ]);
    }
    public function update(UpdateUserRequest $request, User $user)
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
        return back()->with('success', __('user::user.messages.updated'));
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.admin.users.index')
            ->with('success', __('user::user.messages.deleted'));
    }
}
