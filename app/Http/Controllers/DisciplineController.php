<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDisciplineRequest;
use App\Http\Requests\UpdateDisciplineRequest;
use App\Models\Discipline;
use App\Models\Position;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('discipline.index');
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
    public function store(StoreDisciplineRequest $request)
    {
        if (Auth::user()->cannot('create', Discipline::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $discipline = new Discipline();
        $discipline->employee_id = $request->employee_id;
        $discipline->position_id = $request->position_id;
        $discipline->code = $request->code;
        $discipline->sign_date = Carbon::createFromFormat('d/m/Y', $request->dis_sign_date);
        $discipline->content = $request->dis_content;
        if ($request->money) {
            $discipline->money = $request->money;
        }
        if ($request->dis_note) {
            $discipline->note = $request->dis_note;
        }
        $discipline->save();

        Alert::toast('Nhập kỷ luật mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Discipline $discipline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discipline $discipline)
    {
        if (Auth::user()->cannot('update', $discipline)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $my_position_ids = Work::where('employee_id', $discipline->employee_id)->where('status', 'On')->pluck('position_id')->toArray();
        $my_positions = Position::whereIn('id', $my_position_ids)->get();

        return view('discipline.edit', [
            'discipline' => $discipline,
            'my_positions' => $my_positions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDisciplineRequest $request, Discipline $discipline)
    {
        $discipline->position_id = $request->position_id;
        $discipline->code = $request->code;
        $discipline->sign_date = Carbon::createFromFormat('d/m/Y', $request->sign_date);
        $discipline->content = $request->content;
        $discipline->money = $request->money;
        if ($request->note) {
            $discipline->note = $request->note;
        }
        $discipline->save();

        Alert::toast('Sửa kỷ luật thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $discipline->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discipline $discipline)
    {
        if (Auth::user()->cannot('delete', $discipline)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $discipline->delete();

        Alert::toast('Xóa kỷ luật thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function employeeData($employee_id)
    {
        $data = Discipline::where('employee_id', $employee_id)->orderBy('id', 'desc')->get();
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
            ->editColumn('money', function ($data) {
                return number_format($data->money, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('note', function ($data) {
                return $data->note;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("disciplines.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("disciplines.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'content', 'note', 'money'])
            ->make(true);
    }

    public function anyData()
    {
        //Display Discipline based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Discipline::whereIn('employee_id', $employee_ids)
                            ->join('employees', 'employees.id', 'disciplines.employee_id')
                            ->select('disciplines.*', 'employees.code as employees_code')
                            ->orderBy('employees.code', 'desc')
                            ->get();
        } else {
            $data = Discipline::join('employees', 'employees.id', 'disciplines.employee_id')
                            ->select('disciplines.*', 'employees.code as employees_code')
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
            ->editColumn('money', function ($data) {
                return number_format($data->money, 0, '.', ',');
            })
            ->editColumn('note', function ($data) {
                return $data->note;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("disciplines.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("disciplines.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['department', 'employee', 'actions', 'content', 'note', 'money'])
            ->make(true);
    }
}
