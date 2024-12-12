<?php

namespace App\Http\Controllers;

use App\Models\DecreaseInsurance;
use App\Models\DepartmentVice;
use App\Models\Employee;
use App\Models\IncreaseInsurance;
use App\Models\Insurance;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class IncreaseDecreaseInsuranceReportController extends Controller
{
    public function show()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        return view('report.increase_decrease_insurance.show', [
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function byMonth(Request $request)
    {
        $filter_month_year = explode('/', $request->month_of_year);
        $month = $filter_month_year[0];
        $year   = $filter_month_year[1];

        return view('report.increase_decrease_insurance.by_month',
                    [
                        'month' => $month,
                        'year' => $year,
                    ]);
    }

    public function increaseByMonthData($month, $year)
    {
        $data = IncreaseInsurance::with('work')
                                ->whereMonth('confirmed_month', $month)
                                ->whereYear('confirmed_month', $year)
                                ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->work->employee->code;
            })
            ->editColumn('name', function ($data) {
                return '<a href=' . route("employees.show", $data->work->employee->id) . '>' . $data->work->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                return $data->work->position->name;
            })
            ->editColumn('start_date', function ($data) {
                return date('d/m/Y', strtotime($data->work->start_date));
            })
            ->editColumn('confirmed_month', function ($data) {
                return date('m/Y', strtotime($data->confirmed_month));
            })
            ->editColumn('insurance_salary', function ($data) use ($month, $year){
                // Tính lương bhxh tại tháng này
                $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                if ($salary) {
                    return number_format($salary->insurance_salary, 0, '.', ',');
                } else {
                    return '';
                }
            })
            ->editColumn('bhxh_increase', function ($data) use ($month, $year){
                // Tính toán số tiền tăng cho 1- bhxh
                $insurance = Insurance::where('employee_id', $data->work->employee_id)
                                                        ->where('insurance_type_id', 1)
                                                        ->first();
                if ($insurance) {
                    $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                    if ($salary) {
                        $bhxh_increase = $salary->insurance_salary * $insurance->pay_rate / 100;
                        return number_format($bhxh_increase, 0, '.', ',');
                    } else {
                        return '';
                    }
                } else {
                    return 'Chưa khai báo BHXH';
                }
            })
            ->editColumn('bhtn_increase', function ($data) use ($month, $year){
                // Tính toán số tiền tăng cho 2- bhtn
                $insurance = Insurance::where('employee_id', $data->work->employee_id)
                                                        ->where('insurance_type_id', 2)
                                                        ->first();
                if ($insurance) {
                    $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                    if ($salary) {
                        $bhxh_increase = $salary->insurance_salary * $insurance->pay_rate / 100;
                        return number_format($bhxh_increase, 0, '.', ',');
                    } else {
                        return '';
                    }
                    } else {
                    return 'Chưa khai báo BHTN';
                }
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    public function decreaseByMonthData($month, $year)
    {
        $data = DecreaseInsurance::with('work')
                                ->whereMonth('confirmed_month', $month)
                                ->whereYear('confirmed_month', $year)
                                ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->work->employee->code;
            })
            ->editColumn('name', function ($data) {
                return '<a href=' . route("employees.show", $data->work->employee->id) . '>' . $data->work->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                return $data->work->position->name;
            })
            ->editColumn('start_date', function ($data) {
                return date('d/m/Y', strtotime($data->work->start_date));
            })
            ->editColumn('confirmed_month', function ($data) {
                return date('m/Y', strtotime($data->confirmed_month));
            })
            ->editColumn('insurance_salary', function ($data) use ($month, $year){
                // Tính lương bhxh tại tháng này
                $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                if ($salary) {
                    return number_format($salary->insurance_salary, 0, '.', ',');
                } else {
                    return '';
                }
            })
            ->editColumn('bhxh_decrease', function ($data) use ($month, $year){
                // Tính toán số tiền giảm cho 1- bhxh
                $insurance = Insurance::where('employee_id', $data->work->employee_id)
                                                        ->where('insurance_type_id', 1)
                                                        ->first();
                if ($insurance) {
                    $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                    if ($salary) {
                        $bhxh_increase = $salary->insurance_salary * $insurance->pay_rate / 100;
                        return number_format($bhxh_increase, 0, '.', ',');
                    } else {
                        return '';
                    }
                } else {
                    return 'Chưa khai báo BHXH';
                }
            })
            ->editColumn('bhtn_decrease', function ($data) use ($month, $year){
                // Tính toán số tiền giảm cho 2- bhtn
                $insurance = Insurance::where('employee_id', $data->work->employee_id)
                                                        ->where('insurance_type_id', 2)
                                                        ->first();
                if ($insurance) {
                    $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                    if ($salary) {
                        $bhxh_increase = $salary->insurance_salary * $insurance->pay_rate / 100;
                        return number_format($bhxh_increase, 0, '.', ',');
                    } else {
                        return '';
                    }
                    } else {
                    return 'Chưa khai báo BHTN';
                }
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    private function getEmployeeSalaryByMonthYear($employee_id, $month, $year)
    {
        // Tìm các Salary với trạng thái On
        $on_salary = Salary::where('employee_id', $employee_id)
                            ->where('status', 'On')
                            ->whereYear('start_date', '<=', $year)
                            ->whereMonth('start_date', '<=', $month)
                            ->first();
        if ($on_salary) {
            return $on_salary;
        } else {
            // Tìm các Salary với trạng thái Off
            $off_salaries = Salary::where('employee_id', $employee_id)
                                    ->where('status', 'Off')
                                    ->whereYear('start_date', '<=', $year)
                                    ->whereYear('end_date', '>=', $year)
                                    ->get();
            if ($off_salaries->count() > 1) {
                // Tiếp tục lọc theo tháng
            return Salary::where('employee_id', $employee_id)
                        ->where('status', 'Off')
                        ->whereYear('start_date', '<=', $year)
                        ->whereYear('end_date', '>=', $year)
                        ->whereMonth('start_date', '<=', $month)
                        ->whereMonth('end_date', '>=', $month)
                        ->first();
            } else {
                // Trả về luôn
                return Salary::where('employee_id', $employee_id)
                            ->where('status', 'Off')
                            ->whereYear('start_date', '<=', $year)
                            ->whereYear('end_date', '>=', $year)
                            ->first();
            }
        }
    }
}
