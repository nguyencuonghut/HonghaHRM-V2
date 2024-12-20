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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        if ($request->address) {
            $employee->address = $request->address;
        }
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
        //Trưởng đơn vị: Chỉ được xem những Employee thuộc phòng/ban của mình
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $manager_position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $works = Work::where('employee_id', $employee->id)
                        ->whereIn('position_id', $manager_position_ids);
            if (0 == $works->count()) {
                Alert::toast('Bạn không có quyền xem nhân sự này!', 'error', 'top-right');
                return redirect()->route('employees.index');
            }
        }
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
            $data = Employee::with(['commune'])->whereIn('id', $employee_ids)->orderBy('id', 'desc')->get();
        } else {
            $data = Employee::with(['commune'])->orderBy('id', 'desc')->get();
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
                if ($data->address) {
                    return $data->address . ', ' .  $data->commune->name .', ' .  $data->commune->district->name .', ' . $data->commune->district->province->name;
                } else {
                    return $data->commune->name .', ' .  $data->commune->district->name .', ' . $data->commune->district->province->name;
                }
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
        if ($request->address) {
            $employee->address = $request->address;
        }
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

    public function export()
    {
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            //Only fetch the Employee according to User's Department
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $positions_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $positions_ids)->pluck('employee_id')->toArray();
            $employees = Employee::with(['commune'])->whereIn('id', $employee_ids)->orderBy('code', 'desc')->get();
        } else {
            $employees = Employee::with(['commune'])->orderBy('code', 'asc')->get();
        }

        // Make new sheet
        $spreadsheet = new Spreadsheet();

        //Set font
        $styleArray = array(
            'font'  => array(
                'name'  => 'Times New Roman',
                'size' => 11,
            ),
        );
        $spreadsheet->getDefaultStyle()
                    ->applyFromArray($styleArray);

        //Create the first worksheet
        $w_sheet = $spreadsheet->getActiveSheet();
        $w_sheet->setTitle("Danh sách nhân sự");

        //Set column width
        $w_sheet->getColumnDimension('A')->setWidth(5);
        $w_sheet->getColumnDimension('B')->setWidth(10);//STT
        $w_sheet->getColumnDimension('C')->setWidth(10);//Mã
        $w_sheet->getColumnDimension('D')->setWidth(30);//Tên
        $w_sheet->getColumnDimension('E')->setWidth(50);//Vị trí
        $w_sheet->getColumnDimension('F')->setWidth(30);//Bộ phận
        $w_sheet->getColumnDimension('G')->setWidth(50);//Phòng ban
        $w_sheet->getColumnDimension('H')->setWidth(15);//Giới tính
        $w_sheet->getColumnDimension('I')->setWidth(15);//Ngày sinh
        $w_sheet->getColumnDimension('J')->setWidth(15);//CCCD
        $w_sheet->getColumnDimension('K')->setWidth(15);//Ngày cấp
        $w_sheet->getColumnDimension('L')->setWidth(50);//Nơi cấp
        $w_sheet->getColumnDimension('M')->setWidth(15);//Số đt
        $w_sheet->getColumnDimension('N')->setWidth(50);//Thường trú
        $w_sheet->getColumnDimension('O')->setWidth(50);//Tạm trú
        $w_sheet->getColumnDimension('P')->setWidth(15);//Trong tỉnh
        $w_sheet->getColumnDimension('Q')->setWidth(25);//Ngày hđ thử việc
        $w_sheet->getColumnDimension('R')->setWidth(25);//Ngày hđ chính thức
        $w_sheet->getColumnDimension('S')->setWidth(15);//Thâm niên
        $w_sheet->getColumnDimension('T')->setWidth(15);//Ngày bắt đầu
        $w_sheet->getColumnDimension('U')->setWidth(15);//Ngày kết thúc
        $w_sheet->getColumnDimension('V')->setWidth(20);//Số HĐ
        $w_sheet->getColumnDimension('W')->setWidth(25);//Loại HĐ
        $w_sheet->getColumnDimension('X')->setWidth(15);//Trình độ
        $w_sheet->getColumnDimension('Y')->setWidth(15);//Ngành
        $w_sheet->getColumnDimension('Z')->setWidth(50);//Trường
        $w_sheet->getColumnDimension('AA')->setWidth(25);//Số đt người thân
        $w_sheet->getColumnDimension('AB')->setWidth(15);//Trạng thái

        $w_sheet->getStyle('B2:W2')->getFont()->setBold(true);
        $w_sheet->mergeCells("B2:I2");

        $w_sheet->getStyle('B5:AB5')->getFont()->setBold(true);
        $w_sheet->getStyle('B5:AB5')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFA500');

        $w_sheet->getStyle('S4:W4')->getFont()->setBold(true);
        $w_sheet->mergeCells("S4:W4");
        $w_sheet->getStyle('S4:W4')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFA500');

        //Create the title of sheet
        $w_sheet->setCellValue('B2', 'DANH SÁCH NHÂN SỰ');
        $w_sheet->getStyle("B2")
                    ->getFont()
                    ->setSize(20);
        $w_sheet->getStyle("B2")
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("C")
                 ->getAlignment()
                 ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("S")
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        //Create the column name
        $w_sheet->setCellValue('B5', 'STT');
        $w_sheet->setCellValue('C5', 'Mã NV');
        $w_sheet->setCellValue('D5', 'Tên');
        $w_sheet->setCellValue('E5', 'Vị trí');
        $w_sheet->setCellValue('F5', 'Bộ phận');
        $w_sheet->setCellValue('G5', 'Phòng ban');
        $w_sheet->setCellValue('H5', 'Giới tính');
        $w_sheet->setCellValue('I5', 'Ngày sinh');
        $w_sheet->setCellValue('J5', 'CCCD');
        $w_sheet->setCellValue('K5', 'Ngày cấp');
        $w_sheet->setCellValue('L5', 'Nơi cấp');
        $w_sheet->setCellValue('M5', 'Số điện thoại');
        $w_sheet->setCellValue('N5', 'Thường trú');
        $w_sheet->setCellValue('O5', 'Tạm trú');
        $w_sheet->setCellValue('P5', 'Trong tỉnh');
        $w_sheet->setCellValue('Q5', 'Ngày hđ thử việc');
        $w_sheet->setCellValue('R5', 'Ngày hđ chính thức');
        $w_sheet->setCellValue('S5', 'Thâm niên');
        $w_sheet->setCellValue('T5', 'Ngày bắt đầu');
        $w_sheet->setCellValue('U5', 'Ngày kết thúc');
        $w_sheet->setCellValue('V5', 'Số hợp đồng');
        $w_sheet->setCellValue('W5', 'Loại hợp đồng');
        $w_sheet->setCellValue('X5', 'Trình độ');
        $w_sheet->setCellValue('Y5', 'Ngành');
        $w_sheet->setCellValue('Z5', 'Trường');
        $w_sheet->setCellValue('AA5', 'Số điện thoại người thân');
        $w_sheet->setCellValue('AB5', 'Trạng thái');

        $w_sheet->setCellValue('S4', 'Thông tin HĐ đang có hiệu lực');

        //Fill the data
        foreach ($employees as $key => $value) {
            $index = $key + 6;
            //Fill index
            $cell = 'B' . $index;
            $w_sheet->setCellValue($cell, $key + 1);
            //Fill code
            $cell = 'C' . $index;
            $w_sheet->setCellValue($cell, $value->code);
            //Fill name
            $cell = 'D' . $index;
            $w_sheet->setCellValue($cell, $value->name);
            //Fill position
            $cell = 'E' . $index;
            $position_arr = [];
            $position_str = '';

            $division_arr = [];
            $division_str = '';

            $department_arr = [];
            $department_str = '';
            //Tìm tất cả Works
            $works = Work::where('employee_id', $value->id)->get();
            if (0 == $works->count()) {
                return 'Chưa có QT công tác';
            } else {//Đã có QT công tác
                $on_works = Work::where('employee_id', $value->id)
                                ->where('status', 'On')
                                ->get();
                if ($on_works->count()) {//Có QT công tác ở trạng thái On
                    foreach ($on_works as $on_work) {
                        array_push($position_arr, $on_work->position->name);

                        array_push($department_arr, $on_work->position->department->name);

                        if ($on_work->position->division_id) {
                            array_push($division_arr, $on_work->position->division->name);
                        }
                    }
                } else {//Còn lại là các QT công tác ở trạng thái Off
                    $last_off_works = Work::where('employee_id', $value->id)
                                    ->where('status', 'Off')
                                    ->orderBy('start_date', 'desc')
                                    ->first();
                    array_push($position_arr, $last_off_works->position->name);

                    array_push($department_arr, $last_off_works->position->department->name);

                    if ($last_off_works->position->division_id) {
                        array_push($position_arr, $last_off_works->position->division->name);
                    }
                }
                //Xóa các position trùng nhau
                $position_arr = array_unique($position_arr);
                //Xóa các department trùng nhau
                $department_arr = array_unique($department_arr);
                //Xóa các division trùng nhau
                $division_arr = array_unique($division_arr);
                //Convert array sang string
                $position_str = implode(' | ', $position_arr);
                $department_str = implode(' | ', $department_arr);
                $division_str = implode(' | ', $division_arr);
            }
            $w_sheet->setCellValue($cell, $position_str);
            //Fill division
            $cell = 'F' . $index;
            $w_sheet->setCellValue($cell, $division_str);
            //Fill department
            $cell = 'G' . $index;
            $w_sheet->setCellValue($cell, $department_str);
            //Fill gender
            $cell = 'H' . $index;
            $w_sheet->setCellValue($cell, $value->gender);
            //Fill birth of date
            $cell = 'I' . $index;
            $w_sheet->setCellValue($cell, date('d/m/Y', strtotime($value->date_of_birth)));
            //Fill cccd
            $cell = 'J' . $index;
            $w_sheet->setCellValue($cell, $value->cccd);
            //Fill issued date
            $cell = 'K' . $index;
            $w_sheet->setCellValue($cell, date('d/m/Y', strtotime($value->issued_date)));
            //Fill issued by
            $cell = 'L' . $index;
            $w_sheet->setCellValue($cell, $value->issued_by);
            //Fill phone
            $cell = 'M' . $index;
            $w_sheet->setCellValue($cell, $value->phone);
            //Fill address
            $cell = 'N' . $index;
            $addr = $value->address . ', ' . $value->commune->name . ', ' . $value->commune->district->name . ', ' . $value->commune->district->province->name;
            $w_sheet->setCellValue($cell, $addr);
            //Fill temporary address
            $cell = 'O' . $index;
            $addr = '';
            if ($value->temporary_commune_id) {
                $addr = $value->temporary_address . ', ' . $value->temporary_commune->name . ', ' . $value->temporary_commune->district->name . ', ' . $value->temporary_commune->district->province->name;
            }
            $w_sheet->setCellValue($cell, $addr);
            //Fill trong tỉnh
            $cell = 'P' . $index;
            if ('Hà Nam' == $value->commune->district->province->name) {
                $w_sheet->setCellValue($cell, 'Yes');
            } else {
                $w_sheet->setCellValue($cell, 'No');
            }
            //Fill probation contract date
            $cell = 'Q' . $index;
            $probation_contracts = Contract::where('employee_id', $value->id)
                        ->where('contract_type_id', 1)//1: hđ thử việc
                        ->orderBy('start_date', 'desc')
                        ->get();
            if (0 == $probation_contracts->count()) {
                $w_sheet->setCellValue($cell, '-');
            } else {
                $probation_contract = $probation_contracts->first();
                $w_sheet->setCellValue($cell, date('d/m/Y', strtotime($probation_contract->start_date)));
            }
            //Fill formal contract date
            $cell = 'R' . $index;
            $formal_contracts = Contract::where('employee_id', $value->id)
                        ->where('contract_type_id', 2)//2: hđ chính thức
                        ->orderBy('start_date', 'desc')
                        ->get();
            if (0 == $formal_contracts->count()) {
                $w_sheet->setCellValue($cell, '-');
            } else {
                $formal_contract = $formal_contracts->first();
                $w_sheet->setCellValue($cell, date('d/m/Y', strtotime($formal_contract->start_date)));
            }

            //Fill available contract (status = On)
            $on_contract = Contract::where('employee_id', $value->id)
                                    ->where('status', 'On')
                                    ->orderBy('start_date', 'desc')
                                    ->first();
            if ($on_contract) {
                //Điền thâm niên (tính theo ngày ký hđ chính thức)
                $last_formal_contract = Contract::where('employee_id', $value->id)
                                                ->where('contract_type_id', 2)//2: hđ chính thức
                                                ->orderBy('start_date', 'desc')
                                                ->first();

                $seniority_str = '';
                if ($last_formal_contract) {
                    $seniority_str = round(ceil(Carbon::parse($last_formal_contract->start_date)->diffInYears(Carbon::now())*100)/100,2);
                }
                $cell = 'S' . $index;
                $w_sheet->setCellValue($cell, $seniority_str);

                //Điền ngày bắt đầu
                $cell = 'T' . $index;
                $w_sheet->setCellValue($cell, date('d/m/Y', strtotime($on_contract->start_date)));

                //Điền ngày kết thúc
                $cell = 'U' . $index;
                if ($on_contract->end_date) {
                    $w_sheet->setCellValue($cell, date('d/m/Y', strtotime($on_contract->end_date)));
                }

                //Điền số HĐ
                $cell = 'V' . $index;
                $w_sheet->setCellValue($cell, $on_contract->code);

                //Điền loại HĐ
                $cell = 'W' . $index;
                $w_sheet->setCellValue($cell, $on_contract->contract_type->name);
            }

            //Fill degree
            $cell = 'X' . $index;
            $employee_schools = EmployeeSchool::where('employee_id', $value->id)->get();
            $degree_arr = [];
            $degree_str = '';

            $major_arr = [];
            $major_str = '';

            $school_arr = [];
            $school_str = '';
            if ($employee_schools->count()) {
                foreach ($employee_schools as $employee_school) {
                    array_push($degree_arr, $employee_school->degree->name);

                    if ($employee_school->major) {
                        array_push($major_arr, $employee_school->major);
                    }

                    array_push($school_arr, $employee_school->school->name);
                }
                $degree_arr = array_unique($degree_arr);
                $major_arr = array_unique($major_arr);
                $school_arr = array_unique($school_arr);
                $degree_str = implode(' | ', $degree_arr);
                $major_str = implode(' | ', $major_arr);
                $school_str = implode(' | ', $school_arr);
            }
            $w_sheet->setCellValue($cell, $degree_str);


        //Fill major
        $cell = 'Y' . $index;
        $w_sheet->setCellValue($cell, $major_str);
        //Fill school
        $cell = 'Z' . $index;
        $w_sheet->setCellValue($cell, $school_str);
        //Fill relative phone
        $cell = 'AA' . $index;
        $w_sheet->setCellValue($cell, $value->relative_phone);
        //Fill status
        $cell = 'AB' . $index;
        $works = Work::where('employee_id', $value->id)->get();
        $status_str = '';
        if (0 == $works->count()) {//Không tồn tại QT công tác nào
            $status_str = 'Không có QT công tác';
        } else {//Có QT công tác
            //Tìm QT công tác ở trạng thái On
            $on_works = Work::where('employee_id', $value->id)
                            ->where('status', 'On')
                            ->get();
            if ($on_works->count()) {//Đang có QT công tác
                $status_str = 'Đang làm';
            } else { //Chỉ có QT công tác, nhưng ở trạng thái Off
                $last_off_work = Work::where('employee_id', $value->id)
                                ->where('status', 'Off')
                                ->orderBy('start_date' ,'desc')
                                ->first();
                switch ($last_off_work->off_type_id) {
                    case 1://Nghỉ việc
                        $status_str = 'Nghỉ việc';
                        break;
                    case 2://Nghỉ thai sản
                        $status_str = 'Nghỉ thai sản';
                        break;
                    case 3://Nghỉ không lương
                        $status_str = 'Nghỉ không lương';
                        break;
                    case 4://Nghỉ ốm
                        $status_str = 'Nghỉ ốm';
                        break;
                    case 6://Nghỉ hưu
                        $status_str = 'Nghỉ hưu';
                        break;
                    default:
                    $status_str = '-';
                }

            }
        }

        $w_sheet->setCellValue($cell, $status_str);
        }

        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'Danh sách CBCNV toàn công ty' . '.xlsx';
        $writer->save($file_name);

        Alert::toast('Tải file thành công!!', 'success', 'top-right');
        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    public function gallery(Request $request)
    {
        $search =  $request->input('search');
        if ($search != ""){
            if ('Trưởng đơn vị' == Auth::user()->role->name) {
                //Only fetch the Employee according to User's Department
                $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
                $positions_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
                $employee_ids = Work::whereIn('position_id', $positions_ids)->pluck('employee_id')->toArray();
                $employees = Employee::with(['commune'])
                                    ->whereIn('id', $employee_ids)
                                    ->orderBy('code', 'desc')
                                    ->where(function ($query) use ($search){
                                        $query->where('name', 'like', '%'.$search.'%')
                                            ->orWhere('code', 'like', '%'.$search.'%')
                                            ->orWhere('phone', 'like', '%'.$search.'%')
                                            ->orWhere('company_email', 'like', '%'.$search.'%');
                                    })
                                    ->paginate(9);
            } else {
                $employees = Employee::with(['commune'])
                                    ->orderBy('code', 'asc')
                                    ->where(function ($query) use ($search){
                                        $query->where('name', 'like', '%'.$search.'%')
                                            ->orWhere('code', 'like', '%'.$search.'%')
                                            ->orWhere('phone', 'like', '%'.$search.'%')
                                            ->orWhere('company_email', 'like', '%'.$search.'%');
                                    })
                                    ->paginate(9);
            }
        } else {
            if ('Trưởng đơn vị' == Auth::user()->role->name) {
                //Only fetch the Employee according to User's Department
                $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
                $positions_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
                $employee_ids = Work::whereIn('position_id', $positions_ids)->pluck('employee_id')->toArray();
                $employees = Employee::with(['commune'])
                                    ->whereIn('id', $employee_ids)
                                    ->orderBy('code', 'desc')
                                    ->paginate(9);
            } else {
                $employees = Employee::with(['commune'])
                                    ->orderBy('code', 'asc')
                                    ->paginate(9);
            }
        }

        return view('employee.gallery', ['employees' => $employees]);
    }
}
