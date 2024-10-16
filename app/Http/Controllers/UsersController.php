<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Imports\UserImport;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDepartment;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::user()->cannot('create', User::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('users.index');
        }

        $roles = Role::orderBy('name', 'asc')->get();
        $departments = Department::orderBy('id', 'desc')->get();
        return view('user.create',
                    [
                        'roles' => $roles,
                        'departments' => $departments,
                    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $password = Str::random(8);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->role_id = $request->role_id;
        $user->status = 'Mở';
        $user->save();

        //Create user_department pivot item
        $user->departments()->attach($request->department_id);


        Alert::toast('Thêm người dùng thành công!', 'success', 'top-right');
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (Auth::user()->cannot('update', $user)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('users.index');
        }

        $roles = Role::orderBy('id', 'desc')->get();
        $departments = Department::orderBy('id', 'desc')->get();
        $selected_departments = UserDepartment::where('user_id', $user->id)->pluck('department_id')->toArray();

        return view('user.edit',
                    [
                        'user' => $user,
                        'roles' => $roles,
                        'departments' => $departments,
                        'selected_departments' => $selected_departments,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'status' => $request->status,
        ]);

        // Delete all old pivot items
        $user->departments()->detach();

        //Create user_department pivot item
        $user->departments()->attach($request->department_id);

        Alert::toast('Sửa người dùng thành công!', 'success', 'top-right');
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::user()->cannot('delete', $user)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('users.index');
        }

        $user->delete();

        Alert::toast('Xóa người dùng thành công!', 'success', 'top-rigth');
        return redirect()->route('users.index');
    }

    public function anyData()
    {
        $data = User::with(['role', 'departments'])->orderBy('id', 'desc');
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
            ->editColumn('departments', function ($row) {
                $i = 0;
                $length = count($row->departments);
                $departments_list = '';
                foreach ($row->departments as $item) {
                    if(++$i === $length) {
                        $departments_list =  $departments_list . $item->name;
                    } else {
                        $departments_list = $departments_list . $item->name . ', ';
                    }
                }
                return $departments_list;
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
            ->rawColumns(['actions', 'departments', 'status'])
            ->make(true);
    }

    public function import(ImportUserRequest $request)
    {
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
