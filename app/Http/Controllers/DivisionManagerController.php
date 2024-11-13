<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDivisionManagerRequest;
use App\Http\Requests\UpdateDivisionManagerRequest;
use App\Models\Division;
use App\Models\DivisionManager;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DivisionManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('division_manager.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', DivisionManager::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $divisions = Division::orderBy('name', 'asc')->get();
        $employees = Employee::orderBy('id', 'asc')->get();
        return view('division_manager.create',
                    [
                        'divisions' => $divisions,
                        'employees' => $employees,
                    ]);    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDivisionManagerRequest $request)
    {
        $division_manager = new DivisionManager();
        $division_manager->division_id = $request->division_id;
        $division_manager->manager_id = $request->manager_id;
        $division_manager->save();

        Alert::toast('Thêm quản lý bộ phận mới thành công!', 'success', 'top-right');
        return redirect()->route('division_managers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DivisionManager $divisionManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DivisionManager $divisionManager)
    {
        if (Auth::user()->cannot('update', $divisionManager)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }


        $divisions = Division::orderBy('name', 'asc')->get();
        $employees = Employee::orderBy('id', 'asc')->get();

        return view('division_manager.edit',
        [
            'division_manager' => $divisionManager,
            'divisions' => $divisions,
            'employees' => $employees,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDivisionManagerRequest $request, DivisionManager $divisionManager)
    {
        $divisionManager->division_id = $request->division_id;
        $divisionManager->manager_id = $request->manager_id;
        $divisionManager->save();

        Alert::toast('Sửa quản lý bộ phận thành công!', 'success', 'top-right');
        return redirect()->route('division_managers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DivisionManager $divisionManager)
    {
        if (Auth::user()->cannot('delete', $divisionManager)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        $divisionManager->delete();

        Alert::toast('Xóa quản lý bộ phận thành công!', 'success', 'top-right');
        return redirect()->route('division_managers.index');
    }

    public function anyData()
    {
        $data = DivisionManager::with('division', 'manager')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('division', function ($data) {
                return $data->division->name;

            })
            ->editColumn('manager', function ($data) {
                return '<a href="' . route("employees.show", $data->manager_id) . '">' . $data->manager->name . '</a>';
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("division_managers.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("division_managers.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['manager','actions'])
            ->make(true);
    }
}
