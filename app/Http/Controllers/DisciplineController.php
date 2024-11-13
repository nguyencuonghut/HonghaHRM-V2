<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDisciplineRequest;
use App\Http\Requests\UpdateDisciplineRequest;
use App\Models\Discipline;
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
        $discipline->code = $request->code;
        $discipline->sign_date = Carbon::createFromFormat('d/m/Y', $request->dis_sign_date);
        $discipline->content = $request->dis_content;
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

        return view('discipline.edit', ['discipline' => $discipline]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDisciplineRequest $request, Discipline $discipline)
    {
        $discipline->code = $request->code;
        $discipline->sign_date = Carbon::createFromFormat('d/m/Y', $request->sign_date);
        $discipline->content = $request->content;
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
                $action = '<a href="' . route("disciplines.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("disciplines.destroy", $data->id) . '" method="POST">
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
        $data = Discipline::join('employees', 'employees.id', 'disciplines.employee_id')
                        ->select('disciplines.*', 'employees.code as employees_code')
                        ->orderBy('employees.code', 'desc')
                        ->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('department', function ($data) {
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
            ->editColumn('employee', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';
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
                $action = '<a href="' . route("disciplines.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("disciplines.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['department', 'employee', 'actions', 'content', 'note'])
            ->make(true);
    }
}