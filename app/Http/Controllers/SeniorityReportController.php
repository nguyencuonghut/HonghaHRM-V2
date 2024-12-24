<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Employee;
use App\Models\JoinDate;
use App\Models\Position;
use App\Models\SeniorityReport;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SeniorityReportController extends Controller
{
    public function index()
    {
        return view('report.seniority.index');
    }

    public function show()
    {
        return view('report.seniority.show', ['year' => 5]);//Fetch the seniority = 5 years as default
    }

    public function byYear(Request $request)
    {
        return view('report.seniority.by_year',
                    [
                        'year' => $request->year,
                    ]);
    }

    public function anyData()
    {
        $data = SeniorityReport::join('employees', 'employees.id', 'seniority_reports.employee_id')
                    ->select('seniority_reports.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('name', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';

            })
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
            ->editColumn('formal_contract_start_date', function ($data) {

                return date('d/m/Y', strtotime($data->formal_contract_start_date));
            })
            ->editColumn('seniority', function ($data) {
                return round(ceil(Carbon::parse($data->formal_contract_start_date)->diffInYears(Carbon::now())*100)/100,2);
            })
            ->rawColumns(['name', 'department'])
            ->make(true);
    }

    public function byYearData($year)
    {
        $data = SeniorityReport::whereYear('formal_contract_start_date', Carbon::now()->year - $year)
                    ->join('employees', 'employees.id', 'seniority_reports.employee_id')
                    ->select('seniority_reports.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('name', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';

            })
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
            ->editColumn('formal_contract_start_date', function ($data) {
                return date('d/m/Y', strtotime($data->formal_contract_start_date));
            })
            ->editColumn('seniority', function ($data) {
                return round(ceil(Carbon::parse($data->formal_contract_start_date)->diffInYears(Carbon::now())*100)/100,2);
            })
            ->rawColumns(['name', 'department'])
            ->make(true);
    }
}
