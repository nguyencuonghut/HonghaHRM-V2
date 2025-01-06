<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportEmployeeSchoolRequest;
use App\Imports\EmployeeSchoolImport;
use App\Models\EmployeeSchool;
use App\Models\Position;
use App\Models\UserDepartment;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class EmployeeSchoolController extends Controller
{
    public function index()
    {
        return view('employee_school.index');
    }


    public function anyData()
    {
        //Display EmployeeSchool based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = EmployeeSchool::whereIn('employee_id', $employee_ids)
                        ->join('employees', 'employees.id', 'employee_schools.employee_id')
                        ->select('employee_schools.*', 'employees.code as employees_code')
                        ->orderBy('employees_code', 'desc')
                        ->get();
        } else {
            $data = EmployeeSchool::join('employees', 'employees.id', 'employee_schools.employee_id')
                        ->select('employee_schools.*', 'employees.code as employees_code')
                        ->orderBy('employees_code', 'desc')
                        ->get();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('employee', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';
            })
            ->editColumn('school', function ($data) {
                return $data->school->name;
            })
            ->editColumn('degree', function ($data) {
                return $data->degree->name;
            })
            ->editColumn('major', function ($data) {
                return $data->major;
            })
            ->rawColumns(['employee'])
            ->make(true);
    }

    public function import(ImportEmployeeSchoolRequest $request)
    {
        try {
            $import = new EmployeeSchoolImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $invalid_employee_name_row = $import->getInvalidEmployeeNameRow();
            $invalid_school_name_row = $import->getInvalidSchoolNameRow();
            $invalid_degree_name_row = $import->getInvalidDegreeNameRow();

            if ($invalid_employee_name_row) {
                Alert::toast('Không tìm thấy tên nhân viên tại dòng thứ ' . $invalid_employee_name_row, 'error', 'top-right');
                return redirect()->back();
            }

            if ($invalid_school_name_row) {
                Alert::toast('Không tìm thấy trường tại dòng thứ ' . $invalid_school_name_row, 'error', 'top-right');
                return redirect()->back();
            }

            if ($invalid_degree_name_row) {
                Alert::toast('Không tìm thấy trình độ tại dòng thứ ' . $invalid_degree_name_row, 'error', 'top-right');
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
