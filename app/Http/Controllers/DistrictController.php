<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->cannot('viewAny', District::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('home');
        }

        return view('district.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', District::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('districts.index');
        }
        $provinces = Province::orderBy('name', 'asc')->get();
        return view('district.create', ['provinces' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistrictRequest $request)
    {
        $district = new District();
        $district->name = $request->name;
        $district->province_id = $request->province_id;
        $district->save();

        Alert::toast('Thêm quận huyện thành công!', 'success', 'top-right');
        return redirect()->route('districts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(District $district)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(District $district)
    {
        if (Auth::user()->cannot('update', $district)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('districts.index');
        }

        $provinces = Province::orderBy('name', 'asc')->get();
        return view('district.edit',
                    [
                        'district' => $district,
                        'provinces' => $provinces,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistrictRequest $request, District $district)
    {
        $district->update([
            'name' => $request->name,
            'province_id' => $request->province_id,
        ]);

        Alert::toast('Sửa quận huyện thành công!', 'success', 'top-right');
        return redirect()->route('districts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district)
    {
        if (Auth::user()->cannot('delete', $district)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('districts.index');
        }

        //Check if District is used or not
        if ($district->communes->count()) {
            Alert::toast('Quận huyện đang được sử dụng. Không thể xóa!', 'error', 'top-right');
            return redirect()->route('districts.index');
        }

        $district->delete();

        Alert::toast('Xóa quận huyện thành công!', 'success', 'top-rigth');
        return redirect()->route('districts.index');
    }

    public function anyData()
    {
        $data = District::with('province')->orderBy('province_id', 'asc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('province', function($row) {
                return $row->province->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("districts.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("districts.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
