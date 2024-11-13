<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportSchoolRequest;
use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Imports\SchoolImport;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('school.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', School::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('schools.index');
        }
        return view('school.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolRequest $request)
    {
        $school = new School();
        $school->name = $request->name;
        $school->save();

        Alert::toast('Thêm trường thành công!', 'success', 'top-right');
        return redirect()->route('schools.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        if (Auth::user()->cannot('update', $school)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('schools.index');
        }

        return view('school.edit', ['school' => $school]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolRequest $request, School $school)
    {
        $school->update(['name' => $request->name]);

        Alert::toast('Sửa trường thành công!', 'success', 'top-right');
        return redirect()->route('schools.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        if (Auth::user()->cannot('delete', $school)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('schools.index');
        }

        //Check if School is used or not
        if ($school->candidates->count()
            || $school->employees->count()) {
            Alert::toast('Trường đang được sử dụng. Không thể xóa!', 'error', 'top-right');
            return redirect()->route('schools.index');

        }
        $school->delete();

        Alert::toast('Xóa trường thành công!', 'success', 'top-rigth');
        return redirect()->route('schools.index');
    }

    public function anyData()
    {
        $data = School::orderBy('name', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("schools.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("schools.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function import(ImportSchoolRequest $request)
    {
        try {
            $import = new SchoolImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $duplicates = $import->getDuplicateCount();
            $duplicate_rows = $import->getDuplicateRows();
            if ($duplicates) {
                $duplicate_rows_list = implode(', ', $duplicate_rows);
                Alert::toast('Các dòng bị trùng lặp là '. $duplicate_rows_list);
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công! Có ' . $duplicates . ' dòng bị trùng lặp! Lặp tại dòng số: ' . $duplicate_rows_list, 'success', 'top-right');
            } else {
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công!', 'success', 'top-right');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Có lỗi xảy ra trong quá trình import dữ liệu. Vui lòng kiểm tra lại file!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
