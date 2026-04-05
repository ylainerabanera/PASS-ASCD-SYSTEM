<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('id')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (User::count() > 0) {
            return redirect()->route('users.index')->with('status', 'Only one admin user is allowed.');
        }

        return view('users.create');
    }

    public function store(Request $request)
    {
        if (User::count() > 0) {
            return redirect()->route('users.index')->with('status', 'Only one admin user is allowed.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => true,
        ]);

        return redirect()->route('users.index')->with('status', 'Admin user created successfully.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        return redirect()->route('users.index')->with('status', 'Admin user cannot be deleted.');
    }
}
