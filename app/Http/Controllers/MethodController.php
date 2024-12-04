<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMethodRequest;
use App\Models\Method;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class MethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->cannot('viewAny', Method::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('home');
        }

        return view('method.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Method::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('methods.index');
        }

        return view('method.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMethodRequest $request)
    {
        $method = new Method();
        $method->name = $request->name;
        $method->save();

        Alert::toast('Thêm cách thức thành công!', 'success', 'top-right');
        return redirect()->route('methods.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Method $method)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Method $method)
    {
        if (Auth::user()->cannot('update', $method)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('methods.index');
        }

        return view('method.edit', ['method' => $method]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Method $method)
    {
        $method->update(['name' => $request->name]);

        Alert::toast('Sửa cách thức thành công!', 'success', 'top-right');
        return redirect()->route('methods.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Method $method)
    {
        if (Auth::user()->cannot('delete', $method)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('methods.index');
        }

        //Check if Method is used or not
        if ($method->plans->count()) {
            Alert::toast('Cách thức đang được sử dụng. Không thể xóa!', 'error', 'top-right');
            return redirect()->route('methods.index');
        }
        $method->delete();

        Alert::toast('Xóa cách thức thành công!', 'success', 'top-rigth');
        return redirect()->route('methods.index');
    }

    public function anyData()
    {
        $data = Method::orderBy('id', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($row) {
                return $row->name;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("methods.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("methods.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
