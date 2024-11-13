<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportDepartmentRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Imports\DepartmentImport;
use App\Models\Department;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('department.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Department::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('departments.index');
        }

        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = new Department();
        $department->name = $request->name;
        $department->save();

        Alert::toast('Thêm phòng/ban thành công!', 'success', 'top-right');
        return redirect()->route('departments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        if (Auth::user()->cannot('update', $department)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('departments.index');
        }

        return view('department.edit', ['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update(['name' => $request->name]);

        Alert::toast('Sửa phòng/ban thành công!', 'success', 'top-right');
        return redirect()->route('departments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        if (Auth::user()->cannot('delete', $department)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('departments.index');
        }

        //Check if Department is used or not
        $divisions = Division::where('department_id', $department->id)->get();
        if ($divisions->count()) {
            Alert::toast('Phòng/ban đang được sử dụng. Không thể xóa!', 'error', 'top-rigth');
            return redirect()->route('departments.index');
        }
        $department->delete();

        Alert::toast('Xóa vai trò thành công!', 'success', 'top-rigth');
        return redirect()->route('departments.index');
    }

    public function anyData()
    {
        $data = Department::orderBy('id', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->editColumn('divisions', function ($departments) {
                $i = 0;
                $length = count($departments->divisions);
                $divisions = '';
                foreach ($departments->divisions as $division) {
                    if(++$i === $length) {
                        $divisions =  $divisions . $division->name;
                    } else {
                        $divisions = $divisions . $division->name . ', <br>';
                    }
                }
                return $divisions;
            })
            ->editColumn('position_lists', function ($departments) {
                $positions = Position::where('department_id', $departments->id)->orderBy('name')->get();
                $i = 0;
                $length = count($positions);
                $position_lists = '';
                foreach ($positions as $company_job) {
                    if(++$i === $length) {
                        $position_lists =  $position_lists . $company_job->name;
                    } else {
                        $position_lists = $position_lists . $company_job->name . ', <br>';
                    }
                }
                return $position_lists;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("departments.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("departments.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'divisions', 'position_lists'])
            ->make(true);
    }

    public function import(ImportDepartmentRequest $request)
    {
        try {
            $import = new DepartmentImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $duplicates = $import->getDuplicateCount();
            $duplicate_rows = $import->getDuplicateRows();
            if ($duplicates) {
                $duplicate_rows_list = implode(', ', $duplicate_rows);
                Alert::toast('Các dòng bị trùng lặp là '. $duplicate_rows_list);
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công! Có ' . $duplicates . ' dòng bị trùng lặp! Lặp tại dòng số: ' . $duplicate_rows_list, 'success', 'top-right');
            } else {
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công!', 'success', 'top-right');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Có lỗi xảy ra trong quá trình import dữ liệu. Vui lòng kiểm tra lại file!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function getDivision($department_id)
    {
        $divisionData['data'] = Division::orderby("name","asc")
                                    ->select('id','name')
                                    ->where('department_id',$department_id)
                                    ->get();

        return response()->json($divisionData);

    }
}
