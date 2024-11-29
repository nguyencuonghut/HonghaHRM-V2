<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDocumentReport;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DocumentReportController extends Controller
{

    public function index()
    {
        return view('report.document.index');
    }

    public function anyData()
    {
        $data = EmployeeDocumentReport::join('employees', 'employees.id', 'employee_document_reports.employee_id')
                        ->select('employee_document_reports.*', 'employees.code as employees_code')
                        ->orderBy('employees_code', 'desc')
                        ->get();
        return DataTables::of($data)
        ->addIndexColumn()
        ->editColumn('code', function ($data) {
            return $data->employees_code;
        })
        ->editColumn('name', function ($data) {
            return '<a href="' . route("employees.show", $data->employee->id) . '">' . $data->employee->name . '</a>';
        })
        ->editColumn('syll', function ($data) {
            return $data->syll ? 'X' : '';
        })
        ->editColumn('cmt', function ($data) {
            return $data->cmt ? 'X' : '';
        })
        ->editColumn('sk', function ($data) {
            return $data->sk ? 'X' : '';
        })
        ->editColumn('gks', function ($data) {
            return $data->gks ? 'X' : '';
        })
        ->editColumn('shk', function ($data) {
            return $data->shk ? 'X' : '';
        })
        ->editColumn('dxv', function ($data) {
            return $data->dxv ? 'X' : '';
        })
        ->editColumn('bc', function ($data) {
            return $data->bc ? 'X' : '';
        })
        ->editColumn('gxnds', function ($data) {
            return $data->gxnds ? 'X' : '';
        })
        ->rawColumns(['name'])
        ->make(true);
    }
}
