<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('prodi')->orderBy('name')->paginate(20);
        $prodis = Prodi::orderBy('nama_prodi')->get();
        return view('user.index', compact('users', 'prodis'));
    }

    public function create()
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        return view('user.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:kepsek,waka_sarpras,ka_tu,ka_prodi',
            'prodi_id' => 'required_if:role,ka_prodi|nullable|exists:prodi,id',
            'phone' => 'nullable|string|max:20',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['is_active'] = true;

        User::create($data);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        return view('user.edit', compact('user', 'prodis'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:kepsek,waka_sarpras,ka_tu,ka_prodi',
            'prodi_id' => 'required_if:role,ka_prodi|nullable|exists:prodi,id',
            'phone' => 'nullable|string|max:20',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function toggleActive(User $user)
    {
        $user->update(['is_active' => ! $user->is_active]);
        return back()->with('success', 'Status user berhasil diubah.');
    }
}
