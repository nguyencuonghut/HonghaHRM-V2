<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Position;
use App\Models\Work;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SituationReportController extends Controller
{
    public function index()
    {
        return view('report.situation.index');
    }

    public function anyData()
    {
        $data = Family::where('situation', '!=', null)
                    ->join('employees', 'employees.id', 'families.employee_id')
                    ->select('families.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();

        return DataTables::of($data)
            ->addIndexColumn()
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
                            $department_str .= $my_position->department->name;
                            $department_str .= ' | ';
                        }
                    }
                } else {
                    $department_str .= '!! Chưa gán phòng/ban !!';
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
            ->editColumn('type', function ($employee_relatives) {
                return $employee_relatives->type;
            })
            ->editColumn('year_of_birth', function ($employee_relatives) {
                return $employee_relatives->year_of_birth;
            })
            ->editColumn('employee', function ($employee_relatives) {
            })
            ->rawColumns(['employee_name', 'department'])
            ->make(true);
    }
}
