<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFamilyRequest;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FamilyController extends Controller
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
    public function store(StoreFamilyRequest $request)
    {
        if (Auth::user()->cannot('create', Family::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $family = new Family();
        $family->employee_id = $request->employee_id;
        $family->name = $request->name;
        $family->year_of_birth = $request->year_of_birth;
        $family->job = $request->job;
        $family->type = $request->type;
        $family->health = $request->health;
        $family->is_living_together = $request->is_living_together;
        if ($request->situation) {
            $family->situation = $request->situation;
        }
        $family->save();

        Alert::toast('Thêm người thân mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Family $family)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Family $family)
    {
        if (Auth::user()->cannot('update', $family)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $family->name = $request->name;
        $family->year_of_birth = $request->year_of_birth;
        $family->job = $request->job;
        $family->type = $request->type;
        $family->health = $request->health;
        $family->is_living_together = $request->is_living_together;
        if ($request->situation) {
            $family->situation = $request->situation;
        }
        $family->save();

        Alert::toast('Sửa người thân mới thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family)
    {
        if (Auth::user()->cannot('delete', $family)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
;
        $family->delete();

        Alert::toast('Xóa người thân thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
