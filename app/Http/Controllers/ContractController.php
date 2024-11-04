<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Employee;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contract.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Contract::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('contracts.index');
        }
        return view('contract.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        // Create new Contract
        $contract = new Contract();
        $contract->employee_id = $request->employee_id;
        $contract->position_id = $request->position_id;
        $contract->contract_type_id = $request->contract_type_id;
        $contract->start_date = Carbon::createFromFormat('d/m/Y', $request->contract_s_date);
        if ($request->contract_e_date) {
            $contract->end_date = Carbon::createFromFormat('d/m/Y', $request->contract_e_date);
        }

        if ($request->hasFile('file_path')) {
            $path = 'dist/employee_contract';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('file_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $contract->file_path = $path . '/' . $name;
        }
        // Create code based on contract_type_id
        $code = '';
        $employee = Employee::findOrFail($request->employee_id);
        switch ($request->contract_type_id) {
            case 1: //HĐ thử việc
                $code = $res = preg_replace("/[^0-9]/", "", $employee->code) . '/' . Carbon::now()->format('Y') . '/' . 'HH-HĐTV';
                break;
            case 2: //HĐ lao động
                $code = preg_replace("/[^0-9]/", "", $employee->code) . '/' . Carbon::now()->format('Y') . '/' . 'HH-HĐLĐ';
                break;
            case 3: //HĐ cộng tác viên
                $code = preg_replace("/[^0-9]/", "", $employee->code) . '/' . Carbon::now()->format('Y') . '/' . 'HH-HĐCTV';
                break;
        }
        $contract->code = $code;
        $contract->status = 'On';
        $contract->save();

        Alert::toast('Thêm hợp đồng mới thành công. Bạn cần tạo QT công tác!', 'success', 'top-right');
        return redirect()->route('employees.show', $contract->employee_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        if (Auth::user()->cannot('update', $contract)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('contracts.index');
        }

        $positions = Position::all();
        $contract_types = ContractType::all();
        return view('contract.edit', [
            'contract' => $contract,
            'positions' => $positions,
            'contract_types' => $contract_types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $contract->position_id = $request->position_id;
        $contract->contract_type_id = $request->contract_type_id;
        $contract->start_date = Carbon::createFromFormat('d/m/Y', $request->s_date);
        if ($request->e_date) {
            $contract->end_date = Carbon::createFromFormat('d/m/Y', $request->e_date);
        }

        if ($request->hasFile('file_path')) {
            //Delete old file
            if ($contract->file_path) {
                unlink(public_path($contract->file_path));
            }

            $path = 'dist/employee_contract';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('file_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $contract->file_path = $path . '/' . $name;
        }

        // Create code based on contract_type_id
        $code = '';
        $employee = Employee::findOrFail($contract->employee_id);
        switch ($request->contract_type_id) {
            case 1: //HĐ thử việc
                $code = preg_replace("/[^0-9]/", "", $employee->code) . '/' . Carbon::now()->format('Y') . '/' . 'HH-HĐTV';
                break;
            case 2: //HĐ lao động
                $code = preg_replace("/[^0-9]/", "", $employee->code) . '/' . Carbon::now()->format('Y') . '/' . 'HH-HĐLĐ';
                break;
            case 3: //HĐ cộng tác viên
                $code = preg_replace("/[^0-9]/", "", $employee->code) . '/' . Carbon::now()->format('Y') . '/' . 'HH-HĐCTV';
                break;
        }
        $contract->code = $code;
        $contract->status = 'On';
        $contract->save();

        Alert::toast('Sửa hợp đồng mới thành công. Bạn cần sửa quá trình công tác!', 'success', 'top-right');
        return redirect()->route('employees.show', $contract->employee_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        if (Auth::user()->cannot('delete', $contract)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        $contract->delete();

        Alert::toast('Xóa hợp đồng thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        $data = Contract::join('employees', 'employees.id', 'contracts.employee_id')
                            ->select('contracts.*', 'employees.code as employees_code')
                            ->orderBy('employees_code', 'desc')
                            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->code;
            })
            ->editColumn('employee_name', function ($data) {
                return '<a href=' . route("employees.show", $data->employee_id) . '>' . $data->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                if ($data->position->division_id) {
                    return $data->position->name . ' - ' . $data->position->division->name .  '- ' . $data->position->department->name;

                } else {
                    return $data->position->name . ' - ' . $data->position->department->name;
                }
            })
            ->editColumn('contract', function ($data) {
                return $data->contract_type->name;
            })
            ->editColumn('start_date', function ($data) {
                return date('d/m/Y', strtotime($data->start_date));
            })
            ->editColumn('end_date', function ($data) {
                if ($data->end_date) {
                    return date('d/m/Y', strtotime($data->end_date));
                } else {
                    return '-';
                }
            })
            ->editColumn('status', function ($data) {
                if ('On' == $data->status) {
                    return '<span class="badge badge-success">' . $data->status . '</span>';
                } else {
                    return '<span class="badge badge-danger">' . $data->status . '</span>';
                }
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
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("contracts.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("contracts.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['employee_name', 'status', 'file', 'actions'])
            ->make(true);
    }

}
