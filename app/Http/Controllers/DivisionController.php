<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportDivisionRequest;
use App\Http\Requests\StoreDivisionRequest;
use App\Http\Requests\UpdateDivisionRequest;
use App\Imports\DivisionImport;
use App\Models\Department;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->cannot('viewAny', Division::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('home');
        }

        return view('division.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Division::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('divisions.index');
        }

        $departments = Department::orderBy('id', 'desc')->get();
        return view('division.create', ['departments' => $departments]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDivisionRequest $request): RedirectResponse
    {
        $division = new Division();
        $division->name = $request->name;
        $division->department_id = $request->department_id;
        $division->save();

        Alert::toast('Thêm bộ phận thành công!', 'success', 'top-right');
        return redirect()->route('divisions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        if (Auth::user()->cannot('update', $division)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('divisions.index');
        }

        $departments = Department::orderBy('id', 'desc')->get();
        return view('division.edit',
                    [
                        'division' => $division,
                        'departments' => $departments,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDivisionRequest $request, Division $division): RedirectResponse
    {
        $division->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
        ]);

        Alert::toast('Sửa bộ phận thành công!', 'success', 'top-right');
        return redirect()->route('divisions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division): RedirectResponse
    {
        if (Auth::user()->cannot('delete', $division)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('divisions.index');
        }

        //Check if Division is used or not
        $positions = Position::where('division_id', $division->id)->get();
        if ($positions->count()) {
            Alert::toast('Vị trí đang được sử dụng. Không thể xóa!', 'error', 'top-rigth');
            return redirect()->route('divisions.index');
        }

        $division->delete();

        Alert::toast('Xóa bộ phận thành công!', 'success', 'top-rigth');
        return redirect()->route('divisions.index');
    }

    public function anyData()
    {
        $data = Division::with('department')->orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('department', function($row) {
                return '<a href="' . route("departments.show", $row->department->id) . '">' . $row->department->name . '</a>';
            })
            ->editColumn('position_lists', function ($divisions) {
                $positions = Position::where('division_id', $divisions->id)->orderBy('name')->get();
                $i = 0;
                $length = count($positions);
                $position_lists = '';
                foreach ($positions as $position) {
                    if(++$i === $length) {
                        $position_lists =  $position_lists . $position->name;
                    } else {
                        $position_lists = $position_lists . $position->name . ', <br>';
                    }
                }
                return $position_lists;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("divisions.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("divisions.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['department', 'actions', 'position_lists'])
            ->make(true);
    }

    public function import(ImportDivisionRequest $request)
    {
        try {
            $import = new DivisionImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $duplicates = $import->getDuplicateCount();
            $duplicate_rows = $import->getDuplicateRows();
            $duplicate_rows = $import->getDuplicateRows();
            $invalid_dept_name_row = $import->getInvalidDeptNameRow();
            if ($duplicates) {
                $duplicate_rows_list = implode(', ', $duplicate_rows);
                Alert::toast('Các dòng bị trùng lặp là '. $duplicate_rows_list);
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công! Có ' . $duplicates . ' dòng bị trùng lặp! Lặp tại dòng số: ' . $duplicate_rows_list, 'success', 'top-right');
                return redirect()->back();
            }

            if ($invalid_dept_name_row) {
                Alert::toast('Không tìm thấy tên phòng/ban tại dòng thứ ' . $invalid_dept_name_row, 'error', 'top-right');
                return redirect()->back();
            }
            Alert::toast('Import '. $rows . ' dòng dữ liệu thành công!', 'success', 'top-right');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Có lỗi xảy ra trong quá trình import dữ liệu. Vui lòng kiểm tra lại file!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
