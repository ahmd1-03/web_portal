<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function index()
    {
        $users = Admin::orderBy('created_at', 'desc')->paginate(10);
        return View::make('admin.users.index', compact('users'));
    }

    public function create()
    {
        // TODO: Return view to create a new admin user
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        Admin::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengguna berhasil ditambahkan.']);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $user->id,
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengguna berhasil diperbarui.']);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Admin::findOrFail($id);
        $user->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Pengguna berhasil dihapus.']);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
