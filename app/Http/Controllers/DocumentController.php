<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\DocType;
use App\Models\Document;
use App\Models\EmployeeDocumentReport;
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

        //Tạo employee_document_report phục vụ báo cáo sau này
        $doc_type = DocType::findOrFail($request->doc_type_id);
        if (EmployeeDocumentReport::where('employee_id', $document->employee_id)->exists()) {
            // The record exists, update the value
            $employee_document_report = EmployeeDocumentReport::where('employee_id', $request->employee_id)->first();
            $this->make_employee_document_report($employee_document_report->id, $doc_type->name);
        } else {
            // The record does not exist, create new
            $employee_document_report = new EmployeeDocumentReport();
            $employee_document_report->employee_id = $request->employee_id;
            $employee_document_report->save();
            $this->make_employee_document_report($employee_document_report->id, $doc_type->name);
        }

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

        //Update employee_document_report
        $doc_type = DocType::findOrFail($request->doc_type_id);
        $employee_document_report = EmployeeDocumentReport::where('employee_id', $document->employee_id)->first();
        $this->reset_employee_document_report($employee_document_report->id, $old_document_name);
        $this->make_employee_document_report($employee_document_report->id, $doc_type->name);

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

        //Reset employee_document_report
        $employee_document_report = EmployeeDocumentReport::where('employee_id', $employee_id)->first();
        if ($employee_document_report) {
            $this->reset_employee_document_report($employee_document_report->id, $document->doc_type->name);
        }

        //Delete old file
        if ($document->file_path) {
            unlink(public_path($document->file_path));
        }
        // Destroy the record
        $document->delete();

        //If there is no document, destroy the employee_document_report record
        if(!Document::where('employee_id', $employee_id)->count()
            && $employee_document_report) {
            $employee_document_report->destroy($employee_document_report->id);
        }
        Alert::toast('Xóa trạng thái giấy tờ thành công!', 'success', 'top-right');
        return redirect()->back();
    }


    private function make_employee_document_report($id, $document_name)
    {
        $employee_document_report = EmployeeDocumentReport::findOrFail($id);
        switch ($document_name) {
            case 'Sơ yếu lý lịch':
                $employee_document_report->syll = 1;
                break;
            case 'Căn cước công dân':
                $employee_document_report->cmt = 1;
                break;
            case 'Giấy khám sức khỏe':
                $employee_document_report->sk = 1;
                break;
            case 'Giấy khai sinh':
                $employee_document_report->gks = 1;
                break;
            case 'Sổ hộ khẩu/xác nhận cư trú':
                $employee_document_report->shk = 1;
                break;
            case 'Đơn xin việc':
                $employee_document_report->dxv = 1;
                break;
            case 'Bằng cấp':
                $employee_document_report->bc = 1;
                break;
            case 'Giấy xác nhận dân sự':
                $employee_document_report->gxnds = 1;
                break;
            case 'Tờ khai':
                $employee_document_report->tk = 1;
                break;
            case 'Giấy tờ khác':
                $employee_document_report->gtk = 1;
                break;
            case 'Cam kết hội nhập':
                $employee_document_report->ckhn = 1;
                break;
            case 'Hợp đồng thử việc':
                $employee_document_report->hdtv = 1;
                break;
            case 'Hợp đồng lao động':
                $employee_document_report->hdld = 1;
                break;
            case 'Thỏa thuận bảo mật thông tin':
                $employee_document_report->ttbm = 1;
                break;
            case 'Thỏa thuận thu hồi tạm ứng':
                $employee_document_report->ttthtu = 1;
                break;
            case 'Đăng ký người phụ thuộc':
                $employee_document_report->dknpt = 1;
                break;
            case 'Cam kết thuế':
                $employee_document_report->ckt = 1;
                break;
        }
        $employee_document_report->save();
    }

    private function reset_employee_document_report($id, $old_document_name)
    {
        $employee_document_report = EmployeeDocumentReport::findOrFail($id);
        switch ($old_document_name) {
            case 'Sơ yếu lý lịch':
                $employee_document_report->syll = 0;
                break;
            case 'Căn cước công dân':
                $employee_document_report->cmt = 0;
                break;
            case 'Giấy khám sức khỏe':
                $employee_document_report->sk = 0;
                break;
            case 'Giấy khai sinh':
                $employee_document_report->gks = 0;
                break;
            case 'Sổ hộ khẩu/xác nhận cư trú':
                $employee_document_report->shk = 0;
                break;
            case 'Đơn xin việc':
                $employee_document_report->dxv = 0;
                break;
            case 'Bằng cấp':
                $employee_document_report->bc = 0;
                break;
            case 'Giấy xác nhận dân sự':
                $employee_document_report->gxnds = 0;
                break;
            case 'Tờ khai':
                $employee_document_report->tk = 0;
                break;
            case 'Giấy tờ khác':
                $employee_document_report->gtk = 0;
                break;
            case 'Cam kết hội nhập':
                $employee_document_report->ckhn = 0;
                break;
            case 'Hợp đồng thử việc':
                $employee_document_report->hdtv = 0;
                break;
            case 'Hợp đồng lao động':
                $employee_document_report->hdld = 0;
                break;
            case 'Thỏa thuận bảo mật thông tin':
                $employee_document_report->ttbm = 0;
                break;
            case 'Thỏa thuận thu hồi tạm ứng':
                $employee_document_report->ttthtu = 0;
                break;
            case 'Đăng ký người phụ thuộc':
                $employee_document_report->dknpt = 0;
                break;
            case 'Cam kết thuế':
                $employee_document_report->ckt = 0;
                break;
        }
        $employee_document_report->save();
    }
}
