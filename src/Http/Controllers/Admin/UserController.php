<?php
namespace Molitor\User\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Molitor\Admin\Controllers\BaseAdminController;
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
            ->paginate(10)
            ->withQueryString();
        return Inertia::render('User/Admin/Users/Index', [
            'users' => $users,
            'filters' => $request->only(['search']),
        ]);
    }
    public function create(): Response
    {
        return Inertia::render('User/Admin/Users/Create', [
            'userGroups' => UserGroup::all(),
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_groups' => 'array',
            'user_groups.*' => 'exists:user_groups,id',
            'email_verified' => 'boolean',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
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
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'user_groups' => 'array',
            'user_groups.*' => 'exists:user_groups,id',
            'email_verified' => 'boolean',
        ]);
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'email_verified_at' => $validated['email_verified'] ?? false ? now() : null,
        ]);
        if (isset($validated['password'])) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }
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
