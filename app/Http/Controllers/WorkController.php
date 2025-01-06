<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportWorkRequest;
use App\Http\Requests\OffWorkRequest;
use App\Http\Requests\StoreWorkRequest;
use App\Http\Requests\UpdateWorkRequest;
use App\Imports\WorkImport;
use App\Models\DecreaseInsurance;
use App\Models\IncreaseInsurance;
use App\Models\OffType;
use App\Models\OnType;
use App\Models\Position;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('work.index');
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
    public function store(StoreWorkRequest $request)
    {
        if (Auth::user()->cannot('create', Work::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Create new Work
        $work = new Work();
        $work->employee_id = $request->employee_id;
        $work->position_id = $request->wt_position_id;
        $work->on_type_id = $request->on_type_id;
        $work->start_date = Carbon::createFromFormat('d/m/Y', $request->s_date);
        $work->contract_code = $request->contract_code;
        $work->status = 'On';
        $work->save();

        //Tạo bảng theo dõi tăng BHXH với HĐ ký mới là HĐLĐ
        if (2 == $request->on_type_id) {//2: Ký HĐLĐ
            $increase_insurance = new IncreaseInsurance();
            $increase_insurance->work_id = $work->id;
            $increase_insurance->save();
        }

        Alert::toast('Thêm quá trình công tác mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Work $work)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Work $work)
    {
        if (Auth::user()->cannot('update', $work)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $positions = Position::orderBy('name', 'asc')->get();
        $on_types = OnType::all();
        return view('work.edit', [
            'work' => $work,
            'positions' => $positions,
            'on_types' => $on_types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkRequest $request, Work $work)
    {
        //Edit Work
        $work->position_id = $request->position_id;
        $work->on_type_id = $request->on_type_id;
        $work->start_date = Carbon::createFromFormat('d/m/Y', $request->s_date);
        $work->contract_code = $request->contract_code;
        $work->save();

        //Xóa bảng theo dõi tăng BHXH với HĐ ký mới khác HĐLĐ
        if (2 != $request->on_type_id) {//2: Ký HĐLĐ
            $increase_insurance = IncreaseInsurance::where('work_id', $work->id)->first();
            if ($increase_insurance) {
                $increase_insurance->delete();
            }
        }

        Alert::toast('Sửa quá trình công tác mới thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $work->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Work $work)
    {
        if (Auth::user()->cannot('delete', $work)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        if ('On' == $work->status) {
            // Xóa bảng theo dõi tăng BHXH với HĐ ký mới là HĐLĐ
            if ('Ký HĐLĐ' == $work->on_type->name) {
                $increase_insurance = IncreaseInsurance::where('work_id', $work->id)->first();
                if ($increase_insurance) {
                    $increase_insurance->delete();
                }
            }
        } else {
            // Xóa bảng theo dõi giảm BHXH với HĐ ký mới là HĐLĐ
            if ('Ký HĐLĐ' == $work->on_type->name ||
                'Tái ký HĐLĐ' == $work->on_type->name ||
                'Thay đổi chức danh, lương, phòng ban' == $work->on_type->name ||
                'Đi làm lại' == $work->on_type->name) {

                $decrease_insurance = DecreaseInsurance::where('work_id', $work->id)->first();
                if ($decrease_insurance) {
                    $decrease_insurance->delete();
                }
            }
        }

        $work->delete();

        Alert::toast('Xóa quá trình công tác thành công!', 'success', 'top-rigth');
        return redirect()->back();
    }


    public function getOff(Work $work)
    {
        $off_types = OffType::all();
        return view('work.off',
                    [
                        'work' => $work,
                        'off_types' => $off_types,
                    ]);
    }

    public function off(OffWorkRequest $request, Work $work)
    {
        // Off the Work
        $work->status = 'Off';
        $work->end_date = Carbon::createFromFormat('d/m/Y', $request->e_date);
        if ($request->off_type_id) {
            $work->off_type_id = $request->off_type_id;
        }
        if ($request->off_reason) {
            $work->off_reason = $request->off_reason;
        }
        $work->save();

        //Tạo bảng theo dõi giảm BHXH
        if ('Ký HĐLĐ' == $work->on_type->name
            || 'Tái ký HĐLĐ' == $work->on_type->name
            || 'Thay đổi chức danh, lương, phòng ban' == $work->on_type->name
            || 'Đi làm lại' == $work->on_type->name) {
            //Kiểm tra đã có record nào tương ứng với work_id hay chưa
            $decrease_insurances = DecreaseInsurance::where('work_id', $work->id)->get();
            if (0 == $decrease_insurances->count()) {
                $decrease_insurance = new DecreaseInsurance();
                $decrease_insurance->work_id = $work->id;
                $decrease_insurance->save();
            } else {
                $decrease_insurance = $decrease_insurances->first();
                $decrease_insurance->confirmed_month = null;
                $decrease_insurance->save();
            }
        }

        Alert::toast('Cập nhật thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $work->employee_id);
    }

    public function anyData()
    {
        //Display Work based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $data = Work::whereIn('position_id', $position_ids)
                        ->join('employees', 'employees.id', 'works.employee_id')
                        ->orderBy('employees.code', 'desc')
                        ->get();
        } else {
            $data = Work::join('employees', 'employees.id', 'works.employee_id')
                        ->orderBy('employees.code', 'desc')
                        ->get();
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->name . ' - ' . $data->position->division->name .  '- ' . $data->position->department->name;

                } else {
                    return $data->position->name . ' - ' . $data->position->department->name;
                }
            })
            ->editColumn('start_date', function ($data) {
                return date('d/m/Y', strtotime($data->start_date));
            })
            ->editColumn('end_date', function ($data) {
                if ($data->end_date) {
                    return date('d/m/Y', strtotime($data->end_date));
                } else {
                    return '-';
                }
            })
            ->editColumn('status', function ($data) {
                if ('On' == $data->status) {
                    return '<span class="badge badge-success">' . $data->status . '</span>';
                } else {
                    return '<span class="badge badge-danger">' . $data->status . '</span>';
                }
            })
            ->editColumn('on_type', function ($data) {
                if ($data->on_type_id) {
                    return $data->on_type->name;
                }
            })
            ->editColumn('off_type', function ($data) {
                if ($data->off_type_id) {
                    return $data->off_type->name;
                }
            })
            ->editColumn('off_reason', function ($data) {
                return $data->off_reason;
            })
            ->rawColumns(['employee_name', 'status', 'off_reason'])
            ->make(true);
    }

    public function import(ImportWorkRequest $request)
    {
        try {
            $import = new WorkImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $invalid_contract_code_row = $import->getInvalidContractCodeRow();
            $invalid_employee_name_row = $import->getInvalidEmployeeNameRow();
            $invalid_position_name_row = $import->getInvalidPositionNameRow();
            $invalid_on_type_name_row = $import->getInvalidOnTypeNameRow();
            $invalid_off_type_name_row = $import->getInvalidOffTypeNameRow();

            if ($invalid_contract_code_row) {
                Alert::toast('Không tìm thấy mã hợp đồng tại dòng thứ ' . $invalid_contract_code_row, 'error', 'top-right');
                return redirect()->back();
            }

            if ($invalid_employee_name_row) {
                Alert::toast('Không tìm thấy tên nhân viên tại dòng thứ ' . $invalid_employee_name_row, 'error', 'top-right');
                return redirect()->back();
            }

            if ($invalid_position_name_row) {
                Alert::toast('Không tìm thấy vị trí tại dòng thứ ' . $invalid_position_name_row, 'error', 'top-right');
                return redirect()->back();
            }

            if ($invalid_on_type_name_row) {
                Alert::toast('Không tìm thấy OnType tại dòng thứ ' . $invalid_on_type_name_row, 'error', 'top-right');
                return redirect()->back();
            }

            if ($invalid_off_type_name_row) {
                Alert::toast('Không tìm thấy OffType tại dòng thứ ' . $invalid_off_type_name_row, 'error', 'top-right');
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
