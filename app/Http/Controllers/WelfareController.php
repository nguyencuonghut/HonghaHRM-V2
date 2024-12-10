<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWelfareRequest;
use App\Http\Requests\UpdateWelfareRequest;
use App\Models\Position;
use App\Models\UserDepartment;
use App\Models\Welfare;
use App\Models\WelfareType;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class WelfareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('welfare.index');
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
    public function store(StoreWelfareRequest $request)
    {
        if (Auth::user()->cannot('create', Welfare::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $welfare = new Welfare();
        $welfare->employee_id = $request->employee_id;
        $welfare->welfare_type_id = $request->welfare_type_id;
        if($request->payment_date) {
            $welfare->payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date);
        }
        if($request->payment_amount) {
            $welfare->payment_amount = $request->payment_amount;
            $welfare->status = 'Đóng';
        } else {
            $welfare->status = 'Mở';
        }
        $welfare->save();

        Alert::toast('Tạo mới phúc lợi thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Welfare $welfare)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Welfare $welfare)
    {
        if (Auth::user()->cannot('edit', $welfare)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $welfare_types = WelfareType::all();
        return view('welfares.edit', [
            'welfare' => $welfare,
            'welfare_types' => $welfare_types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWelfareRequest $request, Welfare $welfare)
    {
        $welfare->welfare_type_id = $request->welfare_type_id;
        if($request->payment_date) {
            $welfare->payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date);
        }
        if($request->payment_amount) {
            $welfare->payment_amount = $request->payment_amount;
            $welfare->status = 'Đóng';
        } else {
            $welfare->status = 'Mở';
            $welfare->payment_amount = 0;
        }
        $welfare->save();

        Alert::toast('Sửa phúc lợi thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $welfare->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Welfare $welfare)
    {
        if (Auth::user()->cannot('delete', $welfare)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        $welfare->delete();

        Alert::toast('Xóa phúc lợi thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        //Display Welfare based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Welfare::whereIn('employee_id', $employee_ids)
                            ->join('employees', 'employees.id', 'welfares.employee_id')
                            ->select('welfares.*', 'employees.code as employees_code')
                            ->orderBy('employees_code', 'desc')
                            ->get();
        } else {
            $data = Welfare::join('employees', 'employees.id', 'welfares.employee_id')
                            ->select('welfares.*', 'employees.code as employees_code')
                            ->orderBy('employees_code', 'desc')
                            ->get();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('employee_department', function ($data) {
                $employee_works = Work::where('employee_id', $data->employee_id)->where('status', 'On')->get();
                $employee_department_str = '';
                $i = 0;
                $length = count($employee_works);
                if ($length) {
                    foreach ($employee_works as $employee_work) {
                        if(++$i === $length) {
                            $employee_department_str .= $employee_work->position->department->name;
                        } else {
                            $employee_department_str .= $employee_work->position->department->name;
                            $employee_department_str .= ' | ';
                        }
                    }
                } else {
                    $employee_department_str .= '!! Chưa gán vị trí công việc !!';
                }
                return $employee_department_str;
            })
            ->editColumn('welfare_type', function ($data) {
                return $data->welfare_type->name;
            })
            ->editColumn('payment_date', function ($data) {
                if ($data->payment_date) {
                    return date('d/m/Y', strtotime($data->payment_date));
                } else {
                    return '';
                }
            })
            ->editColumn('payment_amount', function ($data) {
                if ($data->payment_amount) {
                    return number_format($data->payment_amount, 0, '.', ',');
                } else {
                    return '';
                }
            })
            ->editColumn('status', function ($data) {
                if ('Mở' == $data->status) {
                    return '<span class="badge badge-success">' . $data->status . '</span>';
                } else {
                    return '<span class="badge badge-secondary">' . $data->status . '</span>';
                }
            })
            ->rawColumns(['employee_name', 'employee_department', 'payment_amount', 'status'])
            ->make(true);
    }
}
