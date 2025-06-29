<?php

namespace App\Http\Controllers\Admin;

// Controller untuk manajemen data admin (CRUD)

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    // Menampilkan daftar admin dengan fitur pencarian
    public function index(Request $request)
    {
        $query = Admin::query();

        // Jika ada parameter pencarian, filter berdasarkan nama atau email
        if ($request->has('search') && $request->search != '') {
            $search = trim(preg_replace('/\s+/', ' ', $request->search)); // Normalize spaces
            $words = explode(' ', $search);
            $query->where(function ($q) use ($search, $words) {
                // Search for the whole phrase
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
                // Also search for each individual word
                foreach ($words as $word) {
                    $q->orWhere('name', 'like', '%' . $word . '%')
                      ->orWhere('email', 'like', '%' . $word . '%');
                }
            });
        }

        // Ambil data admin dengan urutan terbaru dan paginasi 10 per halaman
        $users = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->only('search'));
        return View::make('admin.users.index', compact('users'));
    }

    // (Opsional) Form buat manual (tidak dipakai karena pakai modal)
    public function create()
    {
        //
    }

    // Menyimpan admin baru dengan validasi
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

        // Enkripsi password sebelum disimpan
        $validated['password'] = bcrypt($validated['password']);

        // Simpan data admin baru
        Admin::create($validated);

        // Jika request ingin JSON, kembalikan response JSON
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengguna berhasil ditambahkan.']);
        }

        // Redirect ke halaman daftar admin dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // Mengambil data pengguna untuk modal edit (JSON)
    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        return response()->json($user);
    }

    // Menyimpan perubahan data pengguna dengan validasi
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

        // Jika password diisi, enkripsi, jika tidak hapus dari data validasi
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update data pengguna
        $user->update($validated);

        // Jika request ingin JSON, kembalikan response JSON
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengguna berhasil diperbarui.']);
        }

        // Redirect ke halaman daftar admin dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // Menghapus data pengguna
    public function destroy($id)
    {
        $user = Admin::findOrFail($id);
        $user->delete();

        // Jika request ingin JSON, kembalikan response JSON
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Pengguna berhasil dihapus.']);
        }

        // Redirect ke halaman daftar admin dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
