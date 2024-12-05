<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Position;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KidPolicyReportController extends Controller
{
    public function index()
    {
        return view('report.kid_policy.index');
    }


    public function anyData()
    {
        $data = Family::whereIn('type', ['Con trai', 'Con gái'])
                    ->where('year_of_birth', '>=', Carbon::now()->year - 15)
                    ->join('employees', 'employees.id', 'families.employee_id')
                    ->select('families.*', 'employees.code as employees_code')
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
            ->editColumn('employee_name', function ($data) {
                return '<a href="'.route("employees.show", $data->employee_id).'">'.$data->employee->name.'</a>';
            })
            ->editColumn('family_name', function ($data) {
                return $data->name;
            })
            ->editColumn('year_of_birth', function ($data) {
                return $data->year_of_birth;
            })
            ->rawColumns(['employee_name', 'department'])
            ->make(true);
    }
}
