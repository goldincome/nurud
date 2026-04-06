<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\CustomerType;
use App\Http\Requests\AdminUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // List only admins and superadmins
        $admins = User::whereIn('type', [CustomerType::ADMIN, CustomerType::SUPERADMIN])
            ->latest()
            ->paginate(10);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => CustomerType::ADMIN, // Default to ADMIN
            'phone_no' => $request->phone_no,
        ]);

        event(new Registered($user));

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin user created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = User::findOrFail($id);

        // Prevent editing self via this controller if needed, or allow
        // Ensure strictly admin/superadmin
        if (!in_array($admin->type, [CustomerType::ADMIN, CustomerType::SUPERADMIN])) {
            abort(404);
        }

        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        if (!in_array($user->type, [CustomerType::ADMIN, CustomerType::SUPERADMIN])) {
            abort(404);
        }

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin user updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting oneself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        // Prevent deleting other super admins might be wise, but keeping it simple for now as per request "Super admin can delete"
        if (!in_array($user->type, [CustomerType::ADMIN, CustomerType::SUPERADMIN])) {
            abort(404);
        }

        $user->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin user deleted successfully.');
    }
}
