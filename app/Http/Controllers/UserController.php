<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::active()->with('cabang')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cabangs = Cabang::active()->orderBy('nama_cabang')->get();
        return view('users.create', compact('cabangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi tidak lagi memerlukan 'role', dan 'cabang_id' sekarang wajib
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cabang_id' => ['required', 'exists:cabangs,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_gudang_cabang',
            'cabang_id' => $request->cabang_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User Admin Cabang baru berhasil dibuat.');
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
        $cabangs = Cabang::active()->orderBy('nama_cabang')->get();
        return view('users.edit', compact('user', 'cabangs'));
    }

    /**
     * Memperbarui data user tanpa mengubah role.
     */
    public function update(Request $request, User $user)
    {
        // Validasi tidak lagi menyertakan 'role'
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            // Hanya validasi cabang jika user yang diedit adalah admin cabang
            'cabang_id' => $user->role === 'admin_cabang' ? ['required', 'exists:cabangs,id'] : ['nullable'],
        ]);

        $dataToUpdate = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Hanya perbarui cabang jika rolenya adalah admin_cabang
        if ($user->role === 'admin_cabang') {
            $dataToUpdate['cabang_id'] = $request->cabang_id;
        }

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui.');
    }
    public function inactive()
    {
        $users = User::where('is_active', false)->latest()->paginate(10);
        return view('users.inactive', compact('users'));
    }

    /**
     * [BARU] Mengubah status aktif/nonaktif.
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }
        $user->is_active = !$user->is_active;
        $user->save();
        $message = $user->is_active ? 'Data user berhasil diaktifkan.' : 'Data user berhasil dinonaktifkan.';
        return redirect()->back()->with('success', $message);
    }
}
