<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

        public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $serviceOptions = \App\Service::all()
            ->groupBy('name')
            ->mapWithKeys(function ($group, $name) {
                return [$group->first()->id => $name];
            });

        return view('admin.users.create', compact('roles', 'serviceOptions'));
    }

    public function store(StoreUserRequest $request)
    {
        // 1. Simpan user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        // 2. Assign role
        $user->roles()->sync($request->input('roles'));

        // 3. Cek role yang terpasang
        $role = $user->roles()->first()?->title;

        // 4. Insert ke tabel lain sesuai role
        if ($role === 'Pelatih') {
        \App\Employee::create([
            'name' => $user->name,
            'username' => $user->username,
            'user_id' => $user->id,
            'phone' => $request->input('employee_phone'), // ambil dari input form
            ]);
        }

        if ($role === 'Murid') {
        $client = \App\Client::create([
            'name' => $user->name,
            'username' => $user->username,
            'user_id' => $user->id,
            'phone' => $request->input('client_phone'), // ambil dari input form
            'kuota' => $request->input('client_kuota') ?? 0, // default 0 jika kosong
            ]);

        // Sinkronisasi services (paket latihan) ke pivot table client_service
        $client->services()->sync($request->input('client_services', []));
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = \App\Role::all()->pluck('title', 'id');
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
