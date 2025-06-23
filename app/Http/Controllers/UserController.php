<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang; // <-- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Tambahkan ini
use Illuminate\Validation\Rules; // <-- Tambahkan ini
use Illuminate\Validation\Rule; // <-- Tambahkan ini

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('cabang')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cabangs = Cabang::orderBy('nama_cabang')->get();
        return view('users.create', compact('cabangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin_induk', 'admin_cabang'])],
            'cabang_id' => ['nullable', 'required_if:role,admin_cabang', 'exists:cabangs,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'cabang_id' => $request->role === 'admin_cabang' ? $request->cabang_id : null,
        ]);

        return redirect()->route('users.index')->with('success', 'User baru berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return redirect()->route('users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $cabangs = Cabang::orderBy('nama_cabang')->get();
        return view('users.edit', compact('user', 'cabangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['admin_induk', 'admin_cabang'])],
            'cabang_id' => ['nullable', 'required_if:role,admin_cabang', 'exists:cabangs,id'],
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'cabang_id' => $request->role === 'admin_cabang' ? $request->cabang_id : null,
        ];

        // Jika password diisi, hash dan tambahkan ke data update
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Mencegah user menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
