<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDegreeRequest;
use App\Http\Requests\UpdateDegreeRequest;
use App\Models\CandidateSchool;
use App\Models\Degree;
use App\Models\EmployeeSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('degree.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Degree::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('degrees.index');
        }

        return view('degree.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDegreeRequest $request)
    {
        $degree = new Degree();
        $degree->name = $request->name;
        $degree->save();

        Alert::toast('Thêm trình độ thành công!', 'success', 'top-right');
        return redirect()->route('degrees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Degree $degree)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Degree $degree)
    {
        if (Auth::user()->cannot('update', $degree)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('degrees.index');
        }

        return view('degree.edit', ['degree' => $degree]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDegreeRequest $request, Degree $degree)
    {
        $degree->update(['name' => $request->name]);

        Alert::toast('Sửa trình độ thành công!', 'success', 'top-right');
        return redirect()->route('degrees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Degree $degree)
    {
        if (Auth::user()->cannot('delete', $degree)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('degrees.index');
        }

        //Check if Degree is used or not
        $candidate_schools = CandidateSchool::where('degree_id', $degree->id)->get();
        $employee_schools = EmployeeSchool::where('degree_id', $degree->id)->get();
        if ($candidate_schools->count()
            || $employee_schools->count()) {
            Alert::toast('Trình độ đang được dùng. Bạn không thể xóa!', 'error', 'top-right');
            return redirect()->route('degrees.index');
        }
        $degree->delete();

        Alert::toast('Xóa trình độ thành công!', 'success', 'top-rigth');
        return redirect()->route('degrees.index');
    }

    public function anyData()
    {
        $data = Degree::orderBy('id', 'asc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("degrees.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("degrees.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
