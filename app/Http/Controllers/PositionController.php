<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportPositionRequest;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Imports\PositionImport;
use App\Models\Department;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->cannot('viewAny', Position::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('home');
        }

        return view('position.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Position::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('positions.index');
        }

        $departments = Department::orderBy('id', 'desc')->get();
        $divisions = Division::orderBy('id', 'desc')->get();
        return view('position.create',
                    [
                        'departments' => $departments,
                        'divisions' => $divisions,
                    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePositionRequest $request)
    {
        $position = new Position();
        $position->name = $request->name;
        $position->department_id = $request->department_id;
        if ($request->division_id) {
            $position->division_id = $request->division_id;
        }
        $position->insurance_salary = $request->insurance_salary;
        $position->position_salary = $request->position_salary;
        $position->max_capacity_salary = $request->max_capacity_salary;
        if ($request->position_allowance) {
            $position->position_allowance = $request->position_allowance;
        }

        //Store uploaded file
        if ($request->hasFile('recruitment_standard_file')) {
            $path = 'dist/recruitment_standard';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('recruitment_standard_file');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $position->recruitment_standard_file = $path . '/' . $name;
        }
        $position->save();

        Alert::toast('Thêm vị trí thành công!', 'success', 'top-right');
        return redirect()->route('positions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Position $position)
    {
        if (Auth::user()->cannot('update', $position)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('positions.index');
        }

        $departments = Department::orderBy('id', 'desc')->get();
        $divisions = Division::orderBy('id', 'desc')->get();
        return view('position.edit',
                    [
                        'divisions' => $divisions,
                        'departments' => $departments,
                        'position' => $position,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        $position->name = $request->name;
        $position->department_id = $request->department_id;
        if ($request->division_id) {
            $position->division_id = $request->division_id;
        }
        $position->insurance_salary = $request->insurance_salary;
        $position->position_salary = $request->position_salary;
        $position->max_capacity_salary = $request->max_capacity_salary;
        $position->position_allowance = $request->position_allowance;

        //Store uploaded file
        if ($request->hasFile('recruitment_standard_file')) {
            //Delete old file
            if (file_exists($position->recruitment_standard_file)) {
                unlink(public_path($position->recruitment_standard_file));
            }

            $path = 'dist/recruitment_standard';
            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('recruitment_standard_file');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $position->recruitment_standard_file = $path . '/' . $name;
        }
        $position->save();

        Alert::toast('Sửa vị trí thành công!', 'success', 'top-right');
        return redirect()->route('positions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        if (Auth::user()->cannot('delete', $position)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('positions.index');
        }

        //Check if Position is used or not
        if ($position->recruitments->count()
            || $position->contracts->count()
            || $position->works->count()) {
            Alert::toast('Vị trí đang được sử dụng. Không thể xóa!', 'error', 'top-right');
            return redirect()->route('positions.index');
        }
        $position->delete();

        Alert::toast('Xóa vị trí thành công!', 'success', 'top-rigth');
        return redirect()->route('positions.index');
    }

    public function anyData()
    {
        $data = Position::with(['department', 'division'])->orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('department', function($row) {
                return '<a href="' . route("departments.show", $row->department->id) . '">' . $row->department->name . '</a>';
            })
            ->addColumn('division', function($row) {
                if ($row->division_id) {
                    return $row->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('insurance_salary', function ($row) {
                return number_format($row->insurance_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('position_salary', function ($row) {
                return number_format($row->position_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('max_capacity_salary', function ($row) {
                return number_format($row->max_capacity_salary, 0, '.', ',') . '<sup>đ</sup>';
            })
            ->editColumn('position_allowance', function ($row) {
                if ($row->position_allowance) {
                    return number_format($row->position_allowance, 0, '.', ',') . '<sup>đ</sup>';
                } else {
                    return '-';
                }
            })
            ->editColumn('recruitment_standard_file', function ($row) {
                return '<a target="_blank" href="../../../' . $row->recruitment_standard_file . '"><i class="far fa-file-pdf"></i></a>';
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("positions.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("positions.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'department', 'insurance_salary', 'position_salary', 'max_capacity_salary', 'position_allowance', 'recruitment_standard_file'])
            ->make(true);
    }

    public function import(ImportPositionRequest $request)
    {
        try {
            $import = new PositionImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $duplicates = $import->getDuplicateCount();
            $duplicate_rows = $import->getDuplicateRows();
            $duplicate_rows = $import->getDuplicateRows();
            $invalid_dept_name_row = $import->getInvalidDeptNameRow();
            $invalid_divi_name_row = $import->getInvalidDiviNameRow();
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

            if ($invalid_divi_name_row) {
                Alert::toast('Không tìm thấy tên bộ phận tại dòng thứ ' . $invalid_divi_name_row, 'error', 'top-right');
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
