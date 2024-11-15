<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentManagerRequest;
use App\Http\Requests\UpdateDepartmentManagerRequest;
use App\Models\Department;
use App\Models\DepartmentManager;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DepartmentManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('department_manager.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', DepartmentManager::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $departments = Department::orderBy('name', 'asc')->get();
        $employees = Employee::orderBy('id', 'asc')->get();
        return view('department_manager.create',
                    [
                        'departments' => $departments,
                        'employees' => $employees,
                    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentManagerRequest $request)
    {
        $department_manager = new DepartmentManager();
        $department_manager->department_id = $request->department_id;
        $department_manager->manager_id = $request->manager_id;
        $department_manager->save();

        Alert::toast('Thêm quản lý phòng ban mới thành công!', 'success', 'top-right');
        return redirect()->route('department_managers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DepartmentManager $departmentManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DepartmentManager $departmentManager)
    {
        if (Auth::user()->cannot('update', $departmentManager)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }


        $departments = Department::orderBy('name', 'asc')->get();
        $employees = Employee::orderBy('id', 'asc')->get();
        return view('department_manager.edit',
                    [
                        'department_manager' => $departmentManager,
                        'departments' => $departments,
                        'employees' => $employees,

                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentManagerRequest $request, DepartmentManager $departmentManager)
    {
        $departmentManager->department_id = $request->department_id;
        $departmentManager->manager_id = $request->manager_id;
        $departmentManager->save();

        Alert::toast('Sửa quản lý phòng ban thành công!', 'success', 'top-right');
        return redirect()->route('department_managers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DepartmentManager $departmentManager)
    {
        if (Auth::user()->cannot('delete', $departmentManager)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        $departmentManager->delete();

        Alert::toast('Xóa quản lý phòng ban thành công!', 'success', 'top-right');
        return redirect()->route('department_managers.index');
    }

    public function anyData()
    {
        $data = DepartmentManager::with('department', 'manager')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                return '<a href="' . route("departments.show", $data->department->id) . '">' . $data->department->name . '</a>';
            })
            ->editColumn('manager', function ($data) {
                return '<a href="' . route("employees.show", $data->manager_id) . '">' . $data->manager->name . '</a>';
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("department_managers.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("department_managers.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['department', 'manager','actions'])
            ->make(true);
    }
}
