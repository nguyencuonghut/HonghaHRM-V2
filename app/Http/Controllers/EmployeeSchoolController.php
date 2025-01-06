<?php

namespace App\Http\Controllers;

use App\Models\EmployeeSchool;
use App\Models\Position;
use App\Models\UserDepartment;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    // public function import(ImportWorkRequest $request)
    // {
    //     try {
    //         $import = new WorkImport;
    //         Excel::import($import, $request->file('file')->store('files'));
    //         $rows = $import->getRowCount();
    //         $invalid_contract_code_row = $import->getInvalidContractCodeRow();
    //         $invalid_employee_name_row = $import->getInvalidEmployeeNameRow();
    //         $invalid_position_name_row = $import->getInvalidPositionNameRow();
    //         $invalid_on_type_name_row = $import->getInvalidOnTypeNameRow();
    //         $invalid_off_type_name_row = $import->getInvalidOffTypeNameRow();

    //         if ($invalid_contract_code_row) {
    //             Alert::toast('Không tìm thấy mã hợp đồng tại dòng thứ ' . $invalid_contract_code_row, 'error', 'top-right');
    //             return redirect()->back();
    //         }

    //         if ($invalid_employee_name_row) {
    //             Alert::toast('Không tìm thấy tên nhân viên tại dòng thứ ' . $invalid_employee_name_row, 'error', 'top-right');
    //             return redirect()->back();
    //         }

    //         if ($invalid_position_name_row) {
    //             Alert::toast('Không tìm thấy vị trí tại dòng thứ ' . $invalid_position_name_row, 'error', 'top-right');
    //             return redirect()->back();
    //         }

    //         if ($invalid_on_type_name_row) {
    //             Alert::toast('Không tìm thấy OnType tại dòng thứ ' . $invalid_on_type_name_row, 'error', 'top-right');
    //             return redirect()->back();
    //         }

    //         if ($invalid_off_type_name_row) {
    //             Alert::toast('Không tìm thấy OffType tại dòng thứ ' . $invalid_off_type_name_row, 'error', 'top-right');
    //             return redirect()->back();
    //         }

    //         Alert::toast('Import '. $rows . ' dòng dữ liệu thành công!', 'success', 'top-right');
    //         return redirect()->back();
    //     } catch (\Exception $e) {
    //         Alert::toast('Có lỗi xảy ra trong quá trình import dữ liệu. Vui lòng kiểm tra lại file!', 'error', 'top-right');
    //         return redirect()->back();
    //     }
    // }
}
