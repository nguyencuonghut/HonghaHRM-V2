<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJoinDateRequest;
use App\Http\Requests\UpdateJoinDateRequest;
use App\Models\JoinDate;
use App\Models\Position;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class JoinDateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('join_date.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJoinDateRequest $request)
    {
        if (Auth::user()->cannot('create', JoinDate::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $join_date = new JoinDate();
        $join_date->employee_id = $request->employee_id;
        $join_date->join_date = Carbon::createFromFormat('d/m/Y', $request->join_date);
        $join_date->save();

        Alert::toast('Tạo mới ngày vào thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(JoinDate $joinDate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JoinDate $joinDate)
    {
        if (Auth::user()->cannot('update', $joinDate)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('join_dates.index');
        }

        return view('join_date.edit', [
            'join_date' => $joinDate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJoinDateRequest $request, JoinDate $joinDate)
    {
        $joinDate->join_date = Carbon::createFromFormat('d/m/Y', $request->join_date);
        $joinDate->save();

        Alert::toast('Sửa ngày vào thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $joinDate->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JoinDate $joinDate)
    {
        if (Auth::user()->cannot('delete', $joinDate)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('join_dates.index');
        }
        $joinDate->delete();

        Alert::toast('Xóa ngày vào thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        $data = JoinDate::join('employees', 'employees.id', 'join_dates.employee_id')
                    ->select('join_dates.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                $dept_arr = [];
                $department_str = '';
                //Tìm tất cả Works
                $works = Work::where('employee_id', $data->employee_id)->get();
                if (0 == $works->count()) {
                    return 'Chưa có QT công tác';
                } else {//Đã có QT công tác
                    $on_works = Work::where('employee_id', $data->employee_id)
                                    ->where('status', 'On')
                                    ->get();
                    if ($on_works->count()) {//Có QT công tác ở trạng thái On
                        foreach ($on_works as $on_work) {
                            array_push($dept_arr, $on_work->position->department->name);
                        }
                    } else {//Còn lại là các QT công tác ở trạng thái Off
                        $last_off_works = Work::where('employee_id', $data->employee_id)
                                        ->where('status', 'Off')
                                        ->orderBy('start_date', 'desc')
                                        ->first();
                        array_push($dept_arr, $last_off_works->position->department->name);
                    }
                    //Xóa các department trùng nhau
                    $dept_arr = array_unique($dept_arr);
                    //Convert array sang string
                    $department_str = implode(' | ', $dept_arr);
                }
                return $department_str;
            })
            ->editColumn('code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('name', function ($data) {
                return '<a href="' . route("employees.show", $data->employee->id) . '">' . $data->employee->name . '</a>';
            })
            ->editColumn('join_date', function ($data) {
                return date('d/m/Y', strtotime($data->join_date));
            })
            ->rawColumns(['name', 'department_str'])
            ->make(true);
    }
}
