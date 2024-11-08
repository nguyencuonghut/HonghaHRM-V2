<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class DocumentController extends Controller
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
    public function store(StoreDocumentRequest $request)
    {
        if (Auth::user()->cannot('create', Document::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        // Check if employee document existed or not
        $documents = Document::where('employee_id', $request->employee_id)
                            ->where('doc_type_id', $request->doc_type_id)
                            ->get();
        if ($documents->count()) {
            Alert::toast('Giấy tờ đã được khai báo!', 'error', 'top-right');
            return redirect()->back();
        }

        // Create new
        $document = new Document();
        $document->employee_id = $request->employee_id;
        $document->doc_type_id = $request->doc_type_id;

        if ($request->hasFile('file_path')) {
            $path = 'dist/employee_document';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('file_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $document->file_path = $path . '/' . $name;
        }
        $document->save();

        //TODO: Tạo employee_document_report phục vụ báo cáo sau này
        //     $document = Document::findOrFail($request->document_id);
        // if (EmployeeDocumentReport::where('employee_id', $employee_document->employee_id)->exists()) {
        //     // The record exists, update the value
        //     $employee_document_report = EmployeeDocumentReport::where('employee_id', $request->employee_id)->first();
        //     $this->make_employee_document_report($employee_document_report->id, $document->name);
        // } else {
        //     // The record does not exist, create new
        //     $employee_document_report = new EmployeeDocumentReport();
        //     $employee_document_report->employee_id = $request->employee_id;
        //     $employee_document_report->save();
        //     $this->make_employee_document_report($employee_document_report->id, $document->name);
        // }

        Alert::toast('Tạo trạng thái giấy tờ thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $document)
    {
        if (Auth::user()->cannot('update', $document)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        // Store old document name
        $old_document_name = $document->doc_type->name;
        $document->employee_id = $request->employee_id;
        $document->doc_type_id = $request->e_doc_type_id;

        if ($request->hasFile('file_path')) {
            if ($document->file_path) {
                unlink(public_path($document->file_path));
            }

            $path = 'dist/employee_document';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('file_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $document->file_path = $path . '/' . $name;
        }
        $document->save();

        //TODO: Update employee_document_report
        // $document = Document::findOrFail($request->document_id);
        // $employee_document_report = EmployeeDocumentReport::where('employee_id', $employee_document->employee_id)->first();
        // $this->reset_employee_document_report($employee_document_report->id, $old_document_name);
        // $this->make_employee_document_report($employee_document_report->id, $document->name);

        Alert::toast('Sửa trạng thái giấy tờ thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        if (Auth::user()->cannot('delete', $document)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $employee_id = $document->employee_id;

        //TODO: Reset employee_document_report
        // $employee_document_report = EmployeeDocumentReport::where('employee_id', $employee_id)->first();
        // $this->reset_employee_document_report($employee_document_report->id, $document->doc_type->name);

        //Delete old file
        if ($document->file_path) {
            unlink(public_path($document->file_path));
        }
        // Destroy the record
        $document->delete();

        //TODO: If there is no document, destroy the employee_document_report record
        // if(!EmployeeDocument::where('employee_id', $employee_id)->count()) {
        //     $employee_document_report->destroy($employee_document_report->id);
        // }
        Alert::toast('Xóa trạng thái giấy tờ thành công!', 'success', 'top-right');
        return redirect()->back();
    }
}
