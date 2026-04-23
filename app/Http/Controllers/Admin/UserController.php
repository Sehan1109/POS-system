<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => ['required', Rule::in(['admin', 'manager', 'cashier'])],
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        
        // Log user creation with proper action type
        ActivityLog::record(
            'user_created',
            "Admin created new user: {$user->name} ({$user->email}) with role: {$user->role}",
            $user,
            [
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'created_by' => auth()->user()->name,
                'created_by_role' => auth()->user()->role
            ]
        );
        
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'  => ['required', Rule::in(['admin', 'manager', 'cashier'])],
        ]);

        // Track changes for detailed logging
        $changes = [];
        $oldData = $user->getOriginal();
        
        if ($user->name != $validated['name']) {
            $changes['name'] = ['old' => $user->name, 'new' => $validated['name']];
        }
        
        if ($user->email != $validated['email']) {
            $changes['email'] = ['old' => $user->email, 'new' => $validated['email']];
        }
        
        if ($user->role != $validated['role']) {
            $changes['role'] = ['old' => $user->role, 'new' => $validated['role']];
        }

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
            $changes['password'] = 'changed';
        }

        $user->update($validated);
        
        // Log user update with detailed changes
        if (!empty($changes)) {
            ActivityLog::record(
                'user_updated',
                "Admin updated user: {$user->name} ({$user->email}) - Changes: " . implode(', ', array_keys($changes)),
                $user,
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'changes' => $changes,
                    'updated_by' => auth()->user()->name,
                    'updated_by_role' => auth()->user()->role
                ]
            );
        }
        
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        // Store user data before deletion for logging
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'deleted_by' => auth()->user()->name,
            'deleted_by_role' => auth()->user()->role
        ];
        
        // Log user deletion before actually deleting
        ActivityLog::record(
            'user_deleted',
            "Admin deleted user: {$user->name} ({$user->email}) who had role: {$user->role}",
            null,  // Model is null because we're deleting it
            $userData
        );
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}