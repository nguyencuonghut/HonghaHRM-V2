<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveProbationRequest;
use App\Http\Requests\EvaluateProbationRequest;
use App\Http\Requests\ReviewProbationRequest;
use App\Http\Requests\StoreProbationRequest;
use App\Http\Requests\UpdateProbationRequest;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Probation;
use App\Models\RecruitmentCandidate;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ProbationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('probation.index');
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
    public function store(StoreProbationRequest $request)
    {
        if (Auth::user()->cannot('create', Probation::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $probation = new Probation();
        $probation->employee_id = $request->employee_id;
        $probation->recruitment_id = $request->recruitment_id;
        $probation->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
        $probation->end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);
        $probation->creator_id = Auth::user()->id;
        $probation->save();

        Alert::toast('Thêm kế hoạch thử việc thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Probation $probation)
    {
        return view('probation.show', ['probation' => $probation]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Probation $probation)
    {
        if (Auth::user()->cannot('update', $probation)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('probations.index');
        }

        // Check condition before editing
        if ($probation->approver_result) {
            Alert::toast('Thử việc đã được duyệt. Không thể sửa!', 'error', 'top-right');
            return redirect()->route('probations.index');
        }
        return view('probation.edit', ['probation' => $probation]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProbationRequest $request, Probation $probation)
    {
        // Check condition before updating
        if ($probation->approver_result) {
            Alert::toast('Thử việc đã được duyệt. Không thể sửa!', 'error', 'top-right');
            return redirect()->route('probations.index');
        }
        $probation->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
        $probation->end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);
        $probation->creator_id = Auth::user()->id;
        $probation->save();

        Alert::toast('Sửa kế hoạch thử việc thành công!', 'success', 'top-right');
        return redirect()->route('probations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Probation $probation)
    {
        if (Auth::user()->cannot('delete', $probation)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Check condition before destroying
        if ($probation->approver_result) {
            Alert::toast('Thử việc đã được duyệt. Không thể xóa!', 'error', 'top-right');
            return redirect()->back();
        }

        // Delete all ProbationPlans
        foreach ($probation->probation_plans as $probation_plan) {
            $probation_plan->delete();
        }

        // Delete probation
        $probation->delete();

        Alert::toast('Xóa kế hoạch thử việc thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function evaluate(EvaluateProbationRequest $request, Probation $probation)
    {
        if (Auth::user()->cannot('evaluate', $probation)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $probation->result_of_work = $request->result_of_work;
        $probation->result_of_attitude = $request->result_of_attitude;
        $probation->result_manager_status = $request->result_manager_status;
        $probation->save();

        Alert::toast('Đánh giá kết quả thử việc thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function review(ReviewProbationRequest $request, Probation $probation)
    {
        if (Auth::user()->cannot('review', $probation)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $probation->result_reviewer_status = $request->result_reviewer_status;
        $probation->result_review_time = Carbon::now();
        $probation->result_reviewer_id = Auth::user()->id;
        $probation->save();

        Alert::toast('Kiểm tra kết quả thử việc thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function approve(ApproveProbationRequest $request, Probation $probation)
    {
        if (Auth::user()->cannot('approve', $probation)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $probation->approver_result = $request->approver_result;
        if ($request->approver_comment) {
            $probation->approver_comment = $request->approver_comment;
        }
        $probation->approver_time = Carbon::now();
        $probation->approver_id = Auth::user()->id;
        $probation->save();

        Alert::toast('Duyệt kết quả thử việc thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        //Display Probation based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Probation::whereIn('employee_id', $employee_ids)
                            ->select('probations.*')
                            ->join('employees', 'employees.id', 'probations.employee_id')
                            ->orderBy('employees.code', 'desc')
                            ->get();
        } else {
            $data = Probation::select('probations.*')
                            ->join('employees', 'employees.id', 'probations.employee_id')
                            ->orderBy('employees.code', 'desc')
                            ->get();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_name', function ($data) {
                return '<a href="' . route("employees.show", $data->employee_id) . '">' . $data->employee->name . '</a>';

            })
            ->editColumn('position', function ($data) {
                return $data->recruitment->position->name;
            })
            ->editColumn('time', function ($data) {
                $time = '';
                $time = $time . date('d/m/Y', strtotime($data->start_date)) . ' - ' . date('d/m/Y', strtotime($data->end_date));

                return '<a href="'.route('probations.show', $data->id).'">'.$time.'</a>';
            })
            ->editColumn('creator', function ($data) {
                if ($data->result_manager_status) {
                    if ('Đạt' == $data->result_manager_status) {
                        return $data->creator->name . ' - ' . '<span class="badge badge-success">' . $data->result_manager_status . '</span>';
                    } else {
                        return $data->creator->name . ' - ' . '<span class="badge badge-danger">' . $data->result_manager_status . '</span>';
                    }
                } else {
                    return $data->creator->name;
                }
            })
            ->editColumn('approver', function ($data) {
                if ($data->approver_id) {
                    if ('Đồng ý' == $data->approver_result) {
                        return $data->approver->name . ' - ' . '<span class="badge badge-success">' . $data->approver_result . '</span>';
                    } else {
                        return $data->approver->name . ' - ' . '<span class="badge badge-danger">' . $data->approver_result . '</span>';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("probations.show", $data->id) . '" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                           <a href="' . route("probations.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("probations.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'employee_name', 'creator', 'approver', 'time'])
            ->make(true);
    }
}
