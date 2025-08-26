<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10); // ambil semua user dengan pagination
        $usersCount = User::count(); // optional, kalau masih mau ditampilkan

        return view('admin.users.index', compact('users', 'usersCount'));
    }

    public function getUsers(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'data' => $users->items(),
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Handle profile photo upload
        if ($request->hasFile('profile')) {
            $path = $request->file('profile')->store('profiles', 'public');
            $validated['profile'] = $path;
        }

        $user = User::create($validated);

        return response()->json(['message' => 'User created successfully']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed'
            ],
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle profile photo upload
        if ($request->hasFile('profile')) {
            // Delete old profile photo if exists
            if ($user->profile) {
                Storage::disk('public')->delete($user->profile);
            }
            
            $path = $request->file('profile')->store('profiles', 'public');
            $validated['profile'] = $path;
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Delete profile photo if exists
        if ($user->profile) {
            Storage::disk('public')->delete($user->profile);
        }
        
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
