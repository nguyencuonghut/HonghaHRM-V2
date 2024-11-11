<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Appendix;
use App\Models\Candidate;
use App\Models\Commune;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Degree;
use App\Models\District;
use App\Models\DocType;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeSchool;
use App\Models\Family;
use App\Models\OnType;
use App\Models\Position;
use App\Models\Probation;
use App\Models\Province;
use App\Models\Recruitment;
use App\Models\RecruitmentCandidate;
use App\Models\School;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employee.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Employee::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('mployees.index');
        }

        $communes = Commune::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $provinces = Province::orderBy('name', 'asc')->get();
        $schools = School::orderBy('name', 'asc')->get();
        $degrees = Degree::orderBy('name', 'asc')->get();
        return view('employee.create',
                    [
                        'communes' => $communes,
                        'districts' => $districts,
                        'provinces' => $provinces,
                        'schools' => $schools,
                        'degrees' => $degrees,
                    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = new Employee();
        $employee->code = $request->code;
        $employee->name = $request->name;
        if ($request->hasFile('img_path')) {
            $path = 'dist/employee_img';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('img_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $employee->img_path = $path . '/' . $name;
        }
        if ($request->private_email) {
            $employee->private_email = $request->private_email;
        }
        if ($request->company_email) {
            $employee->company_email = $request->company_email;
        }
        $employee->phone = $request->phone;
        if ($request->relative_phone) {
            $employee->relative_phone = $request->relative_phone;
        }
        $employee->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth);
        if ($request->cccd) {
            $employee->cccd = $request->cccd;
        }
        if ($request->issued_date) {
            $employee->issued_date = Carbon::createFromFormat('d/m/Y', $request->issued_date);
        }
        if ($request->issued_by) {
            $employee->issued_by = $request->issued_by;
        }
        if ($request->bhxh) {
            $employee->bhxh = $request->bhxh;
        }
        $employee->gender = $request->gender;
        $employee->address = $request->address;
        $employee->commune_id = $request->commune_id;
        if ($request->temp_address) {
            $employee->temporary_address = $request->temp_address;
        }
        if ($request->temp_commune_id) {
            $employee->temporary_commune_id = $request->temp_commune_id;
        }
        $employee->experience = $request->experience;
        $employee->join_date = Carbon::createFromFormat('d/m/Y', $request->join_date);
        $employee->marriage_status = $request->marriage_status;
        $employee->save();

        // Create EmployeeSchool
        foreach ($request->addmore as $item) {
            $employee_school = new EmployeeSchool();
            $employee_school->employee_id = $employee->id;
            $employee_school->degree_id = $item['degree_id'];
            $employee_school->school_id = $item['school_id'];
            if ($item['major']) {
                $employee_school->major = $item['major'];
            }
            $employee_school->save();
        }

        Alert::toast('Thêm nhân sự mới thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $employee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $contracts = Contract::where('employee_id', $employee->id)->orderBy('id', 'desc')->get();
        $appendixes = Appendix::where('employee_id', $employee->id)->orderBy('id', 'desc')->get();
        $works = Work::where('employee_id', $employee->id)->orderBy('id', 'desc')->get();
        $documents = Document::where('employee_id', $employee->id)->get();
        $probations = Probation::where('employee_id', $employee->id)->get();
        $families = Family::where('employee_id', $employee->id)->get();
        $positions = Position::all();
        $contract_types = ContractType::all();
        $on_types = OnType::all();
        $doc_types = DocType::all();

        return view('employee.show', [
            'employee' => $employee,
            'contracts' => $contracts,
            'positions' => $positions,
            'contract_types' => $contract_types,
            'appendixes' => $appendixes,
            'documents' => $documents,
            'works' => $works,
            'on_types' => $on_types,
            'doc_types' => $doc_types,
            'probations' => $probations,
            'families' => $families,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        if (Auth::user()->cannot('update', $employee)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('employees.index');
        }

        $communes = Commune::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $provinces = Province::orderBy('name', 'asc')->get();
        $schools = School::orderBy('name', 'asc')->get();
        $degrees = Degree::orderBy('name', 'asc')->get();

        return view('employee.edit',
                    [
                        'employee' => $employee,
                        'communes' => $communes,
                        'districts' => $districts,
                        'provinces' => $provinces,
                        'schools' => $schools,
                        'degrees' => $degrees,
                    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->code = $request->code;
        $employee->name = $request->name;
        if ($request->hasFile('img_path')) {
            $path = 'dist/employee_img';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('img_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $employee->img_path = $path . '/' . $name;
        }
        if ($request->private_email) {
            $employee->private_email = $request->private_email;
        }
        if ($request->company_email) {
            $employee->company_email = $request->company_email;
        }
        $employee->phone = $request->phone;
        if ($request->relative_phone) {
            $employee->relative_phone = $request->relative_phone;
        }
        $employee->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth);
        if ($request->cccd) {
            $employee->cccd = $request->cccd;
        }
        if ($request->issued_date) {
            $employee->issued_date = Carbon::createFromFormat('d/m/Y', $request->issued_date);
        }
        if ($request->issued_by) {
            $employee->issued_by = $request->issued_by;
        }
        if ($request->bhxh) {
            $employee->bhxh = $request->bhxh;
        }
        $employee->gender = $request->gender;
        $employee->address = $request->address;
        $employee->commune_id = $request->commune_id;
        if ($request->temp_address) {
            $employee->temporary_address = $request->temp_address;
        }
        if ($request->temp_commune_id) {
            $employee->temporary_commune_id = $request->temp_commune_id;
        }
        $employee->experience = $request->experience;
        $employee->join_date = Carbon::createFromFormat('d/m/Y', $request->join_date);
        $employee->marriage_status = $request->marriage_status;
        $employee->save();

        //Delete all old EmployeeSchool
        $old_employee_schools = EmployeeSchool::where('employee_id', $employee->id)->get();
        foreach($old_employee_schools as $item) {
            $item->destroy($item->id);
        }

        // Create EmployeeSchool
        foreach ($request->addmore as $item) {
            $employee_school = new EmployeeSchool();
            $employee_school->employee_id = $employee->id;
            $employee_school->degree_id = $item['degree_id'];
            $employee_school->school_id = $item['school_id'];
            if ($item['major']) {
                $employee_school->major = $item['major'];
            }
            $employee_school->save();
        }

        Alert::toast('Sửa nhân sự thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if (Auth::user()->cannot('delete', $employee)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('employees.index');
        }
        $employee->delete();

        //Remove uploaded image
        if ($employee->img_path) {
            unlink(public_path($employee->img_path));
        }

        Alert::toast('Xóa nhân sự thành công!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            //TODO: Only fetch the Employee according to User's Department
            // $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            // $positions_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            // $employee_ids = EmployeeWork::whereIn('company_job_id', $positions_ids)->pluck('employee_id')->toArray();
            // $employees = Employee::with(['commune'])->whereIn('id', $employee_ids)->orderBy('code', 'desc')->get();
            $data = Employee::with(['commune'])->orderBy('code', 'asc')->get();
        } else {
            $data = Employee::with(['commune'])->orderBy('code', 'asc')->get();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->code;
            })
            ->editColumn('name', function ($data) {
                return '<a href="'.route('employees.show', $data->id).'">'.$data->name.'</a>';
            })
            ->editColumn('email', function ($data) {
                $email = '';
                if ($data->private_email) {
                    $email .= $data->private_email;
                }
                if ($data->company_email) {
                    if ($data->private_email) {
                        $email .= '<br>' . ' ' . $data->company_email;
                    } else {
                        $email .= $data->company_email;
                    }
                }
                return $email;
            })
            ->editColumn('phone', function ($data) {
                return $data->phone;
            })
            ->editColumn('addr', function ($data) {
                return $data->address . ', ' .  $data->commune->name .', ' .  $data->commune->district->name .', ' . $data->commune->district->province->name;
            })
            ->editColumn('cccd', function ($data) {
                return $data->cccd;
            })
            ->editColumn('temp_addr', function ($data) {
                if ($data->temporary_address) {
                    return $data->temporary_address . ', ' .  $data->temporary_commune->name .', ' .  $data->temporary_commune->district->name .', ' . $data->temporary_commune->district->province->name;
                } else {
                    return '-';
                }
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("employees.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("employees.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'name', 'email'])
            ->make(true);
    }

    public function createFromCandidate($recruitment_candidate_id)
    {
        $recruitment_candidate = RecruitmentCandidate::findOrFail($recruitment_candidate_id);
        $candidate = Candidate::findOrFail($recruitment_candidate->candidate_id);
        $recruitment = Recruitment::findOrFail($recruitment_candidate->recruitment_id);
        $communes = Commune::orderBy('name', 'asc')->get();
        $districts = District::orderBy('name', 'asc')->get();
        $provinces = Province::orderBy('name', 'asc')->get();
        $schools = School::orderBy('name', 'asc')->get();
        $degrees = Degree::orderBy('name', 'asc')->get();
        $positions = Position::orderBy('name', 'asc')->get();

        return view('employee.create_from_candidate',
                    [
                        'communes' => $communes,
                        'districts' => $districts,
                        'provinces' => $provinces,
                        'schools' => $schools,
                        'degrees' => $degrees,
                        'positions' => $positions,
                        'recruitment_candidate' => $recruitment_candidate,
                        'candidate' => $candidate,
                        'recruitment' => $recruitment,
                    ]);
    }

    public function storeFromCandidate(Request $request)
    {
        if (Auth::user()->cannot('store-from-candidate', Employee::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('candidates.index');
        }

        // Check if Employee is existed
        $existed_employee = Employee::where('name', $request->name)
                                    ->whereDate('date_of_birth', Carbon::createFromFormat('d/m/Y', $request->date_of_birth))
                                    ->where('commune_id', $request->commune_id)
                                    ->first();
        if ($existed_employee) {
            //TODO: Create ProposalCandidateEmployee
            // $proposal_candidate_employee = new ProposalCandidateEmployee();
            // $proposal_candidate_employee->proposal_candidate_id = $request->proposal_candidate_id;
            // $proposal_candidate_employee->employee_id = $existed_employee->id;
            // $proposal_candidate_employee->save();

            Alert::toast('Nhân sự đã có hồ sơ!', 'error', 'top-right');
            return redirect()->back();
        }

        $employee = new Employee();
        $employee->code = $request->code;
        $employee->name = $request->name;
        if ($request->hasFile('img_path')) {
            $path = 'dist/employee_img';

            !file_exists($path) && mkdir($path, 0777, true);

            $file = $request->file('img_path');
            $name = time() . rand(1,100) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move($path, $name);

            $employee->img_path = $path . '/' . $name;
        }
        if ($request->private_email) {
            $employee->private_email = $request->private_email;
        }
        if ($request->company_email) {
            $employee->company_email = $request->company_email;
        }
        $employee->phone = $request->phone;
        if ($request->relative_phone) {
            $employee->relative_phone = $request->relative_phone;
        }
        $employee->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth);
        if ($request->cccd) {
            $employee->cccd = $request->cccd;
        }
        if ($request->issued_date) {
            $employee->issued_date = Carbon::createFromFormat('d/m/Y', $request->issued_date);
        }
        if ($request->issued_by) {
            $employee->issued_by = $request->issued_by;
        }
        if ($request->bhxh) {
            $employee->bhxh = $request->bhxh;
        }
        $employee->gender = $request->gender;
        $employee->address = $request->address;
        $employee->commune_id = $request->commune_id;
        if ($request->temp_address) {
            $employee->temporary_address = $request->temp_address;
        }
        if ($request->temp_commune_id) {
            $employee->temporary_commune_id = $request->temp_commune_id;
        }
        $employee->experience = $request->experience;
        $employee->join_date = Carbon::createFromFormat('d/m/Y', $request->join_date);
        $employee->marriage_status = $request->marriage_status;
        $employee->save();

        // Create EmployeeSchool
        foreach ($request->addmore as $item) {
            $employee_school = new EmployeeSchool();
            $employee_school->employee_id = $employee->id;
            $school = School::where('name', $item['school_name'])->first();
            $degree = Degree::where('name', $item['degree_name'])->first();
            $employee_school->degree_id = $degree->id;
            $employee_school->school_id = $school->id;
            if ($item['major']) {
                $employee_school->major = $item['major'];
            }
            $employee_school->save();
        }

        //TODO: Create ProposalCandidateEmployee
        // $proposal_candidate_employee = new ProposalCandidateEmployee();
        // $proposal_candidate_employee->proposal_candidate_id = $request->proposal_candidate_id;
        // $proposal_candidate_employee->employee_id = $employee->id;
        // $proposal_candidate_employee->save();

        Alert::toast('Thêm nhân sự mới thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $employee);
    }
}
