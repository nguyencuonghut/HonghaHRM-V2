<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentViceRequest;
use App\Http\Requests\UpdateDepartmentViceRequest;
use App\Models\Department;
use App\Models\DepartmentVice;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DepartmentViceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->cannot('viewAny', DepartmentVice::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('home');
        }

        return view('department_vice.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', DepartmentVice::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $departments = Department::orderBy('name', 'asc')->get();
        $employees = Employee::orderBy('id', 'asc')->get();
        return view('department_vice.create',
                    [
                        'departments' => $departments,
                        'employees' => $employees,
                    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentViceRequest $request)
    {
        $department_vice = new DepartmentVice();
        $department_vice->department_id = $request->department_id;
        $department_vice->vice_id = $request->vice_id;
        $department_vice->save();

        Alert::toast('Thêm phó phòng mới thành công!', 'success', 'top-right');
        return redirect()->route('department_vices.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DepartmentVice $departmentVice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DepartmentVice $departmentVice)
    {
        if (Auth::user()->cannot('update', $departmentVice)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }


        $departments = Department::orderBy('name', 'asc')->get();
        $employees = Employee::orderBy('id', 'asc')->get();
        return view('department_vice.edit',
                    [
                        'department_vice' => $departmentVice,
                        'departments' => $departments,
                        'employees' => $employees,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentViceRequest $request, DepartmentVice $departmentVice)
    {
        $departmentVice->department_id = $request->department_id;
        $departmentVice->vice_id = $request->vice_id;
        $departmentVice->save();

        Alert::toast('Sửa phó phòng thành công!', 'success', 'top-right');
        return redirect()->route('department_vices.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DepartmentVice $departmentVice)
    {
        if (Auth::user()->cannot('delete', $departmentVice)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        $departmentVice->delete();

        Alert::toast('Xóa phó phòng thành công!', 'success', 'top-right');
        return redirect()->route('department_vices.index');
    }

    public function anyData()
    {
        $data = DepartmentVice::with('department', 'vice')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                return '<a href="' . route("departments.show", $data->department->id) . '">' . $data->department->name . '</a>';
            })
            ->editColumn('vice', function ($data) {
                return '<a href="' . route("employees.show", $data->vice_id) . '">' . $data->vice->name . '</a>';
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("department_vices.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("department_vices.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['department', 'vice','actions'])
            ->make(true);
    }
}
