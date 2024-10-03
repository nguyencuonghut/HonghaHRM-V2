<?php

namespace App\Http\Controllers;

use App\Imports\RoleImport;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Role::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('roles.index');
        }
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:roles',
        ];

        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Vai trò đã tồn tại.'
        ];

        $request->validate($rules, $messages);

        $role = new Role();
        $role->name = $request->name;
        $role->save();

        Alert::toast('Thêm vai trò thành công!', 'success', 'top-right');
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if (Auth::user()->cannot('update', $role)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('roles.index');
        }

        return view('role.edit', ['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $rules = [
            'name' => 'required|unique:roles,name,'.$role->id,
        ];

        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên bị trùng',
        ];

        $request->validate($rules, $messages);

        $role->name = $request->name;
        $role->save();

        Alert::toast('Sửa vai trò thành công!', 'success', 'top-right');
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if (Auth::user()->cannot('delete', $role)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('roles.index');
        }

        //Check if Role is used or not
        if ($role->users->count()) {
            Alert::toast('Vai trò đang được sử dụng. Không thể xóa!', 'error', 'top-rigth');
            return redirect()->route('roles.index');
        }
        $role->delete();

        Alert::toast('Xóa vai trò thành công!', 'success', 'top-rigth');
        return redirect()->route('roles.index');
    }

    public function anyData()
    {
        $data = Role::orderBy('id', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("roles.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("roles.destroy", $row->id) . '" method="POST">
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
            $import = new RoleImport;
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
}
