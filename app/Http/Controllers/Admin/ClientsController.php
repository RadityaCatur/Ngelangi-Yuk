<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Service;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Client::with(['user', 'services'])
                ->select(sprintf('%s.*', (new \App\Client)->getTable()));
    
            $table = \Yajra\DataTables\Facades\DataTables::of($query);
    
            // Placeholder pakai closure
            $table->addColumn('placeholder', function () {
                return '&nbsp;';
            });
    
            // Actions via closure, bukan string
            $table->addColumn('actions', function ($row) {
                $viewGate      = 'client_show';
                $editGate      = 'client_edit';
                $deleteGate    = 'client_delete';
                $crudRoutePart = 'clients';
    
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
    
            // Kolom lainnya
            $table->editColumn('id', function ($row) {
                return $row->id ?? '';
            });
    
            $table->addColumn('name', function ($row) {
                return optional($row->user)->name ?? '';
            });
    
            $table->addColumn('username', function ($row) {
                return optional($row->user)->username ?? '';
            });
    
            $table->editColumn('phone', function ($row) {
                return $row->phone ?? '';
            });
    
            $table->editColumn('services', function ($row) {
                $groups = [];
    
                foreach ($row->services as $service) {
                    $baseName = explode(':', $service->name)[0];
                    $groups[$baseName] = true;
                }
    
                return implode('<br>', array_keys($groups));
            });
    
            $table->editColumn('kuota', function ($row) {
                return $row->kuota ?? '';
            });
    
            // Pastikan kolom HTML tidak di-escape
            $table->rawColumns(['actions', 'placeholder', 'services']);
    
            return $table->make(true);
        }
    
        return view('admin.clients.index');
    }

    public function create()
    {
        abort_if(Gate::denies('client_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id');

        return view('admin.clients.create', compact('services'));
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->all());
        $client->services()->sync($request->input('services', []));

        return redirect()->route('admin.clients.index');
    }

    public function edit(Client $client)
    {
        abort_if(Gate::denies('client_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $services = Service::pluck('name', 'id');
        $client->load('services');

        return view('admin.clients.edit', compact('client', 'services'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->all());
        $client->user->update([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
        ]);
        $client->services()->sync($request->input('services', []));

        return redirect()->route('admin.clients.index');
    }

    public function show(Client $client)
    {
        abort_if(Gate::denies('client_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->load('services');

        return view('admin.clients.show', compact('client'));
    }

    public function destroy(Client $client)
    {
        abort_if(Gate::denies('client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->delete();

        return back();
    }

    public function massDestroy(MassDestroyClientRequest $request)
    {
        Client::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}