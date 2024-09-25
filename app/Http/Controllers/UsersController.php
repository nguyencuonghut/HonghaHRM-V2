<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('id', 'desc')->get();
        return view('user.create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users',
            'role_id' => 'required',
        ];

        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'email.required' => 'Bạn phải nhập email.',
            'email.unique' => 'Email đã tồn tại.',
            'role_id.required' => 'Bạn phải chọn vai trò.',
        ];

        $request->validate($rules, $messages);

        $password = Str::random(8);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->role_id = $request->role_id;
        $user->status = 'Mở';
        $user->save();

        Alert::toast('Thêm người dùng thành công!', 'success', 'top-right');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('id', 'desc')->get();
        return view('user.edit',
                    [
                        'user' => $user,
                        'roles' => $roles,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'role_id' => 'required',
            'status' => 'required',
        ];

        $messages = [
            'name.required' => 'Bạn phải nhập tên.',
            'email.required' => 'Bạn phải nhập email.',
            'email.unique' => 'Email bị trùng',
            'role_id.required' => 'Bạn phải chọn vai trò.',
            'status.required' => 'Bạn phải chọn trạng thái.',
        ];

        $request->validate($rules, $messages);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->status = $request->status;
        $user->save();

        Alert::toast('Sửa người dùng thành công!', 'success', 'top-right');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->destroy($id);

        Alert::toast('Xóa người dùng thành công!', 'success', 'top-rigth');
        return redirect()->route('users.index');
    }

    public function anyData()
    {
        $data = User::with('role')->orderBy('id', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('email', function($row) {
                return $row->email;
            })
            ->addColumn('role', function($row) {
                return $row->role->name;
            })
            ->addColumn('status', function($row) {
                if ('Mở' == $row->status) {
                    return '<span class="badge badge-success">' . $row->status . '</span>';
                } else {
                    return '<span class="badge badge-danger">' . $row->status . '</span>';
                }
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("users.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("users.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'status'])
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
            $import = new UserImport;
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
