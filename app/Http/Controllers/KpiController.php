<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportKpiRequest;
use App\Http\Requests\StoreKpiRequest;
use App\Http\Requests\UpdateKpiRequest;
use App\Imports\KpiImport;
use App\Models\Kpi;
use App\Models\KpiReport;
use App\Models\Position;
use App\Models\UserDepartment;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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

        $this_month_kpi = Kpi::where('employee_id', $request->employee_id)
                            ->where('position_id', $request->position_id)
                            ->where('year', $request->year)
                            ->where('month', $request->month)
                            ->first();
        if ($this_month_kpi) {
            Alert::toast('KPI cho tháng đã có. Bạn không thể tạo thêm!', 'error', 'top-right');
            return redirect()->back();
        }

        $kpi = new Kpi();
        $kpi->employee_id = $request->employee_id;
        $kpi->position_id = $request->position_id;
        $kpi->year = $request->year;
        $kpi->month = $request->month;
        $kpi->score = $request->score;
        $kpi->save();

        //Add KPI to report
        $kpi_reports = KpiReport::where('employee_id', $request->employee_id)
                                ->where('position_id', $request->position_id)
                                ->where('year', $request->year)
                                ->get();
        if ($kpi_reports->count()) {
            //Đã tồn tại KPI của năm, chỉ cần cập nhật tháng
            $this->updateKpiReport($kpi, false);
        } else {
            //Chưa có KPI của năm, tạo mới
            $this->createKpiReport($request->employee_id, $request->position_id, $request->year, $request->month, $request->score);
        }

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
        $req_month_kpi = Kpi::where('employee_id', $kpi->employee_id)
                            ->where('position_id', $kpi->position_id)
                            ->where('year', $request->year)
                            ->where('month', $request->month)
                            ->first();
        if ($req_month_kpi
            && $kpi->month != $req_month_kpi->month) {
            Alert::toast('KPI cho tháng đã có. Bạn không thể tạo thêm!', 'error', 'top-right');
            return redirect()->back();
        }

        $kpi->position_id = $request->position_id;
        $kpi->year = $request->year;
        $kpi->month = $request->month;
        $kpi->score = $request->score;
        $kpi->save();

        //Update the KpiReport
        $this->updateKpiReport($kpi, false);

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
        $temp_kpi = $kpi;

        //Delete the record
        $kpi->delete();

        //Update the KpiReport
        $this->updateKpiReport($kpi, true);

        Alert::toast('Xóa KPI mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        //Display KPI based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Kpi::whereIn('employee_id', $employee_ids)
                        ->join('employees', 'employees.id', 'kpis.employee_id')
                        ->select('kpis.*', 'employees.code as employees_code')
                        ->orderBy('employees_code', 'desc')
                        ->get();
        } else {
            $data = Kpi::join('employees', 'employees.id', 'kpis.employee_id')
                        ->select('kpis.*', 'employees.code as employees_code')
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

    private function createKpiReport($employee_id, $position_id, $year, $month, $score)
    {
        $kpi_report = new KpiReport();
        $kpi_report->employee_id = $employee_id;
        $kpi_report->position_id = $position_id;
        $kpi_report->year = $year;
        switch ($month) {
            case '1':
                $kpi_report->jan = $score;
                break;
            case '2':
                $kpi_report->feb = $score;
                break;
            case '3':
                $kpi_report->mar = $score;
                break;
            case '4':
                $kpi_report->apr = $score;
                break;
            case '5':
                $kpi_report->may = $score;
                break;
            case '6':
                $kpi_report->jun = $score;
                break;
            case '7':
                $kpi_report->jul = $score;
                break;
            case '8':
                $kpi_report->aug = $score;
                break;
            case '9':
                $kpi_report->sep = $score;
                break;
            case '10':
                $kpi_report->oct = $score;
                break;
            case '11':
                $kpi_report->nov = $score;
                break;
            case '12':
                $kpi_report->dec = $score;
                break;
        }

        $kpi_report->year_avarage = $score;
        $kpi_report->save();
    }


    private function updateKpiReport(Kpi $kpi, bool $is_destroy)
    {
        //Find the KPI report
        $kpi_report = KpiReport::where('employee_id', $kpi->employee_id)
                                ->where('position_id', $kpi->position_id)
                                ->where('year', $kpi->year)
                                ->first();
        $total_score = $kpi_report->jan + $kpi_report->feb + $kpi_report->mar + $kpi_report->apr + $kpi_report->may + $kpi_report->jun
                        + $kpi_report->jul + $kpi_report->aug + $kpi_report->sep + $kpi_report->oct + $kpi_report->nov + $kpi_report->dec;

        switch ($kpi->month) {
            case '1':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->jan = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->jan;
                    $kpi_report->jan = $kpi->score;
                }
                break;
            case '2':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->feb = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->feb;
                    $kpi_report->feb = $kpi->score;
                }
                break;
            case '3':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->mar = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->mar;
                    $kpi_report->mar = $kpi->score;
                }
                break;
            case '4':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->apr = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->apr;
                    $kpi_report->apr = $kpi->score;
                }
                break;
            case '5':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->may = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->may;
                    $kpi_report->may = $kpi->score;
                }
                break;
            case '6':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->jun = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->jun;
                    $kpi_report->jun = $kpi->score;
                }
                break;
            case '7':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->jul = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->jul;
                    $kpi_report->jul = $kpi->score;
                }
                break;
            case '8':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->aug = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->aug;
                    $kpi_report->aug = $kpi->score;
                }
                break;
            case '9':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->sep = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->sep;
                    $kpi_report->sep = $kpi->score;
                }
                break;
            case '10':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->oct = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->oct;
                    $kpi_report->oct = $kpi->score;
                }
                break;
            case '11':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->nov = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->nov;
                    $kpi_report->nov = $kpi->score;
                }
                break;
            case '12':
                if ($is_destroy) {
                    $total_score = $total_score - $kpi->score;
                    $kpi_report->dec = null;
                } else {
                    $total_score = $total_score + $kpi->score - $kpi_report->dec;
                    $kpi_report->dec = $kpi->score;
                }
                break;
        }

        $year_total_kpi = 0;
        $year_kpis = Kpi::where('employee_id', $kpi->employee->id)
                        ->where('position_id', $kpi->position_id)
                        ->where('year', $kpi->year)
                        ->get();
        foreach ($year_kpis as $this_year_kpi) {
            $year_total_kpi += $this_year_kpi->score;
        }
        if ($year_kpis->count()) {
            $kpi_report->year_avarage = round(ceil($year_total_kpi/$year_kpis->count()*100)/100,2);
        } else {
            $kpi_report->year_avarage = 0;
        }
        $kpi_report->save();
    }

    public function import(ImportKpiRequest $request)
    {
        try {
            $import = new KpiImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $invalid_employee_name_row = $import->getInvalidEmployeeNameRow();
            $invalid_position_name_row = $import->getInvalidPositionNameRow();
            $duplicates = $import->getDuplicateCount();
            $duplicate_rows = $import->getDuplicateRows();

            if ($duplicates) {
                $duplicate_rows_list = implode(', ', $duplicate_rows);
                Alert::toast('Các dòng bị trùng lặp là '. $duplicate_rows_list);
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công! Có ' . $duplicates . ' dòng bị trùng lặp! Lặp tại dòng số: ' . $duplicate_rows_list, 'success', 'top-right');
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

            Alert::toast('Import '. $rows . ' dòng dữ liệu thành công!', 'success', 'top-right');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Có lỗi xảy ra trong quá trình import dữ liệu. Vui lòng kiểm tra lại file!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
