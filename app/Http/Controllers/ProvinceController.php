<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProvinceRequest;
use App\Http\Requests\UpdateProvinceRequest;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('province.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Province::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('provinces.index');
        }
        return view('province.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProvinceRequest $request)
    {
        $province = new Province();
        $province->name = $request->name;
        $province->save();

        Alert::toast('Thêm tỉnh thành công!', 'success', 'top-right');
        return redirect()->route('provinces.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        if (Auth::user()->cannot('update', $province)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('provinces.index');
        }

        return view('province.edit', ['province' => $province]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProvinceRequest $request, Province $province)
    {
        $province->update(['name' => $request->name]);

        Alert::toast('Sửa tỉnh thành công!', 'success', 'top-right');
        return redirect()->route('provinces.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        if (Auth::user()->cannot('delete', $province)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('provinces.index');
        }

        //Check if Province is used or not
        if ($province->districts->count()) {
            Alert::toast('Tỉnh đang được sử dụng. Không thể xóa!', 'error', 'top-rigth');
            return redirect()->route('provinces.index');
        }
        $province->delete();

        Alert::toast('Xóa tỉnh thành công!', 'success', 'top-rigth');
        return redirect()->route('provinces.index');
    }

    public function anyData()
    {
        $data = Province::orderBy('name', 'asc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("provinces.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("provinces.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
