<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfirmIncreaseInsuranceRequest;
use App\Models\IncreaseInsurance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class IncreaseInsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('increase_insurance.index');
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
    public function show(IncreaseInsurance $increaseInsurance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncreaseInsurance $increaseInsurance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncreaseInsurance $increaseInsurance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncreaseInsurance $increaseInsurance)
    {
        //
    }

    public function anyData()
    {
        $data = IncreaseInsurance::all();
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
            $action = '<a href="' . route("increase_insurances.getConfirm", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>';
            return $action;
        })
        ->rawColumns(['name', 'actions', 'status'])
        ->make(true);
    }

    public function getConfirm($id)
    {
        $increase_insurance = IncreaseInsurance::findOrFail($id);
        return view('increase_insurance.confirm', ['increase_insurance' => $increase_insurance]);
    }

    public function confirm(ConfirmIncreaseInsuranceRequest $request, $id)
    {
        $increase_insurance = IncreaseInsurance::findOrFail($id);
        $increase_insurance->confirmed_month = Carbon::createFromFormat('m/Y', $request->confirmed_month);
        $increase_insurance->save();

        Alert::toast('Xác nhận tăng BHXH thành công!', 'success', 'top-right');
        return redirect()->route('increase_insurances.index');
    }
}
