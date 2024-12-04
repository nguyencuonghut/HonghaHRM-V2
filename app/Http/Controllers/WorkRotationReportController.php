<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WorkRotationReportController extends Controller
{
    public function index()
    {
        return view('report.work_rotation.index');
    }

    public function show()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        return view('report.work_rotation.show', [
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function byMonth(Request $request)
    {
        $filter_month_year = explode('/', $request->month_of_year);
        $month = $filter_month_year[0];
        $year   = $filter_month_year[1];
        return view('report.work_rotation.by_month',
                    [
                        'month' => $month,
                        'year' => $year,
                    ]);
    }

    public function anyData()
    {
        $data = Work::where('on_type_id', 4)//4: Thay đổi chức danh
                    ->join('employees', 'employees.id', 'works.employee_id')
                    ->select('works.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('old_position', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    return $old_work->position->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('old_division', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    if ($old_work->position->division_id) {
                        return $old_work->position->division->name;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->editColumn('old_department', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    return $old_work->position->department->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('new_position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('new_division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('new_department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('rotation_date', function ($data) {
                return date('d/m/Y', strtotime($data->start_date));
            })
            ->rawColumns(['employee_name'])
            ->make(true);
    }

    public function byMonthData($month, $year)
    {
        $data = Work::where('on_type_id', 4)//4: Thay đổi chức danh
                    ->whereMonth('start_date', $month)
                    ->whereYear('start_date', $year)
                    ->join('employees', 'employees.id', 'works.employee_id')
                    ->select('works.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('old_position', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    return $old_work->position->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('old_division', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    if ($old_work->position->division_id) {
                        return $old_work->position->division->name;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->editColumn('old_department', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    return $old_work->position->department->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('new_position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('new_division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('new_department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('rotation_date', function ($data) {
                return date('d/m/Y', strtotime($data->start_date));
            })
            ->rawColumns(['employee_name'])
            ->make(true);
    }

    public function byRange(Request $request)
    {
        if ($request->ajax()) {
            $data = Work::where('on_type_id', 4)//4: Thay đổi chức danh
                        ->join('employees', 'employees.id', 'works.employee_id')
                        ->select('works.*', 'employees.code as employees_code')
                        ->orderBy('employees_code', 'desc')
                        ->get();

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('start_date', [$request->from_date, $request->to_date]);
            }
            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('old_position', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    return $old_work->position->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('old_division', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    if ($old_work->position->division_id) {
                        return $old_work->position->division->name;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->editColumn('old_department', function ($data) {
                $old_work = Work::where('employee_id', $data->employee_id)
                                ->where('end_date', $data->start_date)
                                ->first();
                if ($old_work) {
                    return $old_work->position->department->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('new_position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('new_division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('new_department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('rotation_date', function ($data) {
                return date('d/m/Y', strtotime($data->start_date));
            })
            ->rawColumns(['employee_name'])
            ->make(true);
        }

        return view('report.work_rotation.by_range');
    }
}
