<?php
namespace Molitor\User\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Molitor\Admin\Controllers\BaseAdminController;
use Molitor\Admin\Traits\HasAdminFilters;
use Molitor\User\Http\Requests\StoreUserRequest;
use Molitor\User\Http\Requests\UpdateUserRequest;
use Molitor\User\Models\User;
use Molitor\User\Models\UserGroup;

class UserController extends BaseAdminController
{
    use HasAdminFilters;

    public function index(Request $request): Response
    {
        $query = User::with('userGroups');
        $users = $this->applyAdminFilters($query, $request, ['name', 'email'])
            ->paginate(10)
            ->withQueryString();

        return $this->view('Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search', 'sort', 'direction']),
        ]);
    }
    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', [
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
        return Inertia::render('Admin/Users/Edit', [
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
