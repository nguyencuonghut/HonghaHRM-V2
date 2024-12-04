<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractTypeRequest;
use App\Http\Requests\UpdateContractTypeRequest;
use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class ContractTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->cannot('viewAny', ContractType::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('home');
        }

        return view('contract_type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', ContractType::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('contract_types.index');
        }
        return view('contract_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractTypeRequest $request)
    {
        $contract_type = new ContractType();
        $contract_type->name = $request->name;
        $contract_type->save();

        Alert::toast('Thêm vai trò thành công!', 'success', 'top-right');
        return redirect()->route('contract_types.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContractType $contractType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContractType $contractType)
    {
        if (Auth::user()->cannot('update', $contractType)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('contract_types.index');
        }

        return view('contract_type.edit', ['contract_type' => $contractType]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractTypeRequest $request, ContractType $contractType)
    {
        $contractType->update(['name' => $request->name]);

        Alert::toast('Sửa vai trò thành công!', 'success', 'top-right');
        return redirect()->route('contract_types.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractType $contractType)
    {

        if (Auth::user()->cannot('delete', $contractType)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('contract_types.index');
        }

        //Check if ContractType is used or not
        if ($contractType->contracts->count()) {
            Alert::toast('Loại hợp đồng đang được sử dụng. Không thể xóa!', 'error', 'top-right');
            return redirect()->route('contract_types.index');
        }
        $contractType->delete();

        Alert::toast('Xóa loại hợp đồng thành công!', 'success', 'top-rigth');
        return redirect()->route('contract_types.index');
    }

    public function anyData()
    {
        $data = ContractType::orderBy('id', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("contract_types.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("contract_types.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
