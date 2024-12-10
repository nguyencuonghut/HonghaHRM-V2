<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRewardRequest;
use App\Http\Requests\UpdateRewardRequest;
use App\Models\Position;
use App\Models\Reward;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reward.index');
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
    public function store(StoreRewardRequest $request)
    {
        if (Auth::user()->cannot('create', Reward::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $reward = new Reward();
        $reward->employee_id = $request->employee_id;
        $reward->position_id = $request->position_id;
        $reward->code = $request->code;
        $reward->sign_date = Carbon::createFromFormat('d/m/Y', $request->sign_date);
        $reward->content = $request->content;
        if ($request->note) {
            $reward->note = $request->note;
        }
        $reward->save();

        Alert::toast('Nhập khen thưởng mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Reward $reward)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reward $reward)
    {
        if (Auth::user()->cannot('update', $reward)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $my_position_ids = Work::where('employee_id', $reward->employee_id)->where('status', 'On')->pluck('position_id')->toArray();
        $my_positions = Position::whereIn('id', $my_position_ids)->get();

        return view('reward.edit', [
            'reward' => $reward,
            'my_positions' => $my_positions,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRewardRequest $request, Reward $reward)
    {
        $reward->code = $request->code;
        $reward->position_id = $request->position_id;
        $reward->sign_date = Carbon::createFromFormat('d/m/Y', $request->sign_date);
        $reward->content = $request->content;
        if ($request->note) {
            $reward->note = $request->note;
        }
        $reward->save();

        Alert::toast('Sửa khen thưởng thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $reward->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reward $reward)
    {
        if (Auth::user()->cannot('delete', $reward)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        $reward->delete();

        Alert::toast('Xóa khen thưởng thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function employeeData($employee_id)
    {
        $data = Reward::where('employee_id', $employee_id)->orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('code', function ($data) {
                return $data->code;
            })
            ->editColumn('sign_date', function ($data) {
                return date('d/m/Y', strtotime($data->sign_date));
            })
            ->editColumn('content', function ($data) {
                return $data->content;
            })
            ->editColumn('note', function ($data) {
                return $data->note;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("rewards.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("rewards.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'content', 'note'])
            ->make(true);
    }

    public function anyData()
    {
        //Display Reward based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Reward::whereIn('employee_id', $employee_ids)
                        ->join('employees', 'employees.id', 'rewards.employee_id')
                        ->select('rewards.*', 'employees.code as employees_code')
                        ->orderBy('employees.code', 'desc')
                        ->get();

        } else {
            $data = Reward::join('employees', 'employees.id', 'rewards.employee_id')
                        ->select('rewards.*', 'employees.code as employees_code')
                        ->orderBy('employees.code', 'desc')
                        ->get();
        }
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('employee', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';
            })
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('code', function ($data) {
                return $data->code;
            })
            ->editColumn('sign_date', function ($data) {
                return date('d/m/Y', strtotime($data->sign_date));
            })
            ->editColumn('content', function ($data) {
                return $data->content;
            })
            ->editColumn('note', function ($data) {
                return $data->note;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("rewards.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("rewards.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['department', 'employee', 'actions', 'content', 'note'])
            ->make(true);
    }
}
