<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyEmployeeRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class EmployeesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            // sama pola dengan Service: single query, pakai select('%s.*')
            $query = \App\Employee::with('user')
                ->select(sprintf('%s.*', (new \App\Employee)->getTable()));
    
            $table = \Yajra\DataTables\Facades\DataTables::of($query);
    
            // placeholder pakai closure (bukan string) biar gak kena eval
            $table->addColumn('placeholder', function () {
                return '&nbsp;';
            });
    
            // actions langsung via closure (hapus addColumn('actions', '&nbsp;'))
            $table->addColumn('actions', function ($row) {
                $viewGate      = 'employee_show';
                $editGate      = 'employee_edit';
                $deleteGate    = 'employee_delete';
                $crudRoutePart = 'employees';
    
                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });
    
            // kolom-kolom lain aman tanpa eval
            $table->editColumn('id', function ($row) {
                return $row->id ?? '';
            });
    
            // name & username diambil dari relasi user → addColumn (karena bukan kolom tabel employees)
            $table->addColumn('name', function ($row) {
                return optional($row->user)->name ?? '';
            });
    
            $table->addColumn('username', function ($row) {
                return optional($row->user)->username ?? '';
            });
    
            $table->editColumn('phone', function ($row) {
                return $row->phone ?? '';
            });
    
            // jika punya thumbnail foto; kalau tidak, biarkan kosong
            $table->addColumn('photo', function ($row) {
                if ($row->photo) {
                    return sprintf(
                        '<a href="%s" target="_blank"><img src="%s" style="width:100px; height:100px; object-fit:cover; border-radius:8px;"></a>',
                        $row->photo->url,
                        $row->photo->getUrl('thumb')
                    );
                }
                return '';
            });
    
            // pastikan kolom HTML tidak di-escape
            $table->rawColumns(['actions', 'placeholder', 'photo']);
    
            return $table->make(true);
        }
    
        return view('admin.employees.index');
    }

    public function create()
    {
        abort_if(Gate::denies('employee_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.employees.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->all());

        if ($request->input('photo', false)) {
            $employee->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))
                     ->toMediaCollection('photo');
        }

        return redirect()->route('admin.employees.index');
    }

    public function edit(Employee $employee)
    {
        abort_if(Gate::denies('employee_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->all());
        $employee->user->update([
            'name' => $request->input('name'),
        ]);

        if ($request->input('photo', false)) {
            if (!$employee->photo || $request->input('photo') !== $employee->photo->file_name) {
                $employee->addMedia(storage_path('tmp/uploads/' . $request->input('photo')))
                         ->toMediaCollection('photo');
            }
        } elseif ($employee->photo) {
            $employee->photo->delete();
        }

        return redirect()->route('admin.employees.index');
    }

    public function show(Employee $employee)
    {
        abort_if(Gate::denies('employee_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.employees.show', compact('employee'));
    }

    public function destroy(Employee $employee)
    {
        abort_if(Gate::denies('employee_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employee->delete();

        return back();
    }

    public function massDestroy(MassDestroyEmployeeRequest $request)
    {
        Employee::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}