<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\RecruitmentRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class RecruitmentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('recruitment_request.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = Position::orderBy('name', 'asc')->get();

        return view('recruitment_request.create', ['positions' => $positions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'position_id' => 'required',
            'quantity' => 'required',
            'reason' => 'required',
            'requirement' => 'required',
            'work_time' => 'required',
        ];
        $messages = [
            'position_id.required' => 'Bạn phải chọn vị trí.',
            'quantity.required' => 'Bạn phải nhập số lượng.',
            'reason.required' => 'Bạn phải nhập lý do.',
            'requirement.required' => 'Bạn phải nhập yêu cầu.',
            'work_time.required' => 'Bạn phải nhập thời gian.',
        ];
        $request->validate($rules,$messages);

        //Create new RecruitmentRequest
        $proposal = new RecruitmentRequest();
        $proposal->position_id  = $request->position_id;
        $proposal->quantity     = $request->quantity;
        $proposal->reason       = $request->reason;
        $proposal->requirement  = $request->requirement;
        if ($request->salary) {
            $proposal->salary   = $request->salary;
        }
        $proposal->work_time    = Carbon::createFromFormat('d/m/Y', $request->work_time);
        if ($request->note) {
            $proposal->note = $request->note;
        }
        $proposal->creator_id   = Auth::user()->id;
        // Trường hợp người tạo là Ban lãnh đạo, không cần review và phê duyệt nữa
        if ('Ban lãnh đạo' == Auth::user()->role->name) {
            $proposal->reviewer_id      = Auth::user()->id;
            $proposal->reviewer_result  = 'Đồng ý';
            $proposal->approver_id      = Auth::user()->id;
            $proposal->approver_result  = 'Đồng ý';
            $proposal->status = 'Đã duyệt';

        } else {
            $proposal->status = 'Mở';
        }
        $proposal->save();

        Alert::toast('Thêm yêu cầu tuyển dụng mới thành công!', 'success', 'top-right');
        return redirect()->route('recruitment_requests.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(RecruitmentRequest $recruitmentRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecruitmentRequest $recruitmentRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecruitmentRequest $recruitmentRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecruitmentRequest $recruitmentRequest)
    {
        //
    }

    public function anyData()
    {
        $data = RecruitmentRequest::with(['position', 'creator', 'reviewer', 'approver'])->orderBy('id', 'desc')->get();

        return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('position', function ($data) {
            return '<a href="'.route('positions.show', $data->id).'">'.$data->position->name.'</a>';
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
            $action = '<a href="' . route("recruitment_requests.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
            <form style="display:inline" action="'. route("recruitment_requests.destroy", $row->id) . '" method="POST">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
            return $action;
        })
        ->rawColumns(['actions', 'position', 'status'])
        ->make(true);
    }
}
