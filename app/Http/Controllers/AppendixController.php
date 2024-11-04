<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppendixRequest;
use App\Http\Requests\UpdateAppendixRequest;
use App\Models\Appendix;
use App\Models\Contract;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class AppendixController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('appendix.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Add new ContractAppendix
     */
    public function getAdd($contract_id)
    {
        if (Auth::user()->cannot('create', Appendix::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }

        $contract = Contract::findOrFail($contract_id);
        return view('appendix.add', ['contract' => $contract]);
    }

    public function add(StoreAppendixRequest $request)
    {
        $appendix = new Appendix();
        $appendix->employee_id = $request->employee_id;
        $appendix->contract_id = $request->contract_id;
        $appendix->description = $request->description;
        $appendix->reason = $request->reason;
        if ($request->hasFile('file_path')) {
            $path = 'dist/employee_appendix';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('file_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $appendix->file_path = $path . '/' . $name;
        }

        // Create code
        $employee = Employee::findOrFail($request->employee_id);
        $appendix->code = preg_replace("/[^0-9]/", "", $employee->code) . '/' . Carbon::now()->format('Y') . '/' . 'HH-PLHĐ';
        $appendix->save();

        Alert::toast('Thêm phụ lục mới thành công. Bạn cần tạo QT công tác!', 'success', 'top-right');
        return redirect()->route('employees.show', $appendix->contract->employee->id);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Appendix $appendix)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appendix $appendix)
    {
        if (Auth::user()->cannot('update', $appendix)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('appendixes.index');
        }
        return view('appendix.edit', ['appendix' => $appendix]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppendixRequest $request, Appendix $appendix)
    {
        $appendix->description = $request->description;
        $appendix->reason = $request->reason;
        if ($request->hasFile('file_path')) {
            //Delete old file
            if ($appendix->file_path) {
                unlink(public_path($appendix->file_path));
            }

            $path = 'dist/employee_appendix';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('file_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $appendix->file_path = $path . '/' . $name;
        }
        $appendix->save();

        Alert::toast('Sửa phụ lục mới thành công. Bạn cần tạo QT công tác!', 'success', 'top-right');
        return redirect()->route('employees.show', $appendix->contract->employee->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appendix $appendix)
    {
        if (Auth::user()->cannot('delete', $appendix)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('appendixes.index');
        }

        //Delete file
        if ($appendix->file_path) {
            unlink(public_path($appendix->file_path));
        }
        $appendix->delete();

        Alert::toast('Xóa phụ lục thành công. Bạn cần cập nhật QT công tác!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        $data = Appendix::join('employees', 'employees.id', 'appendixes.employee_id')
                            ->select('appendixes.*', 'employees.code as employees_code')
                            ->orderBy('employees_code', 'desc')
                            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                if ($data->contract->position->division_id) {
                    return $data->contract->position->name . ' - ' . $data->contract->position->division->name .  '- ' . $data->contract->position->department->name;

                } else {
                    return $data->contract->position->name . ' - ' . $data->contract->position->department->name;
                }
            })
            ->editColumn('code', function ($data) {
                return $data->code;
            })
            ->editColumn('contract_code', function ($data) {
                return$data->contract->code;
            })
            ->editColumn('description', function ($data) {
                return $data->description;
            })
            ->editColumn('reason', function ($data) {
                return $data->reason;
            })
            ->editColumn('file', function ($data) {
                $url = '';
                if ($data->file_path) {
                    $url .= '<a target="_blank" href="../../../' . $data->file_path . '"><i class="far fa-file-pdf"></i></a>';
                    return $url;
                } else {
                    return $url;
                }

            })
            ->rawColumns(['employee_name', 'description', 'file'])
            ->make(true);
    }
}
