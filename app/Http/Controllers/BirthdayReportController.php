<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BirthdayReportController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Employee::orderBy('code', 'desc');

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_code', function ($data) {
                    return $data->code;
                })
                ->editColumn('employee_name', function ($data) {
                    return '<a href=' . route("employees.show", $data->id) . '>' . $data->name . '</a>' ;
                })
                ->editColumn('position', function ($data) {
                    $my_position_ids = Work::where('employee_id', $data->id)
                                    ->where(function ($query) {
                                        $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                            ->orWhereNull('off_type_id');
                                    })
                                    ->pluck('position_id')
                                    ->toArray();
                    $my_positions = Position::whereIn('id', $my_position_ids)->get();
                    $position_str = '';
                    $i = 0;
                    $length = count($my_positions);
                    if ($length) {
                        foreach ($my_positions as $my_position) {
                            if(++$i === $length) {
                                $position_str .= $my_position->name;
                            } else {
                                $position_str .= $my_position->name;
                                $position_str .= ' | ';
                            }
                        }
                    } else {
                        $position_str .= '!! Chưa gán vị trí công việc !!';
                    }
                    return $position_str;
                })
                ->editColumn('division', function ($data) {
                    return '-';
                })
                ->editColumn('department', function ($data) {
                    $dept_arr = [];
                    $department_str = '';
                    //Tìm tất cả Works
                    $works = Work::where('employee_id', $data->id)->get();
                    if (0 == $works->count()) {
                        return 'Chưa có QT công tác';
                    } else {//Đã có QT công tác
                        $on_works = Work::where('employee_id', $data->id)
                                        ->where('status', 'On')
                                        ->get();
                        if ($on_works->count()) {//Có QT công tác ở trạng thái On
                            foreach ($on_works as $on_work) {
                                array_push($dept_arr, $on_work->position->department->name);
                            }
                        } else {//Còn lại là các QT công tác ở trạng thái Off
                            $last_off_works = Work::where('employee_id', $data->id)
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
                ->editColumn('gender', function ($data) {
                    return $data->gender;
                })
                ->editColumn('date_of_birth', function ($data) {
                    return date('d/m/Y', strtotime($data->date_of_birth));
                })
                ->editColumn('formal_contract_start_date', function ($data) {
                    //Find the formal contract
                    $contract = Contract::where('employee_id', $data->id)
                                ->where('contract_type_id', 2)//2: HĐ chính thức
                                ->where('status', 'On')
                                ->orderBy('start_date', 'desc')
                                ->first();
                    if ($contract) {
                        return date('d/m/Y', strtotime($contract->start_date));
                    } else {
                        return '-';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    if ($request->get('month')) {
                        $instance->whereMonth('date_of_birth', $request->get('month'));
                    }
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('name', 'LIKE', "%$search%")
                            ->orWhere('gender', 'LIKE', "%$search%")
                            ->orWhere('date_of_birth', 'LIKE', "%$search%")
                            ->orWhere('company_email', 'LIKE', "%$search%");
                        });
                    }
                })
                ->rawColumns(['employee_name'])
                ->make(true);
        }

        return view('report.birthday.index');
    }
}
