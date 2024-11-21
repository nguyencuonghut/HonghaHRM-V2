<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveFilterRequest;
use App\Http\Requests\StoreFilterRequest;
use App\Http\Requests\UpdateFilterRequest;
use App\Models\Filter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FilterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreFilterRequest $request)
    {
        if (Auth::user()->cannot('create', Filter::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $filter = new Filter();
        $filter->recruitment_candidate_id = $request->recruitment_candidate_id;
        $filter->work_location = $request->work_location;
        $filter->salary = $request->salary;
        $filter->reviewer_id = Auth::user()->id;
        $filter->reviewer_result = $request->reviewer_result;
        $filter->reviewer_comment = $request->reviewer_comment;
        $filter->save();

        Alert::toast('Lọc ứng viên thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Filter $filter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filter $filter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFilterRequest $request, Filter $filter)
    {
        if (Auth::user()->cannot('update', $filter)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $filter->recruitment_candidate_id = $request->recruitment_candidate_id;
        $filter->work_location = $request->work_location;
        $filter->salary = $request->salary;
        $filter->reviewer_id = Auth::user()->id;
        $filter->reviewer_result = $request->reviewer_result;
        $filter->reviewer_comment = $request->reviewer_comment;
        $filter->save();

        Alert::toast('Lọc ứng viên thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filter $filter)
    {
        if (Auth::user()->cannot('delete', $filter)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        if ($filter->approver_result) {
            Alert::toast('Đã có kết quả duyệt. Bạn không thể xóa!', 'error', 'top-right');
            return redirect()->back();
        }

        $filter->delete();

        Alert::toast('Xóa kết quả lọc thành công!', 'success', 'top-rigth');
        return redirect()->back();
    }

    public function approve(ApproveFilterRequest $request, Filter $filter)
    {
        if (Auth::user()->cannot('approve', $filter)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $filter->approver_id = Auth::user()->id;
        $filter->approver_result = $request->approver_result;
        $filter->approver_comment = $request->approver_comment;
        $filter->save();

        Alert::toast('Duyệt kết quả lọc thành công!', 'success', 'top-rigth');
        return redirect()->back();
    }
}
