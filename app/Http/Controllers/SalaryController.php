<?php

namespace App\Http\Controllers;

use App\Http\Requests\OffSalaryRequest;
use App\Http\Requests\StoreSalaryRequest;
use App\Http\Requests\UpdateSalaryRequest;
use App\Models\Position;
use App\Models\Salary;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('salary.index');
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
    public function store(StoreSalaryRequest $request)
    {
        if (Auth::user()->cannot('create', Salary::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $salary = new Salary();
        $salary->employee_id = $request->employee_id;
        if ($request->position_salary) {
            $salary->position_salary = $request->position_salary;
        }
        if ($request->capacity_salary) {
            $salary->capacity_salary = $request->capacity_salary;
        }
        if ($request->position_allowance) {
            $salary->position_allowance = $request->position_allowance;
        }
        $salary->insurance_salary = $request->insurance_salary;
        $salary->start_date = Carbon::createFromFormat('d/m/Y', $request->salary_start_date);
        $salary->status = 'On';
        $salary->save();

        Alert::toast('Tạo lương mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        if (Auth::user()->cannot('update', $salary)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        return view('salary.edit', ['salary' => $salary]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryRequest $request, Salary $salary)
    {
        if ($request->position_salary) {
            $salary->position_salary = $request->position_salary;
        }
        if ($request->capacity_salary) {
            $salary->capacity_salary = $request->capacity_salary;
        }
        $salary->position_allowance = $request->position_allowance;
        $salary->insurance_salary = $request->insurance_salary;
        $salary->start_date = Carbon::createFromFormat('d/m/Y', $request->salary_start_date);
        $salary->save();

        Alert::toast('Sửa lương thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $salary->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        if (Auth::user()->cannot('delete', $salary)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $salary->delete();

        Alert::toast('Xóa lương thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function employeeData($employee_id)
    {
        $data = Salary::where('employee_id', $employee_id)->orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('position_salary', function ($data) {
                return number_format($data->position_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('capacity_salary', function ($data) {
                return number_format($data->capacity_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('position_allowance', function ($data) {
                return number_format($data->position_allowance, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('insurance_salary', function ($data) {
                return number_format($data->insurance_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('start_date', function ($data) {
                return date('d/m/Y', strtotime($data->start_date));
            })
            ->editColumn('end_date', function ($data) {
                if ($data->end_date) {
                    return date('d/m/Y', strtotime($data->end_date));
                } else {
                    return '';
                }
            })
            ->editColumn('status', function ($data) {
                if($data->status == 'On') {
                    return '<span class="badge badge-success">On</span>';
                } else {
                    return '<span class="badge badge-secondary">Off</span>';
                }
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("salaries.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="' . route("salaries.getOff", $data->id) . '" class="btn btn-secondary btn-sm"><i class="fas fa-power-off"></i></a>
                           <form style="display:inline" action="'. route("salaries.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'status', 'position_salary', 'capacity_salary', 'position_allowance', 'insurance_salary'])
            ->make(true);
    }

    public function anyData()
    {
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            // Only fetch the Employee according to Admin's Department
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('company_job_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Salary::whereIn('employee_id', $employee_ids)
                                                ->where('status', 'On')
                                                ->join('employees', 'employees.id', 'salaries.employee_id')
                                                ->orderBy('employees.code', 'desc')
                                                ->get();
        } else {
            $data = Salary::where('status', 'On')
                                                ->join('employees', 'employees.id', 'salaries.employee_id')
                                                ->orderBy('employees.code', 'desc')
                                                ->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                $works =Work::where('employee_id', $data->employee_id)->where('status', 'On')->get();
                $department_str = '';
                $i = 0;
                $length = count($works);
                if ($length) {
                    foreach ($works as $work) {
                        if(++$i === $length) {
                            $department_str .= $work->position->department->name;
                        } else {
                            $department_str .= $work->company_job->department->name;
                            $department_str .= ' | ';
                        }
                    }
                } else {
                    $department_str .= '!! Chưa gán vị trí công việc !!';
                }
                return $department_str;
            })
            ->editColumn('code', function ($data) {
                return $data->employee->code;
            })
            ->editColumn('employee', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';
            })
            ->editColumn('position_salary', function ($data) {
                return number_format($data->position_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('capacity_salary', function ($data) {
                return number_format($data->capacity_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('position_allowance', function ($data) {
                return number_format($data->position_allowance, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('insurance_salary', function ($data) {
                return number_format($data->insurance_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('status', function ($data) {
                if($data->status == 'On') {
                    return '<span class="badge badge-success">On</span>';
                } else {
                    return '<span class="badge badge-secondary">Off</span>';
                }
            })
            ->rawColumns(['department', 'employee', 'status', 'position_salary', 'capacity_salary', 'position_allowance', 'insurance_salary'])
            ->make(true);
    }

    public function getOff(Salary $salary)
    {
        if (Auth::user()->cannot('off', $salary)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        return view('salary.off', ['salary' => $salary]);
    }

    public function off(OffSalaryRequest $request, Salary $salary)
    {
        // Off the Salary
        $salary->end_date = Carbon::createFromFormat('d/m/Y', $request->salary_end_date);
        $salary->status = 'Off';
        $salary->save();

        Alert::toast('Off thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $salary->employee_id);
    }
}