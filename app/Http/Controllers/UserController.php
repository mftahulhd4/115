<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // <--- TAMBAHKAN BARIS INI

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('role', 'like', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', Rule::in(['admin', 'pengurus'])],
            ]);

        } catch (ValidationException $e) {
            return redirect()->back()
                             ->withErrors($e->errors())
                             ->withInput();
        }

        $dataToSave = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'email_verified_at' => now(), // Otomatis verifikasi email untuk pengguna yang dibuat admin
        ];

        try {
            $user = User::create($dataToSave);
            $successMessage = 'Pengguna baru (' . $user->name . ') dengan peran ' . $user->role . ' berhasil ditambahkan!';
            return redirect()->route('users.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Gagal menyimpan pengguna baru ke database: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan internal saat menambahkan pengguna baru. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Biasanya tidak ada halaman show detail untuk user, kecuali diperlukan.
        // Jika tidak, bisa redirect ke edit atau index.
        return redirect()->route('users.edit', $user->id); // Pastikan $user->id atau $user saja tergantung konfigurasi rute
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
                'role' => ['required', Rule::in(['admin', 'pengurus'])],
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()
                             ->withErrors($e->errors())
                             ->withInput();
        }

        $dataToUpdate = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
        ];

        if (!empty($validatedData['password'])) {
            $dataToUpdate['password'] = Hash::make($validatedData['password']);
        }

        try {
            $user->update($dataToUpdate);
            $successMessage = 'Data pengguna (' . $user->name . ') berhasil diperbarui!';
            return redirect()->route('users.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Gagal mengupdate data pengguna: ' . $e->getMessage(), ['user_id' => $user->id, 'exception' => $e]);
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan internal saat memperbarui data pengguna.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) { // Menggunakan Auth facade
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() === 1) {
             return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus satu-satunya akun admin.');
        }

        try {
            $userName = $user->name;
            $user->delete();
            Log::info('Pengguna (' . $userName . ') berhasil dihapus dari database.'); // Logging tambahan
            return redirect()->route('users.index')->with('success', 'Pengguna (' . $userName . ') berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pengguna: ' . $e->getMessage(), ['user_id' => $user->id, 'exception' => $e]);
            return redirect()->route('users.index')->with('error', 'Terjadi kesalahan saat menghapus pengguna.');
        }
    }
}