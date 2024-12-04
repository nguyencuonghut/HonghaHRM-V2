<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocTypeRequest;
use App\Models\DocType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DocTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->cannot('viewAny', DocType::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('home');
        }

        return view('doc_type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', DocType::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('doc_types.index');
        }
        return view('doc_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDocTypeRequest $request)
    {
        //Create new DocType
        $document = new DocType();
        $document->name = $request->name;
        $document->save();

        Alert::toast('Thêm loại giấy tờ mới thành công!', 'success', 'top-right');
        return redirect()->route('doc_types.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocType $doc_type)
    {
        if (Auth::user()->cannot('update', $doc_type)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('doc_types.index');
        }
        return view('doc_type.edit', ['doc_type' => $doc_type]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocType $doc_type)
    {
        //Update DocType
        $doc_type->name = $request->name;
        $doc_type->save();

        Alert::toast('Sửa loại giấy tờ mới thành công!', 'success', 'top-right');
        return redirect()->route('doc_types.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocType $doc_type)
    {
        if (Auth::user()->cannot('delete', $doc_type)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('doc_types.index');
        }

        //Check if doc_type is used
        if ($doc_type->documents->count()){
            Alert::toast('Loại giấy tờ đang được sử dụng. Bạn không thể xóa!', 'error', 'top-right');
            return redirect()->route('doc_types.index');
        }
        $doc_type->delete();
        Alert::toast('Xóa loại giấy tờ thành công!', 'success', 'top-right');
        return redirect()->route('doc_types.index');
    }

    public function anyData()
    {
        $data = DocType::select(['id', 'name'])->orderBy('id', 'asc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return $data->name;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("doc_types.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("doc_types.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
