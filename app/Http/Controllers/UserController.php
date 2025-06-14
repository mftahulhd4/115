<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'pengurus'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil dibuat.');
    }

    /**
     * (Opsional) Menampilkan detail pengguna.
     * Biasanya redirect ke form edit.
     */
    public function show(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'pengurus'])],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database.
     * INI ADALAH METHOD YANG HILANG
     */
    public function destroy(User $user)
    {
        // Logika keamanan 1: Jangan biarkan user menghapus akunnya sendiri
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Logika keamanan 2: Jangan biarkan admin terakhir dihapus
        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
             return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus satu-satunya akun admin yang tersisa.');
        }

        $userName = $user->name;
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'Pengguna (' . $userName . ') berhasil dihapus.');
    }
}