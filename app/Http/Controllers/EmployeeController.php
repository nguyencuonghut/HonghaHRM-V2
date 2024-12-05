<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\StoreFromCandidateRequest;
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
use App\Models\Insurance;
use App\Models\InsuranceType;
use App\Models\JoinDate;
use App\Models\Kpi;
use App\Models\OnType;
use App\Models\Position;
use App\Models\Probation;
use App\Models\Province;
use App\Models\Recruitment;
use App\Models\RecruitmentCandidate;
use App\Models\Regime;
use App\Models\RegimeType;
use App\Models\School;
use App\Models\UserDepartment;
use App\Models\Welfare;
use App\Models\WelfareType;
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
        $insurances = Insurance::where('employee_id', $employee->id)->get();
        $regimes = Regime::where('employee_id', $employee->id)->get();
        $welfares = Welfare::where('employee_id', $employee->id)->get();
        $kpis = Kpi::where('employee_id', $employee->id)->get();
        $join_dates = JoinDate::where('employee_id', $employee->id)->get();
        $positions = Position::orderBy('name', 'asc')->get();
        $contract_types = ContractType::all();
        $on_types = OnType::all();
        $doc_types = DocType::all();
        $insurance_types = InsuranceType::all();
        $regime_types = RegimeType::all();
        $welfare_types = WelfareType::all();
        $my_position_ids = Work::where('employee_id', $employee->id)->where('status', 'On')->pluck('position_id')->toArray();
        $my_positions = Position::whereIn('id', $my_position_ids)->get();

        $this_year_total_kpi = 0;
        $this_year_my_kpis = Kpi::where('employee_id', $employee->id)->where('year', Carbon::now()->year)->get();
        foreach ($this_year_my_kpis as $this_year_my_kpi) {
            $this_year_total_kpi += $this_year_my_kpi->score;
        }
        if ($this_year_my_kpis->count()) {
            $this_year_kpi_average = $this_year_total_kpi/$this_year_my_kpis->count();
        } else {
            $this_year_kpi_average = 0;
        }

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
            'insurances' => $insurances,
            'regimes' => $regimes,
            'insurance_types' => $insurance_types,
            'regime_types' => $regime_types,
            'welfares' => $welfares,
            'welfare_types' => $welfare_types,
            'kpis' => $kpis,
            'this_year_kpi_average' => $this_year_kpi_average,
            'my_positions' => $my_positions,
            'join_dates' => $join_dates,
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
            //Only fetch the Employee according to User's Department
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $positions_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $positions_ids)->pluck('employee_id')->toArray();
            $data = Employee::with(['commune'])->whereIn('id', $employee_ids)->orderBy('code', 'desc')->get();
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
            ->editColumn('department', function ($data) {
                $dept_arr = [];
                $department_str = '';
                //Tìm tất cả Works
                $works = Work::where('employee_id', $data->id)->get();
                if (0 == $works->count()) {
                    return 'Chưa có QT công tác';
                } else {//Đã có QT công tác
                    $on_works = Work::where('employee_id', $data->id)
                                    ->where('status', 'On')
                                    ->get();
                    if ($on_works->count()) {//Có QT công tác ở trạng thái On
                        foreach ($on_works as $on_work) {
                            array_push($dept_arr, $on_work->position->department->name);
                        }
                    } else {//Còn lại là các QT công tác ở trạng thái Off
                        $last_off_works = Work::where('employee_id', $data->id)
                                        ->where('status', 'Off')
                                        ->orderBy('start_date', 'desc')
                                        ->first();
                        array_push($dept_arr, $last_off_works->position->department->name);
                    }
                    //Xóa các department trùng nhau
                    $dept_arr = array_unique($dept_arr);
                    //Convert array sang string
                    $department_str = implode(' | ', $dept_arr);
                }
                return $department_str;
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
            ->editColumn('status', function ($data) {
                $works = Work::where('employee_id', $data->id)->get();
                if (0 == $works->count()) {//Không tồn tại QT công tác nào
                    return '<span class="badge badge-secondary">Không có QT công tác</span>';
                } else {//Có QT công tác
                    //Tìm QT công tác ở trạng thái On
                    $on_works = Work::where('employee_id', $data->id)
                                    ->where('status', 'On')
                                    ->get();
                    if ($on_works->count()) {//Đang có QT công tác
                        return '<span class="badge badge-success">Đang làm</span>';
                    } else { //Chỉ có QT công tác, nhưng ở trạng thái Off
                        $last_off_work = Work::where('employee_id', $data->id)
                                        ->where('status', 'Off')
                                        ->orderBy('start_date' ,'desc')
                                        ->first();
                        switch ($last_off_work->off_type_id) {
                            case 1://Nghỉ việc
                                return '<span class="badge badge-danger">Nghỉ việc</span>';
                                break;
                            case 2://Nghỉ thai sản
                                return '<span class="badge badge-secondary">Nghỉ thai sản</span>';
                                break;
                            case 3://Nghỉ không lương
                                return '<span class="badge badge-secondary">Nghỉ không lương</span>';
                                break;
                            case 4://Nghỉ ốm
                                return '<span class="badge badge-secondary">Nghỉ ốm</span>';
                                break;
                            case 6://Nghỉ hưu
                                return '<span class="badge badge-warning">Nghỉ hưu</span>';
                                break;
                            default:
                                return '-';
                        }

                    }
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
            ->rawColumns(['actions', 'name', 'email', 'status'])
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

    public function storeFromCandidate(StoreFromCandidateRequest $request)
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

        //Create JoinDate
        $join_date = new JoinDate();
        $join_date->recruitment_candidate_id = $request->recruitment_candidate_id;
        $join_date->employee_id = $employee->id;
        $join_date->join_date = Carbon::createFromFormat('d/m/Y', $request->join_date);
        $join_date->save();

        Alert::toast('Thêm nhân sự mới thành công!', 'success', 'top-right');
        return redirect()->route('employees.show', $employee);
    }
}
