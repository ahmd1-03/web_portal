<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    // Menampilkan daftar admin
    public function index(Request $request)
    {
        $query = Admin::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->only('search'));
        return View::make('admin.users.index', compact('users'));
    }

    // (Opsional) Form buat manual (tidak dipakai karena pakai modal)
    public function create()
    {
        //
    }

    // Menyimpan admin baru
    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf kapital, huruf kecil, angka, dan simbol khusus (!@#$%^&*).',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+$/'
            ], // validasi dengan konfirmasi dan regex kompleksitas
        ], $messages);

        $validated['password'] = bcrypt($validated['password']);

        Admin::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengguna berhasil ditambahkan.']);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Mengambil data pengguna untuk modal edit
    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        return response()->json($user);
    }

    // Menyimpan perubahan data pengguna
    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $messages = [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf kapital, huruf kecil, angka, dan simbol khusus (!@#$%^&*).',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*]).+$/'
            ], // validasi jika password diisi
        ], $messages);

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

    // Menghapus data pengguna
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
