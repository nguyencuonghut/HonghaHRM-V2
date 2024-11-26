<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\KpiReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KpiReportController extends Controller
{

    public function show()
    {
        $year = Carbon::now()->year;
        return view('report.kpi.show', [
            'year' => $year,
        ]);
    }

    public function byYear(Request $request)
    {
        return view('report.kpi.by_year',
                    [
                        'year' => $request->year,
                    ]);
    }

    public function byYearData($year)
    {
        $data = KpiReport::where('year', $year)
                        ->where('year_avarage', '!=', 0)
                        ->join('employees', 'employees.id', 'kpi_reports.employee_id')
                        ->select('kpi_reports.*', 'employees.code as employees_code')
                        ->orderBy('employees_code', 'desc')
                        ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_code', function ($data) {
                return $data->employee->code;
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
            ->editColumn('year', function ($data) {
                return $data->year;
            })
            ->editColumn('jan', function ($data) {
                return $data->jan;
            })
            ->editColumn('feb', function ($data) {
                return $data->feb;
            })
            ->editColumn('mar', function ($data) {
                return $data->mar;
            })
            ->editColumn('apr', function ($data) {
                return $data->apr;
            })
            ->editColumn('may', function ($data) {
                return $data->may;
            })
            ->editColumn('jun', function ($data) {
                return $data->jun;
            })
            ->editColumn('jul', function ($data) {
                return $data->jul;
            })
            ->editColumn('aug', function ($data) {
                return $data->aug;
            })
            ->editColumn('sep', function ($data) {
                return $data->sep;
            })
            ->editColumn('oct', function ($data) {
                return $data->oct;
            })
            ->editColumn('nov', function ($data) {
                return $data->nov;
            })
            ->editColumn('dec', function ($data) {
                return $data->dec;
            })
            ->editColumn('year_avarage', function ($data) {
                return number_format($data->year_avarage, 2, '.', ',');
            })
            ->rawColumns(['employee_name'])
            ->make(true);
    }
}
