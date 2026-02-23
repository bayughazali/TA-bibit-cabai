<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        try {
            // Ambil semua users dari database dengan pagination
            $users = User::orderBy('id', 'desc')->paginate(15);
            
            // Hitung statistik
            $totalUsers = User::count();
            $totalAdmins = User::where('is_admin', 1)->count();
            $verifiedUsers = User::whereNotNull('email_verified_at')->count();
            $activeUsers = User::where('created_at', '>=', now()->subDays(30))->count();
            
            // Debug: Log jumlah users
            \Log::info('Total users in database: ' . $totalUsers);
            \Log::info('Users retrieved: ' . $users->count());
            
            // Data yang akan dikirim ke view
            $data = [
                'users' => $users,
                'totalUsers' => $totalUsers,
                'totalAdmins' => $totalAdmins,
                'verifiedUsers' => $verifiedUsers,
                'activeUsers' => $activeUsers
            ];
            
            // Coba cari view di lokasi yang berbeda
            if (view()->exists('admin.users.index')) {
                return view('admin.users.index', $data);
            }
            
            return view('admin.users', $data);
            
        } catch (\Exception $e) {
            \Log::error('Error in UserController@index: ' . $e->getMessage());
            
            // Jika error, kirim data kosong
            return view('admin.users', [
                'users' => collect()->paginate(15),
                'totalUsers' => 0,
                'totalAdmins' => 0,
                'verifiedUsers' => 0,
                'activeUsers' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'is_admin' => ['boolean']
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_admin' => $request->has('is_admin') ? 1 : 0,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified user in database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'is_admin' => ['boolean']
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_admin' => $request->has('is_admin') ? 1 : 0,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()]
            ]);
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui');
    }

    /**
     * Remove the specified user from database.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}