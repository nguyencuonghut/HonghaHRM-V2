<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInsuranceRequest;
use App\Http\Requests\UpdateInsuranceRequest;
use App\Models\Insurance;
use App\Models\InsuranceType;
use App\Models\Position;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('insurance.index');
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
    public function store(StoreInsuranceRequest $request)
    {
        if (Auth::user()->cannot('create', Insurance::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('insurances.index');
        }

        $insurance = new Insurance();
        $insurance->employee_id = $request->employee_id;
        $insurance->insurance_type_id = $request->insurance_type_id;
        $insurance->start_date = Carbon::createFromFormat('d/m/Y', $request->insurance_s_date);
        if ($request->insurance_e_date) {
            $insurance->end_date = Carbon::createFromFormat('d/m/Y', $request->insurance_e_date);
        }
        $insurance->pay_rate = $request->pay_rate;
        $insurance->save();

        Alert::toast('Tạo mới bảo hiểm thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        if (Auth::user()->cannot('update', $insurance)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('insurances.index');
        }

        $insurance_types = InsuranceType::all();
        return view('insurance.edit', [
            'insurance' => $insurance,
            'insurance_types' => $insurance_types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInsuranceRequest $request, Insurance $insurance)
    {

        $insurance->start_date = Carbon::createFromFormat('d/m/Y', $request->insurance_s_date);
        if ($request->insurance_e_date) {
            $insurance->end_date = Carbon::createFromFormat('d/m/Y', $request->insurance_e_date);
        } else {
            $insurance->end_date = null;
        }
        $insurance->pay_rate = $request->pay_rate;
        $insurance->save();

        Alert::toast('Sửa bảo hiểm thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $insurance->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        if (Auth::user()->cannot('delete', $insurance)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('insurances.index');
        }
        $insurance->delete();

        Alert::toast('Xóa bảo hiểm thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        //Display Insurance based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Insurance::whereIn('employee_id', $employee_ids)
                            ->join('employees', 'employees.id', 'insurances.employee_id')
                            ->select('insurances.*', 'employees.code as employees_code')
                            ->orderBy('employees_code', 'desc')
                            ->get();
        } else {
            $data = Insurance::join('employees', 'employees.id', 'insurances.employee_id')
                            ->select('insurances.*', 'employees.code as employees_code')
                            ->orderBy('employees_code', 'desc')
                            ->get();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('insurance_type', function ($data) {
                return $data->insurance_type->name;
            })
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
            ->editColumn('employee_bhxh', function ($data) {
                return $data->employee->bhxh;
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
            ->editColumn('pay_rate', function ($data) {
                return $data->pay_rate;
            })
            ->rawColumns(['employee_name', 'employee_department'])
            ->make(true);
    }
}
