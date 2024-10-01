<?php

namespace App\Http\Controllers;

use App\Imports\DepartmentImport;
use App\Models\Department;
use App\Models\Division;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function create(): View
    {
        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => 'required|unique:departments',
        ];

        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Phòng/ban đã tồn tại.'
        ];

        $request->validate($rules, $messages);

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
    public function edit(Department $department): View
    {
        return view('department.edit', ['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department): RedirectResponse
    {
        $rules = [
            'name' => 'required|unique:departments,name,'.$department->id,
        ];

        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên bị trùng',
        ];

        $request->validate($rules, $messages);

        $department->name = $request->name;
        $department->save();

        Alert::toast('Sửa phòng/ban thành công!', 'success', 'top-right');
        return redirect()->route('departments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
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
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("departments.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("departments.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:xlsx,xls|max:5000',
        ];
        $messages = [
            'file.required' => 'Bạn phải chọn file import.',
            'file.mimes' => 'Bạn phải chọn định dạng file .xlsx, .xls.',
            'file.max' => 'File vượt quá 5MB.',
        ];

        $request->validate($rules, $messages);

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
