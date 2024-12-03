<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OffWorkReportController extends Controller
{
    public function byRange(Request $request)
    {
        if ($request->ajax()) {
            $data = Work::whereIn('off_type_id', [1, 6])//1: Nghỉ việc, 6: Nghỉ hưu
                        ->join('employees', 'employees.id', 'works.employee_id')
                        ->select('works.*', 'employees.code as employees_code')
                        ->orderBy('employees_code', 'desc')
                        ->get();
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('end_date', [$request->from_date, $request->to_date]);
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('employee_code', function ($data) {
                    return $data->employees_code;
                })
                ->editColumn('employee_name', function ($data) {
                    return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
                })
                ->editColumn('position', function ($data) {
                    return $data->position->name;
                })
                ->editColumn('division', function ($data) {
                    if ($data->position->division_id) {
                        return $data->position->division->name;
                    } else {
                        return '-';
                    }
                })
                ->editColumn('department', function ($data) {
                    return $data->position->department->name;
                })
                ->editColumn('probation_contract_date', function ($data) {
                    $formal_contract = Contract::where('code', $data->contract_code)
                                        ->where('contract_type_id', 2)//2: HĐ chính thức
                                        ->orderBy('start_date', 'desc')
                                        ->first();
                    if ($formal_contract) {
                        //Tìm hđ thử việc
                        //Tìm hđ trước đó
                        $previous_contract = Contract::where('end_date', $formal_contract->start_date)
                                                    ->where('employee_id', $formal_contract->employee_id)
                                                    ->first();
                        if ($previous_contract) {
                            if ('Hợp đồng thử việc' == $previous_contract->contract_type->name) {
                                return date('d/m/Y', strtotime($previous_contract->start_date));
                            } else {
                                //Tiếp tục tìm
                                $pre_previous_contract = Contract::where('end_date', $previous_contract->start_date)
                                                                ->where('employee_id', $formal_contract->employee_id)
                                                                ->first();
                                if ($pre_previous_contract) {
                                    if ('Hợp đồng thử việc' == $pre_previous_contract->contract_type->name) {
                                        return date('d/m/Y', strtotime($pre_previous_contract->start_date));
                                    } else {
                                        return '-';
                                    }
                                } else {
                                    return '-';
                                }
                            }
                        } else {
                            return '-';
                        }
                    } else {//Chưa có hđ chính thức
                        //Lấy hđ thử việc gần nhất
                        $probation_contract = Contract::where('employee_id', $data->employee_id)
                                                    ->where('position_id', $data->position_id)
                                                    ->orderBy('start_date', 'desc')
                                                    ->first();
                        if ($probation_contract) {
                            return date('d/m/Y', strtotime($probation_contract->start_date));
                        } else {
                            return '-';
                        }
                    }
                })
                ->editColumn('formal_contract_date', function ($data) {
                    $contract = Contract::where('code', $data->contract_code)
                                        ->where('contract_type_id', 2)//2: HĐ chính thức
                                        ->first();
                    if ($contract) {
                        return date('d/m/Y', strtotime($contract->start_date));
                    } else {
                        return '-';
                    }
                })
                ->editColumn('end_date', function ($data) {
                    return date('d/m/Y', strtotime($data->end_date));
                })
                ->editColumn('off_reason', function ($data) {
                    return $data->off_reason;
                })
                ->rawColumns(['employee_name'])
                ->make(true);
        }

        return view('report.off_work.by_range');
    }

    public function index()
    {
        return view('report.off_work.index');
    }

    public function show()
    {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        return view('report.off_work.show', [
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function byMonth(Request $request)
    {
        $filter_month_year = explode('/', $request->month_of_year);
        $month = $filter_month_year[0];
        $year   = $filter_month_year[1];
        return view('report.off_work.by_month',
                    [
                        'month' => $month,
                        'year' => $year,
                    ]);
    }

    public function anyData()
    {
        $data = Work::whereIn('off_type_id', [1, 6])//1: Nghỉ việc, 6: Nghỉ hưu
                    ->join('employees', 'employees.id', 'works.employee_id')
                    ->select('works.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_code', function ($data) {
                return $data->employees_code;
            })
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                return $data->position->name;
            })
            ->editColumn('division', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->division->name;
                } else {
                    return '-';
                }
            })
            ->editColumn('department', function ($data) {
                return $data->position->department->name;
            })
            ->editColumn('probation_contract_date', function ($data) {
                $formal_contract = Contract::where('code', $data->contract_code)
                                    ->where('contract_type_id', 2)//2: HĐ chính thức
                                    ->orderBy('start_date', 'desc')
                                    ->first();
                if ($formal_contract) {
                    //Tìm hđ thử việc
                    //Tìm hđ trước đó
                    $previous_contract = Contract::where('end_date', $formal_contract->start_date)
                                                ->where('employee_id', $formal_contract->employee_id)
                                                ->first();
                    if ($previous_contract) {
                        if ('Hợp đồng thử việc' == $previous_contract->contract_type->name) {
                            return date('d/m/Y', strtotime($previous_contract->start_date));
                        } else {
                            //Tiếp tục tìm
                            $pre_previous_contract = Contract::where('end_date', $previous_contract->start_date)
                                                            ->where('employee_id', $formal_contract->employee_id)
                                                            ->first();
                            if ($pre_previous_contract) {
                                if ('Hợp đồng thử việc' == $pre_previous_contract->contract_type->name) {
                                    return date('d/m/Y', strtotime($pre_previous_contract->start_date));
                                } else {
                                    return '-';
                                }
                            } else {
                                return '-';
                            }
                        }
                    } else {
                        return '-';
                    }
                } else {//Chưa có hđ chính thức
                    //Lấy hđ thử việc gần nhất
                    $probation_contract = Contract::where('employee_id', $data->employee_id)
                                                ->where('position_id', $data->position_id)
                                                ->orderBy('start_date', 'desc')
                                                ->first();
                    if ($probation_contract) {
                        return date('d/m/Y', strtotime($probation_contract->start_date));
                    } else {
                        return '-';
                    }
                }
            })
            ->editColumn('formal_contract_date', function ($data) {
                $contract = Contract::where('code', $data->contract_code)
                                    ->where('contract_type_id', 2)//2: HĐ chính thức
                                    ->first();
                if ($contract) {
                    return date('d/m/Y', strtotime($contract->start_date));
                } else {
                    return '-';
                }
            })
            ->editColumn('end_date', function ($data) {
                return date('d/m/Y', strtotime($data->end_date));
            })
            ->editColumn('off_reason', function ($data) {
                return $data->off_reason;
            })
            ->rawColumns(['employee_name'])
            ->make(true);
    }

    public function byMonthData($month, $year)
    {
        $data = Work::whereIn('off_type_id', [1, 6])//1: Nghỉ việc, 6: Nghỉ hưu
                    ->whereMonth('end_date', $month)
                    ->whereYear('end_date', $year)
                    ->join('employees', 'employees.id', 'works.employee_id')
                    ->select('works.*', 'employees.code as employees_code')
                    ->orderBy('employees_code', 'desc')
                    ->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('employee_code', function ($data) {
            return $data->employees_code;
        })
        ->editColumn('employee_name', function ($data) {
            return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
        })
        ->editColumn('position', function ($data) {
            return $data->position->name;
        })
        ->editColumn('division', function ($data) {
            if ($data->position->division_id) {
                return $data->position->division->name;
            } else {
                return '-';
            }
        })
        ->editColumn('department', function ($data) {
            return $data->position->department->name;
        })
        ->editColumn('probation_contract_date', function ($data) {
            $formal_contract = Contract::where('code', $data->contract_code)
                                ->where('contract_type_id', 2)//2: HĐ chính thức
                                ->orderBy('start_date', 'desc')
                                ->first();
            if ($formal_contract) {
                //Tìm hđ thử việc
                //Tìm hđ trước đó
                $previous_contract = Contract::where('end_date', $formal_contract->start_date)
                                            ->where('employee_id', $formal_contract->employee_id)
                                            ->first();
                if ($previous_contract) {
                    if ('Hợp đồng thử việc' == $previous_contract->contract_type->name) {
                        return date('d/m/Y', strtotime($previous_contract->start_date));
                    } else {
                        //Tiếp tục tìm
                        $pre_previous_contract = Contract::where('end_date', $previous_contract->start_date)
                                                        ->where('employee_id', $formal_contract->employee_id)
                                                        ->first();
                        if ($pre_previous_contract) {
                            if ('Hợp đồng thử việc' == $pre_previous_contract->contract_type->name) {
                                return date('d/m/Y', strtotime($pre_previous_contract->start_date));
                            } else {
                                return '-';
                            }
                        } else {
                            return '-';
                        }
                    }
                } else {
                    return '-';
                }
            } else {//Chưa có hđ chính thức
                //Lấy hđ thử việc gần nhất
                $probation_contract = Contract::where('employee_id', $data->employee_id)
                                            ->where('position_id', $data->position_id)
                                            ->orderBy('start_date', 'desc')
                                            ->first();
                if ($probation_contract) {
                    return date('d/m/Y', strtotime($probation_contract->start_date));
                } else {
                    return '-';
                }
            }
        })
        ->editColumn('formal_contract_date', function ($data) {
            $contract = Contract::where('code', $data->contract_code)
                                ->where('contract_type_id', 2)//2: HĐ chính thức
                                ->first();
            if ($contract) {
                return date('d/m/Y', strtotime($contract->start_date));
            } else {
                return '-';
            }
        })
        ->editColumn('end_date', function ($data) {
            return date('d/m/Y', strtotime($data->end_date));
        })
        ->editColumn('off_reason', function ($data) {
            return $data->off_reason;
        })
        ->rawColumns(['employee_name'])
        ->make(true);
    }
}
