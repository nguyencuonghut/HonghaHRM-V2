<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKpiRequest;
use App\Http\Requests\UpdateKpiRequest;
use App\Models\Kpi;
use App\Models\Position;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class KpiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('kpi.index');
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
    public function store(StoreKpiRequest $request)
    {
        if (Auth::user()->cannot('create', Kpi::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $kpi = new Kpi();
        $kpi->employee_id = $request->employee_id;
        $kpi->position_id = $request->position_id;
        $kpi->year = $request->year;
        $kpi->month = $request->month;
        $kpi->score = $request->score;
        $kpi->save();

        Alert::toast('Nhập KPI mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Kpi $kpi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kpi $kpi)
    {
        if (Auth::user()->cannot('update', $kpi)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $my_position_ids = Work::where('employee_id', $kpi->employee_id)->where('status', 'On')->pluck('position_id')->toArray();
        $my_positions = Position::whereIn('id', $my_position_ids)->get();

        return view('kpi.edit', [
            'kpi' => $kpi,
            'my_positions' => $my_positions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKpiRequest $request, Kpi $kpi)
    {
        $kpi->position_id = $request->position_id;
        $kpi->year = $request->year;
        $kpi->month = $request->month;
        $kpi->score = $request->score;
        $kpi->save();

        Alert::toast('Lưu KPI thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $kpi->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kpi $kpi)
    {
        if (Auth::user()->cannot('delete', $kpi)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        $kpi->delete();

        Alert::toast('Xóa KPI mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        $data = Kpi::join('employees', 'employees.id', 'kpis.employee_id')
                    ->select('kpis.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('employee', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';
            })
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('year', function ($data) {
                return $data->year;
            })
            ->editColumn('month', function ($data) {
                return $data->month;
            })
            ->editColumn('score', function ($data) {
                return $data->score;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("kpis.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("kpis.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['department', 'employee', 'actions'])
            ->make(true);
    }

    public function employeeData($employee_id)
    {
        $data = Kpi::where('employee_id', $employee_id)->orderBy('id', 'desc')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('year', function ($data) {
                return $data->year;
            })
            ->editColumn('month', function ($data) {
                return $data->month;
            })
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('score', function ($data) {
                return $data->score;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("kpis.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("kpis.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

}
