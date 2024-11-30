<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\JoinDate;
use App\Models\Position;
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
        $data = Employee::orderBy('code', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->code;
            })
            ->editColumn('name', function ($data) {
                return '<a href="' . route("employees.show", $data->id) . '">' . $data->name . '</a>';

            })
            ->editColumn('department', function ($data) {
                $my_position_ids = Work::where('employee_id', $data->id)
                                        ->where(function ($query) {
                                            $query->whereIn('off_type_id', [2,3,4])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm
                                                ->orWhereNull('off_type_id');
                                        })
                                        ->pluck('position_id')
                                        ->toArray();
                $my_positions = Position::whereIn('id', $my_position_ids)->get();
                $department_str = '';
                $i = 0;
                $length = count($my_positions);
                if ($length) {
                    foreach ($my_positions as $my_position) {
                        if(++$i === $length) {
                            $department_str .= $my_position->division_id ? $my_position->division->name . ' - ' . $my_position->department->name : $my_position->department->name;
                        } else {
                            $department_str .= $my_position->division_id ? $my_position->division->name . ' - ' . $my_position->department->name : $my_position->department->name;
                            $department_str .= ' | ';
                        }
                    }
                } else {
                    $department_str .= '!! Chưa gán phòng/ban !!';
                }

                return $department_str;
            })
            ->editColumn('join_date', function ($data) {
                $join_dates = JoinDate::where('employee_id', $data->id)->orderBy('join_date', 'desc')->get();
                $join_date_str = '';
                $i = 0;
                $length = count($join_dates);
                if ($length) {
                    foreach ($join_dates as $join_date) {
                        if(++$i === $length) {
                            $join_date_str .= date('d/m/Y', strtotime($join_date->join_date));
                        } else {
                            $join_date_str .= date('d/m/Y', strtotime($join_date->join_date));
                            $join_date_str .= ', <br>';
                        }
                    }
                } else {
                    $join_date_str .= 'Chưa nhập Ngày Vào';
                }
                return $join_date_str;
            })
            ->editColumn('seniority', function ($data) {
                $last_join_date = JoinDate::where('employee_id', $data->id)->orderBy('join_date', 'desc')->first();
                if ($last_join_date) {
                    return round(ceil(Carbon::parse($last_join_date->join_date)->diffInYears(Carbon::now())*100)/100,2);
                } else {
                    return 'Chưa nhập Ngày Vào';
                }
            })
            ->rawColumns(['name', 'department', 'join_date'])
            ->make(true);
    }

    public function byYearData($year)
    {
        $data = JoinDate::whereYear('join_date', Carbon::now()->year - $year)
                    ->join('employees', 'employees.id', 'join_dates.employee_id')
                    ->select('join_dates.*', 'employees.code as employees_code')
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
                $my_position_ids = Work::where('employee_id', $data->employee_id)
                                        ->where(function ($query) {
                                            $query->whereIn('off_type_id', [2,3,4])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm
                                                ->orWhereNull('off_type_id');
                                        })
                                        ->pluck('position_id')
                                        ->toArray();
                $my_positions = Position::whereIn('id', $my_position_ids)->get();
                $department_str = '';
                $i = 0;
                $length = count($my_positions);
                if ($length) {
                    foreach ($my_positions as $my_position) {
                        if(++$i === $length) {
                            $department_str .= $my_position->division_id ? $my_position->division->name . ' - ' . $my_position->department->name : $my_position->department->name;
                        } else {
                            $department_str .= $my_position->division_id ? $my_position->division->name . ' - ' . $my_position->department->name : $my_position->department->name;
                            $department_str .= ' | ';
                        }
                    }
                } else {
                    $department_str .= '!! Chưa gán phòng/ban !!';
                }

                return $department_str;
            })
            ->editColumn('join_date', function ($data) {
                $join_dates = JoinDate::where('employee_id', $data->employee_id)->orderBy('join_date', 'desc')->get();
                $join_date_str = '';
                $i = 0;
                $length = count($join_dates);
                if ($length) {
                    foreach ($join_dates as $join_date) {
                        if(++$i === $length) {
                            $join_date_str .= date('d/m/Y', strtotime($join_date->join_date));
                        } else {
                            $join_date_str .= date('d/m/Y', strtotime($join_date->join_date));
                            $join_date_str .= ', <br>';
                        }
                    }
                } else {
                    $join_date_str .= 'Chưa nhập Ngày Vào';
                }
                return $join_date_str;
            })
            ->editColumn('seniority', function ($data) {
                $last_join_date = JoinDate::where('employee_id', $data->employee_id)->orderBy('join_date', 'desc')->first();
                if ($last_join_date) {
                    return round(ceil(Carbon::parse($last_join_date->join_date)->diffInYears(Carbon::now())*100)/100,2);
                } else {
                    return 'Chưa nhập Ngày Vào';
                }
            })
            ->rawColumns(['name', 'department', 'join_date'])
            ->make(true);
    }
}
