<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegimeRequest;
use App\Http\Requests\UpdateRegimeRequest;
use App\Models\Position;
use App\Models\Regime;
use App\Models\RegimeType;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class RegimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('regime.index');
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
    public function store(StoreRegimeRequest $request)
    {
        if (Auth::user()->cannot('create', Regime::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $regime = new Regime();
        $regime->employee_id = $request->employee_id;
        $regime->regime_type_id = $request->regime_type_id;
        if($request->off_start_date) {
            $regime->off_start_date = Carbon::createFromFormat('d/m/Y', $request->off_start_date);
        }
        if($request->off_end_date) {
            $regime->off_end_date = Carbon::createFromFormat('d/m/Y', $request->off_end_date);
        }
        if($request->payment_period) {
            $regime->payment_period = $request->payment_period;
        }
        if($request->payment_amount) {
            $regime->payment_amount = $request->payment_amount;
            $regime->status = 'Đóng';
        } else {
            $regime->status = 'Mở';
        }
        $regime->save();

        Alert::toast('Tạo mới chế độ thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Regime $regime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Regime $regime)
    {
        if (Auth::user()->cannot('update', $regime)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $regime_types = RegimeType::all();
        return view('regime.edit', [
            'regime_types' => $regime_types,
            'regime' => $regime,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegimeRequest $request, Regime $regime)
    {
        $regime->regime_type_id = $request->regime_type_id;
        if($request->off_start_date) {
            $regime->off_start_date = Carbon::createFromFormat('d/m/Y', $request->off_start_date);
        }
        if($request->off_end_date) {
            $regime->off_end_date = Carbon::createFromFormat('d/m/Y', $request->off_end_date);
        }
        if($request->payment_period) {
            $regime->payment_period = $request->payment_period;
        } else {
            $regime->payment_period = null;
        }
        if($request->payment_amount) {
            $regime->payment_amount = $request->payment_amount;
            $regime->status = 'Đóng';
        } else {
            $regime->status = 'Mở';
            $regime->payment_amount = 0;
        }
        $regime->save();

        Alert::toast('Sửa chế độ thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $regime->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Regime $regime)
    {
        if (Auth::user()->cannot('delete', $regime)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $regime->delete();

        Alert::toast('Xóa chế độ thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        //Display Regime based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Regime::whereIn('employee_id', $employee_ids)
                            ->join('employees', 'employees.id', 'regimes.employee_id')
                            ->select('regimes.*', 'employees.code as employees_code')
                            ->orderBy('employees_code', 'desc')
                            ->get();
        } else {
            $data = Regime::join('employees', 'employees.id', 'regimes.employee_id')
                        ->select('regimes.*', 'employees.code as employees_code')
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
                $works = Work::where('employee_id', $data->employee_id)->orderBy('start_date', 'desc')->get();
                $department_str = '';
                $i = 0;
                $length = $works->count();
                if ($length) {
                    $on_works = Work::where('employee_id', $data->employee_id)->where('status', 'On')->get();
                    $on_length = $on_works->count();
                    if ($on_length) {
                        foreach ($on_works as $work) {
                            if(++$i === $on_length) {
                                $department_str .= $work->position->department->name;
                            } else {
                                $department_str .= $work->position->department->name;
                                $department_str .= ' | ';
                            }
                        }
                    } else {
                        //Get the last Work that has Status is Off
                        $last_work = $works->first();
                        $department_str .= $last_work->position->department->name;
                    }
                } else {
                    $department_str .= '!! Chưa gán vị trí công việc !!';
                }
                return $department_str;
            })
            ->editColumn('employee_bhxh', function ($data) {
                return $data->employee->bhxh;
            })
            ->editColumn('off_start_date', function ($data) {
                if ($data->off_start_date) {
                    return date('d/m/Y', strtotime($data->off_start_date));
                } else {
                    return '';
                }
            })
            ->editColumn('off_end_date', function ($data) {
                if ($data->off_end_date) {
                    return date('d/m/Y', strtotime($data->off_end_date));
                } else {
                    return '';
                }
            })
            ->editColumn('payment_period', function ($data) {
                return $data->payment_period;
            })
            ->editColumn('payment_amount', function ($data) {
                if ($data->payment_amount) {
                    return number_format($data->payment_amount, 0, '.', ',');
                } else {
                    return '';
                }
            })
            ->editColumn('regime_type', function ($data) {
                return $data->regime_type->name;
            })
            ->editColumn('status', function ($employee_regimes) {
                if ('Mở' == $employee_regimes->status) {
                    return '<span class="badge badge-success">' . $employee_regimes->status . '</span>';
                } else {
                    return '<span class="badge badge-secondary">' . $employee_regimes->status . '</span>';
                }
            })
            ->rawColumns(['employee_name', 'employee_department', 'payment_amount', 'status'])
            ->make(true);
    }
}
