<?php

namespace App\Http\Controllers;

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
        $filter->result = $request->result;
        $filter->note = $request->filter_note;
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
        $filter->result = $request->result;
        $filter->note = $request->filter_note;
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

        $filter->delete();

        Alert::toast('Xóa kết quả lọc thành công!', 'success', 'top-rigth');
        return redirect()->back();
    }
}
