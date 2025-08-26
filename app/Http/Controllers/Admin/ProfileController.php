<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the profile.
     */
    public function update(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $validator = \Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'      => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Update info dasar
            $user->name  = $request->name;
            $user->email = $request->email;

            // Update password jika ada
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Upload foto profil
         if ($request->hasFile('profile_photo')) {
    // Hapus foto lama jika ada
    if ($user->profile) {
        Storage::disk('public')->delete($user->profile);
    }

    // Simpan baru
    $path = $request->file('profile_photo')->store('profile-photos', 'public');
    $user->profile = $path; // path akan disimpan di DB, misal "profile-photos/abc.jpg"
}


            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'data'    => [
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'profile_url' => $user->profile ? Storage::url($user->profile) : null
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil: ' . $e->getMessage()
            ], 500);
        }
    }
}
