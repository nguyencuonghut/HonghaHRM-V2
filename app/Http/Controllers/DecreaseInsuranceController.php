<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmDecreaseInsuranceRequest;
use App\Models\DecreaseInsurance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DecreaseInsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('decrease_insurance.index');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DecreaseInsurance $decreaseInsurance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DecreaseInsurance $decreaseInsurance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DecreaseInsurance $decreaseInsurance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DecreaseInsurance $decreaseInsurance)
    {
        //
    }

    public function anyData()
    {
        $data = DecreaseInsurance::all();
        return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('code', function ($data) {
            return $data->work->employee->code;
        })
        ->editColumn('name', function ($data) {
            return '<a href="' . route("employees.show", $data->work->employee->id) . '">' . $data->work->employee->name . '</a>';

        })
        ->editColumn('position', function ($data) {
            return $data->work->position->name;

        })
        ->editColumn('department', function ($data) {
            return $data->work->position->department->name;

        })
        ->editColumn('confirmed_month', function ($data) {
            if ($data->confirmed_month) {
                return date('m/Y', strtotime($data->confirmed_month));
            }
        })
        ->editColumn('status', function ($data) {
            if ($data->confirmed_month) {
                return '<span class="badge badge-success">Đã xác nhận</span>';
            } else {
                return '<span class="badge badge-danger">Chưa xác nhận</span>';
            }
        })
        ->addColumn('actions', function ($data) {
            $action = '<a href="' . route("decrease_insurances.getConfirm", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>';
            return $action;
        })
        ->rawColumns(['name', 'actions', 'status'])
        ->make(true);
    }

    public function getConfirm($id)
    {
        $decrease_insurance = DecreaseInsurance::findOrFail($id);
        return view('decrease_insurance.confirm', ['decrease_insurance' => $decrease_insurance]);
    }

    public function confirm(ConfirmDecreaseInsuranceRequest $request, $id)
    {
        $decrease_insurance = DecreaseInsurance::findOrFail($id);
        $decrease_insurance->confirmed_month = Carbon::createFromFormat('m/Y', $request->confirmed_month);
        $decrease_insurance->save();

        Alert::toast('Xác nhận giảm BHXH thành công!', 'success', 'top-right');
        return redirect()->route('decrease_insurances.index');
    }
}
