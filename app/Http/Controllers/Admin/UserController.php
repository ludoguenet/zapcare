<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('specialties')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $specialties = \App\Models\Specialty::all();
        return view('admin.users.edit', compact('user', 'specialties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_doctor' => 'boolean',
            'specialties' => 'array',
            'specialties.*' => 'exists:specialties,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_doctor' => $request->has('is_doctor'),
        ]);

        if ($user->is_doctor && isset($validated['specialties'])) {
            $user->specialties()->sync($validated['specialties']);
        } else {
            $user->specialties()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
}
