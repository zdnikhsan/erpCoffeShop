<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $users = User::query()
            ->with('roles')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name')->pluck('name', 'name');

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Hubungkan role yang dipilih menggunakan Spatie
        $user->assignRole($request->role);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->pluck('name', 'name');

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Jika password diisi, enkripsi dan perbarui
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Sync role menggunakan Spatie (menghapus role lama, assign role baru)
        $user->syncRoles($request->role);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified user from storage.
     *
     * Failsafe protections:
     * 1. User tidak boleh menghapus dirinya sendiri.
     * 2. User tidak boleh dihapus jika terikat di tabel orders (cashier_id).
     * 3. User tidak boleh dihapus jika terikat di tabel stock_opnames.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Proteksi 1: Tidak boleh menghapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        // Proteksi 2: Cek apakah user terikat sebagai kasir di tabel orders
        $orderCount = \App\Models\Order::where('cashier_id', $user->id)->count();
        if ($orderCount > 0) {
            return redirect()
                ->route('users.index')
                ->with('error', "User \"{$user->name}\" tidak dapat dihapus karena masih terikat dengan {$orderCount} transaksi order.");
        }

        // Proteksi 3: Cek apakah user terikat di tabel stock_opnames (jika tabel sudah ada)
        if (\Illuminate\Support\Facades\Schema::hasTable('stock_opnames')) {
            $stockOpnameCount = \Illuminate\Support\Facades\DB::table('stock_opnames')
                ->where('user_id', $user->id)
                ->count();

            if ($stockOpnameCount > 0) {
                return redirect()
                    ->route('users.index')
                    ->with('error', "User \"{$user->name}\" tidak dapat dihapus karena masih terikat dengan {$stockOpnameCount} data stock opname.");
            }
        }

        // Hapus semua roles sebelum menghapus user
        $user->syncRoles([]);
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
