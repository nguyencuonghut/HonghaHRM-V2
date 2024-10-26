<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApproveOfferRequest;
use App\Http\Requests\StoreOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Models\Offer;
use App\Models\Recruitment;
use App\Models\RecruitmentCandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class OfferController extends Controller
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
    public function store(StoreOfferRequest $request)
    {
        if (Auth::user()->cannot('create', Offer::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $offer = new Offer();
        $offer->recruitment_candidate_id = $request->recruitment_candidate_id;
        $offer->current_salary = $request->current_salary;
        $offer->desired_salary = $request->desired_salary;
        $offer->insurance_salary = $request->insurance_salary;
        $offer->position_salary = $request->position_salary;
        $offer->capacity_salary = $request->capacity_salary;
        $offer->position_allowance = $request->position_allowance;
        $offer->feedback = $request->feedback;
        if ($request->offer_note) {
            $offer->note = $request->offer_note;
        }
        $offer->creator_id = Auth::user()->id;
        $offer->save();

        Alert::toast('Nhập dữ liệu thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $offer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, Offer $offer)
    {
        if (Auth::user()->cannot('update', $offer)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Check condition before update
        if ($offer->result) {
            Alert::toast('Offer đã được duyệt, bạn không có quyền sửa!', 'error', 'top-right');
            return redirect()->back();
        }

        $offer->recruitment_candidate_id = $request->recruitment_candidate_id;
        $offer->current_salary = $request->current_salary;
        $offer->desired_salary = $request->desired_salary;
        $offer->insurance_salary = $request->insurance_salary;
        $offer->position_salary = $request->position_salary;
        $offer->capacity_salary = $request->capacity_salary;
        $offer->position_allowance = $request->position_allowance;
        $offer->feedback = $request->feedback;
        if ($request->offer_note) {
            $offer->note = $request->offer_note;
        }
        $offer->creator_id = Auth::user()->id;
        $offer->save();

        Alert::toast('Sửa đề xuất thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        if (Auth::user()->cannot('delete', $offer)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Check condition before destroy
        if ($offer->result) {
            Alert::toast('Offer đã được duyệt, bạn không có quyền xóa!', 'error', 'top-right');
            return redirect()->back();
        }

        $offer->delete();
        Alert::toast('Xóa đề xuất thành công!', 'success', 'top-right');
        return redirect()->back();
    }


    public function approve(ApproveOfferRequest $request, Offer $offer)
    {
        if (Auth::user()->cannot('approve', $offer)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $offer->result = $request->result;
        $offer->approver_id = Auth::user()->id;
        $offer->save();

        Alert::toast('Duyệt đề xuất thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
