<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveRecruitmentRequest;
use App\Http\Requests\ReviewRecruitmentRequest;
use App\Http\Requests\StoreRecruitmentRequest;
use App\Http\Requests\UpdateRecruitmentRequest;
use App\Models\Candidate;
use App\Models\Channel;
use App\Models\Method;
use App\Models\Position;
use App\Models\Recruitment;
use App\Models\User;
use App\Models\UserDepartment;
use App\Notifications\RecruitmentApproved;
use App\Notifications\RecruitmentCreated;
use App\Notifications\RecruitmentReviewerRejected;
use App\Notifications\RecruitmentToApprover;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('recruitment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Recruitment::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('recruitments.index');
        }
        //Fetch Position by User's department
        if ('Admin' == Auth::user()->role->name
        || 'Ban lãnh đạo' == Auth::user()->role->name
        || 'Nhân sự' == Auth::user()->role->name) {
            // Fetch all Position
            $positions = Position::orderBy('name', 'asc')->get();
        } else {
            // Only fetch the User's department
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)
                                            ->pluck('department_id')
                                            ->toArray();
            $positions = Position::whereIn('department_id', $department_ids)
                                ->orderBy('name', 'asc')
                                ->get();
        }


        return view('recruitment.create', ['positions' => $positions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecruitmentRequest $request)
    {
        //Create new Recruitment
        $recruitment = new Recruitment();
        $recruitment->position_id  = $request->position_id;
        $recruitment->quantity     = $request->quantity;
        $recruitment->reason       = $request->reason;
        $recruitment->requirement  = $request->requirement;
        if ($request->salary) {
            $recruitment->salary   = $request->salary;
        }
        $recruitment->work_time    = Carbon::createFromFormat('d/m/Y', $request->work_time);
        if ($request->note) {
            $recruitment->note = $request->note;
        }
        $recruitment->creator_id   = Auth::user()->id;
        // Trường hợp người tạo là Ban lãnh đạo, không cần review và phê duyệt nữa
        if ('Ban lãnh đạo' == Auth::user()->role->name) {
            $recruitment->reviewer_id      = Auth::user()->id;
            $recruitment->reviewer_result  = 'Đồng ý';
            $recruitment->reviewed_time  = Carbon::now();
            $recruitment->approver_id      = Auth::user()->id;
            $recruitment->approved_time  = Carbon::now();
            $recruitment->approver_result  = 'Đồng ý';
            $recruitment->status = 'Đã duyệt';

        } else {
            $recruitment->status = 'Mở';
        }
        $recruitment->save();

        //Send notification to reviewer
        $reviewers = User::where('status', 'Mở')->where('role_id', 4)->get(); //4: Nhân sự
        foreach ($reviewers as $reviewer) {
            Notification::route('mail' , $reviewer->email)->notify(new RecruitmentCreated($recruitment->id));
        }

        Alert::toast('Thêm yêu cầu tuyển dụng mới thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Recruitment $recruitment)
    {
        //Check authorization
        if (Auth::user()->cannot('view', $recruitment)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('recruitments.index');
        }

        $positions = Position::orderBy('name', 'asc')->get();
        $methods = Method::orderBy('name', 'asc')->get();
        $channels = Channel::orderBy('name', 'asc')->get();
        $candidates = Candidate::orderBy('id', 'desc')->get();
        return view('recruitment.show',
                    ['recruitment' => $recruitment,
                     'positions' => $positions,
                     'methods' => $methods,
                     'channels' => $channels,
                     'candidates' => $candidates,
                    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recruitment $recruitment)
    {
        //Check authorization
        if (Auth::user()->cannot('update', $recruitment)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('recruitments.index');
        }

        $positions = Position::orderBy('name', 'asc')->get();

        return view('recruitment.edit',
                    [
                        'recruitment' => $recruitment,
                        'positions' => $positions,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecruitmentRequest $request, Recruitment $recruitment)
    {
        //Create new Recruitment
        $recruitment->position_id  = $request->position_id;
        $recruitment->quantity     = $request->quantity;
        $recruitment->reason       = $request->reason;
        $recruitment->requirement  = $request->requirement;
        $recruitment->salary   = $request->salary;
        $recruitment->work_time    = Carbon::createFromFormat('d/m/Y', $request->work_time);
        $recruitment->note = $request->note;
        $recruitment->creator_id   = Auth::user()->id;
        // Trường hợp người sửa là Ban lãnh đạo, không cần review và phê duyệt nữa
        if ('Ban lãnh đạo' == Auth::user()->role->name) {
            $recruitment->reviewer_id      = Auth::user()->id;
            $recruitment->reviewer_result  = 'Đồng ý';
            $recruitment->approver_id      = Auth::user()->id;
            $recruitment->approver_result  = 'Đồng ý';
            $recruitment->status = 'Đã duyệt';

        } else {
            $recruitment->status = 'Mở';
        }
        $recruitment->save();

        //Send notification to reviewer
        $reviewers = User::where('status', 'Mở')->where('role_id', 4)->get(); //4: Nhân sự
        foreach ($reviewers as $reviewer) {
            Notification::route('mail' , $reviewer->email)->notify(new RecruitmentCreated($recruitment->id));
        }

        Alert::toast('Sửa yêu cầu tuyển dụng mới thành công!', 'success', 'top-right');
        return redirect()->route('recruitments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recruitment $recruitment)
    {
        //Check authorization
        if (Auth::user()->cannot('update', $recruitment)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('recruitments.index');
        }

        if ('Mở' != $recruitment->status) {
            Alert::toast('Tuyển dụng đang chạy. Không thể xóa!', 'error', 'top-right');
            return redirect()->route('recruitments.index');
        }

        $recruitment->delete();
        Alert::toast('Xóa tuyển dụng thành công!', 'success', 'top-rigth');
        return redirect()->route('recruitments.index');
    }

    public function anyData()
    {
        $data = Recruitment::with(['position', 'creator', 'reviewer', 'approver'])->orderBy('id', 'desc')->get();

        if ('Admin' == Auth::user()->role->name
        || 'Ban lãnh đạo' == Auth::user()->role->name
        || 'Nhân sự' == Auth::user()->role->name) {
        $data = Recruitment::with(['position', 'creator', 'reviewer', 'approver'])->orderBy('id', 'desc')->get();

    } else {
        // Only fetch the Recruitment according to User's department
        $department_ids = [];
        $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
        $positions_ids = [];
        $positions_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
        $data = Recruitment::with(['position', 'creator', 'reviewer', 'approver'])
                                            ->whereIn('position_id', $positions_ids)
                                            ->orderBy('id', 'desc')
                                            ->get();
    }

        return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('position', function ($data) {
            return '<a href="'.route('recruitments.show', $data->id).'">'.$data->position->name.'</a>';
        })
        ->addColumn('quantity', function($row) {
            return $row->quantity;
        })
        ->addColumn('reason', function($row) {
            return $row->reason;
        })
        ->editColumn('work_time', function ($row) {
            return date('d/m/Y', strtotime($row->work_time));
        })
        ->editColumn('creator', function ($row) {
            return $row->creator->name;
        })
        ->editColumn('status', function ($row) {
            $status = '';
            switch ($row->status) {
                case 'Mở':
                    $status = '<span class="badge badge-primary">' . $row->status . '</span>';
                    break;
                case 'Đã kiểm tra':
                case 'Đã duyệt':
                    $status = '<span class="badge badge-warning">' . $row->status . '</span>';
                    break;
                case 'Hủy':
                    $status = '<span class="badge badge-secondary">' . $row->status . '</span>';
                    break;
                case 'Đóng':
                    $status = '<span class="badge badge-success">' . $row->status . '</span>';
                    break;
            }
            return $status;
        })
        ->addColumn('actions', function($row){
            $action = '<a href="' . route("recruitments.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
            <form style="display:inline" action="'. route("recruitments.destroy", $row->id) . '" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
            return $action;
        })
        ->rawColumns(['actions', 'position', 'status'])
        ->make(true);
    }

    public function review(ReviewRecruitmentRequest $request, Recruitment $recruitment)
    {
        $recruitment->update([
            'reviewer_result' => $request->reviewer_result,
            'reviewer_comment' => $request->reviewer_comment,
            'reviewer_id' => Auth::user()->id,
            'reviewed_time' => Carbon::now(),
            'status' => 'Đã kiểm tra',
        ]);

        //Send notification
        if ('Đồng ý' == $recruitment->reviewer_result) {
            // Send notification to request approve
            $leaders = User::where('role_id', 2)->get(); //2: Ban lãnh đạo
            foreach ($leaders as $leader) {
                Notification::route('mail' , $leader->email)->notify(new RecruitmentToApprover($recruitment->id));
            }
        } else {
            // Send notification for reject status to the creator
            Notification::route('mail' , $recruitment->creator->email)->notify(new RecruitmentReviewerRejected($recruitment->id));
        }

        Alert::toast('Kiểm tra thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function approve(ApproveRecruitmentRequest $request, Recruitment $recruitment)
    {
        $recruitment->approver_result = $request->approver_result;
        $recruitment->approver_comment = $request->approver_comment;
        $recruitment->approver_id = Auth::user()->id;
        $recruitment->approved_time = Carbon::now();
        $recruitment->status = 'Đã duyệt';
        $recruitment->save();

        //Send notification to creator and reviewer
        Notification::route('mail' , $recruitment->creator->email)->notify(new RecruitmentApproved($recruitment->id));
        Notification::route('mail' , $recruitment->reviewer->email)->notify(new RecruitmentApproved($recruitment->id));

        Alert::toast('Phê duyệt thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
