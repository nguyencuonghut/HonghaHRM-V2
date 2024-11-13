<?php

namespace App\Http\Controllers;

use App\Http\Requests\OffWorkRequest;
use App\Http\Requests\StoreWorkRequest;
use App\Http\Requests\UpdateWorkRequest;
use App\Models\OffType;
use App\Models\OnType;
use App\Models\Position;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('work.index');
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
    public function store(StoreWorkRequest $request)
    {
        if (Auth::user()->cannot('create', Work::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Create new Work
        $work = new Work();
        $work->employee_id = $request->employee_id;
        $work->position_id = $request->position_id;
        $work->on_type_id = $request->on_type_id;
        $work->start_date = Carbon::createFromFormat('d/m/Y', $request->s_date);
        $work->contract_code = $request->contract_code;
        $work->status = 'On';
        $work->save();

        // TODO: Tạo bảng theo dõi tăng BHXH với HĐ ký mới là HĐLĐ
        // if (2 == $request->on_type_id) {
        //     $increase_decrease_insurance = new IncreaseDecreaseInsurance();
        //     $increase_decrease_insurance->employee_work_id = $employee_work->id;
        //     $increase_decrease_insurance->is_increase = true;
        //     $increase_decrease_insurance->save();
        // }

        Alert::toast('Thêm quá trình công tác mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work)
    {
        if (Auth::user()->cannot('update', $work)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $positions = Position::all();
        $on_types = OnType::all();
        return view('work.edit', [
            'work' => $work,
            'positions' => $positions,
            'on_types' => $on_types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkRequest $request, Work $work)
    {
        //Edit Work
        $work->position_id = $request->position_id;
        $work->on_type_id = $request->on_type_id;
        $work->start_date = Carbon::createFromFormat('d/m/Y', $request->s_date);
        $work->contract_code = $request->contract_code;
        $work->save();

        //TODO: Xóa bảng theo dõi tăng BHXH với HĐ ký mới khác HĐLĐ
        // if (2 != $request->on_type_id) {
        //     $increase_decrease_insurance = IncreaseDecreaseInsurance::where('employee_work_id', $employee_work->id)->first();
        //     if ($increase_decrease_insurance) {
        //         $increase_decrease_insurance->destroy($increase_decrease_insurance->id);
        //     }
        // }

        Alert::toast('Sửa quá trình công tác mới thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $work->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work)
    {
        if (Auth::user()->cannot('delete', $work)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        $work->delete();

        Alert::toast('Xóa quá trình công tác thành công!', 'success', 'top-rigth');
        return redirect()->back();
    }


    public function getOff(Work $work)
    {
        $off_types = OffType::all();
        return view('work.off',
                    [
                        'work' => $work,
                        'off_types' => $off_types,
                    ]);
    }

    public function off(OffWorkRequest $request, Work $work)
    {
        // Off the Work
        $work->status = 'Off';
        $work->end_date = Carbon::createFromFormat('d/m/Y', $request->e_date);
        if ($request->off_type_id) {
            $work->off_type_id = $request->off_type_id;
        }
        if ($request->off_reason) {
            $work->off_reason = $request->off_reason;
        }
        $work->save();

        //TODO: Tạo bảng theo dõi giảm BHXH
        // if (2 == $work->on_type_id
        //     || 3 == $work->on_type_id
        //     || 4 == $work->on_type_id) {
        //     $increase_decrease_insurance = new IncreaseDecreaseInsurance();
        //     $increase_decrease_insurance->employee_work_id = $employee_work->id;
        //     $increase_decrease_insurance->is_decrease = true;
        //     $increase_decrease_insurance->save();
        // }

        Alert::toast('Cập nhật thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $work->employee_id);
    }

    public function anyData()
    {
        $data = Work::join('employees', 'employees.id', 'works.employee_id')
                                        ->orderBy('employees.code', 'desc')
                                        ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->name . ' - ' . $data->position->division->name .  '- ' . $data->position->department->name;

                } else {
                    return $data->position->name . ' - ' . $data->position->department->name;
                }
            })
            ->editColumn('start_date', function ($data) {
                return date('d/m/Y', strtotime($data->start_date));
            })
            ->editColumn('end_date', function ($data) {
                if ($data->end_date) {
                    return date('d/m/Y', strtotime($data->end_date));
                } else {
                    return '-';
                }
            })
            ->editColumn('status', function ($data) {
                if ('On' == $data->status) {
                    return '<span class="badge badge-success">' . $data->status . '</span>';
                } else {
                    return '<span class="badge badge-danger">' . $data->status . '</span>';
                }
            })
            ->editColumn('on_type', function ($data) {
                if ($data->on_type_id) {
                    return $data->on_type->name;
                }
            })
            ->editColumn('off_type', function ($data) {
                if ($data->off_type_id) {
                    return $data->off_type->name;
                }
            })
            ->editColumn('off_reason', function ($data) {
                return $data->off_reason;
            })
            ->rawColumns(['employee_name', 'status', 'off_reason'])
            ->make(true);
    }
}
