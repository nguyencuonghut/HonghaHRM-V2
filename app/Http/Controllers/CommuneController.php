<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommuneRequest;
use App\Http\Requests\UpdateCommuneRequest;
use App\Models\Commune;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class CommuneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('commune.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Commune::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('communes.index');
        }
        $districts = District::orderBy('name', 'asc')->get();
        return view('commune.create', ['districts' => $districts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommuneRequest $request)
    {
        $commune = new Commune();
        $commune->name = $request->name;
        $commune->district_id = $request->district_id;
        $commune->save();

        Alert::toast('Thêm phường xã thành công!', 'success', 'top-right');
        return redirect()->route('communes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commune $commune)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commune $commune)
    {
        if (Auth::user()->cannot('update', $commune)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('districts.index');
        }

        $districts = District::orderBy('name', 'asc')->get();
        return view('commune.edit',
                    [
                        'commune' => $commune,
                        'districts' => $districts,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommuneRequest $request, Commune $commune)
    {
        $commune->update([
            'name' => $request->name,
            'district_id' => $request->district_id,
        ]);

        Alert::toast('Sửa phường xã thành công!', 'success', 'top-right');
        return redirect()->route('communes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commune $commune)
    {
        if (Auth::user()->cannot('delete', $commune)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('communes.index');
        }

        //TODO: Check if Commune is used or not

        $commune->delete();

        Alert::toast('Xóa phường xã thành công!', 'success', 'top-rigth');
        return redirect()->route('communes.index');
    }

    public function anyData()
    {
        $data = Commune::with('district')->orderBy('district_id', 'asc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('district', function($row) {
                return $row->district->name;
            })
            ->addColumn('province', function($row) {
                return $row->district->province->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("communes.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("communes.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
