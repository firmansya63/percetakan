<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    /**
     * Hanya owner yang boleh mengelola user.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('role:owner'),
        ];
    }

    /**
     * Roles yang boleh dipilih dari menu manajemen user.
     *
     * @return array<int, string>
     */
    private function assignableRoles(): array
    {
        return Role::query()->orderBy('name')->pluck('name')->all();
    }

    public function index()
    {
        $users = User::with('roles')->orderBy('name')->paginate(10);

        return view('user.index', compact('users'));
    }

    public function create()
    {
        $roles = $this->assignableRoles();

        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in($this->assignableRoles())],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->syncRoles([$validated['role']]);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = $this->assignableRoles();

        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $isSelf = $request->user()->is($user);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ];

        // Role akun sendiri tidak boleh diubah agar owner tidak kehilangan akses.
        if (! $isSelf) {
            $rules['role'] = ['required', 'string', Rule::in($this->assignableRoles())];
        }

        $validated = $request->validate($rules);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        if (! $isSelf) {
            $user->syncRoles([$validated['role']]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->is($user)) {
            return redirect()->route('user.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Cegah owner terakhir terhapus sehingga tidak ada yang bisa mengelola user.
        if ($user->hasRole('owner') && User::role('owner')->count() <= 1) {
            return redirect()->route('user.index')->with('error', 'Owner terakhir tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
