<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Family;
use App\Models\JoinDate;
use App\Models\Probation;
use App\Models\Recruitment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        //Số phòng ban
        $department_cnt = Department::count();
        //Số nhân sự đang làm việc
        $employee_ids = Work::where(function ($query) {
                                $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                    ->orWhereNull('off_type_id');
                            })
                            ->where('status', 'On')
                            ->pluck('employee_id')
                            ->toArray();
        $employee_cnt = Employee::whereIn('id', $employee_ids)->count();
        //Số yêu cầu tuyển dụng
        $recruitment_cnt = Recruitment::count();
        //Số thử việc
        $probation_cnt = Probation::where('approver_result', null)->count();
        //Số sinh nhật tháng này
        $birthday_cnt = Employee::whereIn('id', $employee_ids)->whereMonth('date_of_birth', Carbon::now()->month)->count();
        //Số hoàn cảnh
        $situation_cnt = Family::where('situation', '!=', null)->count();
        //Chế độ 1-6 cho thiếu nhi
        $kid_cnt = Family::whereIn('type', ['Con trai', 'Con gái'])
                        ->where('year_of_birth', '>=', Carbon::now()->year - 15)
                        ->count();
        //Thâm niên 5 năm
        $seniority_cnt = JoinDate::whereYear('join_date', Carbon::now()->year - 5)
                    ->whereIn('employee_id', $employee_ids)
                    ->join('employees', 'employees.id', 'join_dates.employee_id')
                    ->count();

        return view('home', [
            'department_cnt' => $department_cnt,
            'employee_cnt' => $employee_cnt,
            'recruitment_cnt' => $recruitment_cnt,
            'probation_cnt' => $probation_cnt,
            'birthday_cnt' => $birthday_cnt,
            'situation_cnt' => $situation_cnt,
            'kid_cnt' => $kid_cnt,
            'seniority_cnt' => $seniority_cnt,
        ]);
    }
}
