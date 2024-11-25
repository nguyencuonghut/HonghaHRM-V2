<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RewardReportController extends Controller
{

    public function index()
    {
        return view('report.reward.index');
    }

    public function show()
    {
        $year = Carbon::now()->year;
        return view('report.reward.show', [
            'year' => $year,
        ]);
    }

    public function byYear(Request $request)
    {
        return view('report.reward.by_year',
                    [
                        'year' => $request->year,
                    ]);
    }

    public function anyData()
    {
        $data = Reward::join('employees', 'employees.id', 'rewards.employee_id')
                    ->select('rewards.*', 'employees.code as employees_code')
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
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('code', function ($data) {
                return $data->code;
            })
            ->editColumn('sign_date', function ($data) {
                return date('d/m/Y', strtotime($data->sign_date));
            })
            ->editColumn('content', function ($data) {
                return $data->content;
            })
            ->editColumn('note', function ($data) {
                return $data->note;
            })
            ->rawColumns(['employee_name'])
            ->make(true);
    }

    public function byYearData($year)
    {
        $data = Reward::whereYear('sign_date', $year)
                    ->join('employees', 'employees.id', 'rewards.employee_id')
                    ->select('rewards.*', 'employees.code as employees_code')
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
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('code', function ($data) {
                return $data->code;
            })
            ->editColumn('sign_date', function ($data) {
                return date('d/m/Y', strtotime($data->sign_date));
            })
            ->editColumn('content', function ($data) {
                return $data->content;
            })
            ->editColumn('note', function ($data) {
                return $data->note;
            })
            ->rawColumns(['employee_name'])
            ->make(true);
    }
}
