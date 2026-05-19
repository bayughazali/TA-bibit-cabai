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
    
    $users = User::orderBy('id', 'desc')->paginate(15);
    
    $totalUsers   = User::count();
    $totalAdmins  = User::where('is_admin', 1)->count();
    $verifiedUsers = User::whereNotNull('email_verified_at')->count();
    $activeUsers  = User::where('created_at', '>=', now()->subDays(30))->count();
    // dd($users->count(), $totalUsers);

    return view('admin.users', compact(
        'users', 'totalUsers', 'totalAdmins', 'verifiedUsers', 'activeUsers'
    ));
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
    $request->validate([
        'name'     => [
            'required', 'string', 'min:8',
            'regex:/^[a-zA-Z\s]+$/'  // hanya huruf dan spasi
        ],
        'email'    => [
            'required', 'string', 'min:8', 'email', 'max:255', 'unique:users',
            'regex:/^[^\s]+@gmail\.com$/'  // tidak boleh spasi, harus @gmail.com
        ],
        'password' => [
            'required', 'confirmed', 'min:8',
            'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])\S+$/'  // huruf+angka, no spasi
        ],
        'phone'    => [
            'required', 'string', 'min:12',
            'regex:/^(\+62)[0-9]+$/'  // harus diawali +62, hanya angka
        ],
        'address'  => ['required', 'string', 'min:10'],
    ], [
        'name.required'     => 'Nama lengkap wajib diisi.',
        'name.min'          => 'Nama minimal 8 karakter.',
        'name.regex'        => 'Nama hanya boleh menggunakan huruf dan spasi.',

        'email.required'    => 'Email wajib diisi.',
        'email.min'         => 'Email minimal 8 karakter.',
        'email.unique'      => 'Email sudah digunakan.',
        'email.regex'       => 'Email tidak boleh mengandung spasi dan harus menggunakan @gmail.com.',

        'password.required' => 'Password wajib diisi.',
        'password.min'      => 'Password minimal 8 karakter.',
        'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        'password.regex'    => 'Password tidak boleh mengandung spasi dan harus terdiri dari huruf dan angka.',

        'phone.required'    => 'Nomor telepon wajib diisi.',
        'phone.min'         => 'Nomor telepon minimal 12 karakter.',
        'phone.regex'       => 'Nomor telepon harus diawali +62 dan hanya boleh berisi angka.',

        'address.required'  => 'Alamat wajib diisi.',
        'address.min'       => 'Alamat minimal 10 karakter.',
    ]);

    User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'phone'    => $request->phone,
        'address'  => $request->address,
        'is_admin' => $request->has('is_admin') ? 1 : 0,
    ]);

    return redirect()->route('admin.users')
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
    // Hanya boleh edit akun sendiri
    if ($user->id !== auth()->id()) {
        return redirect()->route('admin.users')
            ->with('error', 'Anda tidak dapat mengedit akun pengguna lain.');
    }

    return view('admin.users.edit', compact('user'));
}

    /**
     * Update the specified user in database.
     */
   public function update(Request $request, User $user)
{

 // Hanya boleh update akun sendiri
    if ($user->id !== auth()->id()) {
        return redirect()->route('admin.users')
            ->with('error', 'Anda tidak dapat mengedit akun pengguna lain.');
    }

    $request->validate([
        'name'    => [
            'required', 'string', 'min:8',
            'regex:/^[a-zA-Z\s]+$/'
        ],
        'email'   => [
            'required', 'string', 'min:8', 'max:255',
            'unique:users,email,' . $user->id,
            'regex:/^[^\s]+@gmail\.com$/'
        ],
        'phone'   => [
            'required', 'string', 'min:12',
            'regex:/^(\+62)[0-9]+$/'
        ],
        'address' => ['required', 'string', 'min:10'],
    ], [
        'name.required'  => 'Nama lengkap wajib diisi.',
        'name.min'       => 'Nama minimal 8 karakter.',
        'name.regex'     => 'Nama hanya boleh menggunakan huruf dan spasi.',

        'email.required' => 'Email wajib diisi.',
        'email.min'      => 'Email minimal 8 karakter.',
        'email.unique'   => 'Email sudah digunakan.',
        'email.regex'    => 'Email tidak boleh mengandung spasi dan harus menggunakan @gmail.com.',

        'phone.required' => 'Nomor telepon wajib diisi.',
        'phone.min'      => 'Nomor telepon minimal 12 karakter.',
        'phone.regex'    => 'Nomor telepon harus diawali +62 dan hanya boleh berisi angka.',

        'address.required' => 'Alamat wajib diisi.',
        'address.min'      => 'Alamat minimal 10 karakter.',
    ]);

    $data = [
        'name'     => $request->name,
        'email'    => $request->email,
        'phone'    => $request->phone,
        'address'  => $request->address,
        'is_admin' => $request->has('is_admin') ? 1 : 0,
    ];

    if ($request->filled('password')) {
        $request->validate([
            'password' => [
                'confirmed', 'min:8',
                'regex:/^(?=.*[a-zA-Z])(?=.*[0-9])\S+$/'
            ],
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex'     => 'Password tidak boleh mengandung spasi dan harus terdiri dari huruf dan angka.',
        ]);
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('admin.users')
        ->with('success', 'Pengguna berhasil diperbarui');
}

    /**
     * Remove the specified user from database.
     */
   public function destroy(User $user)
{
    // Tidak bisa hapus akun siapapun selain diri sendiri
    if (auth()->id() !== $user->id) {
        return back()->with('error', 'Anda tidak dapat menghapus akun pengguna lain.');
    }

    // Tidak bisa hapus akun sendiri
    if (auth()->id() === $user->id) {
        return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
    }

    $user->delete();

    return redirect()->route('admin.users')
        ->with('success', 'Pengguna berhasil dihapus');
}
}