<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportDepartmentRequest;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Imports\DepartmentImport;
use App\Models\Department;
use App\Models\DepartmentManager;
use App\Models\DepartmentVice;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Work;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('department.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Department::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('departments.index');
        }

        return view('department.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = new Department();
        $department->name = $request->name;
        $department->save();

        Alert::toast('Thêm phòng/ban thành công!', 'success', 'top-right');
        return redirect()->route('departments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $datasource = [];
        $datasource = $this->createDataSource($department);

        return view('department.show',
                    [
                        'department' => $department,
                        'datasource' => $datasource,
                    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        if (Auth::user()->cannot('update', $department)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('departments.index');
        }

        return view('department.edit', ['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update(['name' => $request->name]);

        Alert::toast('Sửa phòng/ban thành công!', 'success', 'top-right');
        return redirect()->route('departments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        if (Auth::user()->cannot('delete', $department)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('departments.index');
        }

        //Check if Department is used or not
        $divisions = Division::where('department_id', $department->id)->get();
        if ($divisions->count()) {
            Alert::toast('Phòng/ban đang được sử dụng. Không thể xóa!', 'error', 'top-rigth');
            return redirect()->route('departments.index');
        }
        $department->delete();

        Alert::toast('Xóa vai trò thành công!', 'success', 'top-rigth');
        return redirect()->route('departments.index');
    }

    public function anyData()
    {
        $data = Department::orderBy('id', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('name', function($data) {
                return '<a href=' . route("departments.show", $data->id) . '>' . $data->name . '</a>' ;
            })
            ->editColumn('divisions', function ($departments) {
                $i = 0;
                $length = count($departments->divisions);
                $divisions = '';
                foreach ($departments->divisions as $division) {
                    if(++$i === $length) {
                        $divisions =  $divisions . $division->name;
                    } else {
                        $divisions = $divisions . $division->name . ', <br>';
                    }
                }
                return $divisions;
            })
            ->editColumn('position_lists', function ($departments) {
                $positions = Position::where('department_id', $departments->id)->orderBy('name')->get();
                $i = 0;
                $length = count($positions);
                $position_lists = '';
                foreach ($positions as $company_job) {
                    if(++$i === $length) {
                        $position_lists =  $position_lists . $company_job->name;
                    } else {
                        $position_lists = $position_lists . $company_job->name . ', <br>';
                    }
                }
                return $position_lists;
            })
            ->addColumn('actions', function($row){
                $action = '<a href="' . route("departments.edit", $row->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form style="display:inline" action="'. route("departments.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['name', 'actions', 'divisions', 'position_lists'])
            ->make(true);
    }

    public function import(ImportDepartmentRequest $request)
    {
        try {
            $import = new DepartmentImport;
            Excel::import($import, $request->file('file')->store('files'));
            $rows = $import->getRowCount();
            $duplicates = $import->getDuplicateCount();
            $duplicate_rows = $import->getDuplicateRows();
            if ($duplicates) {
                $duplicate_rows_list = implode(', ', $duplicate_rows);
                Alert::toast('Các dòng bị trùng lặp là '. $duplicate_rows_list);
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công! Có ' . $duplicates . ' dòng bị trùng lặp! Lặp tại dòng số: ' . $duplicate_rows_list, 'success', 'top-right');
            } else {
                Alert::toast('Import '. $rows . ' dòng dữ liệu thành công!', 'success', 'top-right');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            Alert::toast('Có lỗi xảy ra trong quá trình import dữ liệu. Vui lòng kiểm tra lại file!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function getDivision($department_id)
    {
        $divisionData['data'] = Division::orderby("name","asc")
                                    ->select('id','name')
                                    ->where('department_id',$department_id)
                                    ->get();

        return response()->json($divisionData);

    }

    private function createDataSource(Department $department)
    {
        $datasource = [];
        $manager_ids = [];

        // Tạo node trưởng phòng
        if ($department->department_manager) { // Có trưởng phòng
            $datasource = [
                'id' => $department->department_manager->manager->img_path,
                'name'=> $department->department_manager->manager->name,
                'title' => 'Trưởng phòng',
                'children' => [],
            ];
            //Gom các employee là quản lý
            array_push($manager_ids, $department->department_manager->manager->id);

            // Tạo node phó phòng
            if ($department->department_vice) { // Có phó phòng
                $child = [
                    'id' => $department->department_vice->vice->img_path,
                    'name' =>$department->department_vice->vice->name,
                    'title' => 'Phó phòng',
                    'children' => []
                ];
                array_push($datasource['children'], $child);


                //Gom các employee là quản lý
                array_push($manager_ids, $department->department_vice->vice->id);

                // Tạo node trưởng bộ phận/tổ/nhóm
                foreach ($department->divisions as $key => $division) {
                    if ($division->division_manager) { // Có trưởng bộ phận/tổ/nhóm
                        $child = [
                            'id' => $division->division_manager->manager->img_path,
                            'name' => $division->division_manager->manager->name,
                            'title' => $division->name,
                            'children' => []
                        ];
                        array_push($datasource['children'][0]['children'], $child);

                        //Gom các employee là quản lý
                        array_push($manager_ids, $division->division_manager->manager->id);

                        // Tạo node nhân viên thuộc tổ nhóm
                        $position_ids = Position::where('department_id', $department->id)
                                            ->where('division_id', $division->id)
                                            ->pluck('id')
                                            ->toArray();
                        $employee_ids = Work::whereIn('position_id', $position_ids)
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->where('status', 'On')
                                            ->pluck('employee_id')
                                            ->toArray();

                        $final_employe_ids = array_diff($employee_ids, $manager_ids);
                        $nv_employees = Employee::whereIn('id', $final_employe_ids)->get();
                        foreach ($nv_employees as $employee) {
                            $my_employee_work = Work::where('employee_id', $employee->id)
                                                    ->where('status', 'On')
                                                    ->first();
                            $child = [
                                'id' => $employee->img_path,
                                'name' => $my_employee_work->off_type_id ? $employee->name . ' (Off)' : $employee->name,
                                'title' => $my_employee_work->position->name,
                            ];
                            array_push($datasource['children'][0]['children'][$key]['children'], $child);

                            //Gom các employee là nhân viên thuộc tổ/nhóm
                            array_push($manager_ids, $employee->id);
                        }
                    } else { // Không có trưởng bộ phận/tổ/nhóm
                        $child = [
                            'id' => 'images/default-avatar.png',
                            'name' => 'Chưa có',
                            'title' => $division->name,
                            'children' => []
                        ];
                        array_push($datasource['children'][0]['children'], $child);

                        // Tạo node nhân viên thuộc tổ nhóm
                        $position_ids = Position::where('department_id', $department->id)
                                                    ->where('division_id', $division->id)
                                                    ->pluck('id')
                                                    ->toArray();
                        $employee_ids = Work::whereIn('position_id', $position_ids)
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5]) //2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->where('status', 'On')
                                            ->pluck('employee_id')
                                            ->toArray();
                        $final_employe_ids = array_diff($employee_ids, $manager_ids);
                        $nv_employees = Employee::whereIn('id', $final_employe_ids)->get();
                        foreach ($nv_employees as $employee) {
                            $my_employee_work = Work::where('employee_id', $employee->id)
                                                    ->where('status', 'On')
                                                    ->first();
                            $child = [
                                'id' => $employee->img_path,
                                'name' => $my_employee_work->off_type_id ? $employee->name . ' (Off)' : $employee->name,
                                'title' => $my_employee_work->position->name,
                            ];
                            array_push($datasource['children'][0]['children'][$key]['children'], $child);

                            //Gom các employee là nhân viên thuộc tổ/nhóm
                            array_push($manager_ids, $employee->id);
                        }
                    }
                }

                // Tạo node nhân viên không thuộc tổ/nhóm
                $dept_position_ids = Position::where('department_id', $department->id)
                                            ->pluck('id')
                                            ->toArray();
                $dept_employee_ids = Work::whereIn('position_id', $dept_position_ids)
                                        ->where(function ($query) {
                                            $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                ->orWhereNull('off_type_id');
                                        })
                                        ->where('status', 'On')
                                        ->pluck('employee_id')
                                        ->toArray();
                $remain_dept_employee_ids = array_diff( $dept_employee_ids, $manager_ids);
                $remain_nv_employees = Employee::whereIn('id', $remain_dept_employee_ids)->get();
                foreach ($remain_nv_employees as $item) {
                    $my_work = Work::where('employee_id', $item->id)
                                    ->where('status', 'On')
                                    ->first();
                    $child = [
                        'id' => $item->img_path,
                        'name' => $my_work->off_type_id ? $item->name . ' (Off)' : $item->name,
                        'title' => $my_work->position->name,
                    ];
                    array_push($datasource['children'][0]['children'], $child);
                }
            } else { //Không có phó phòng
                $child = [
                    'id' => 'images/default-avatar.png',
                    'name' => 'Chưa có',
                    'title' => 'Phó phòng',
                    'children' => []
                ];
                array_push($datasource['children'], $child);

                // Tạo node trưởng bộ phận/tổ/nhóm
                foreach ($department->divisions as $key => $division) {
                    if ($division->division_manager) { // Có trưởng bộ phận/tổ/nhóm
                        $child = [
                            'id' => $division->division_manager->manager->img_path,
                            'name' => $division->division_manager->manager->name,
                            'title' => $division->name,
                            'children' => []
                        ];
                        array_push($datasource['children'][0]['children'], $child);

                        //Gom các employee là quản lý
                        array_push($manager_ids, $division->division_manager->manager->id);

                        // Tạo node nhân viên thuộc tổ nhóm
                        $position_ids = Position::where('department_id', $department->id)
                                            ->where('division_id', $division->id)
                                            ->pluck('id')
                                            ->toArray();
                        $employee_ids = Work::whereIn('position_id', $position_ids)
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->where('status', 'On')
                                            ->pluck('employee_id')
                                            ->toArray();
                        $final_employe_ids = array_diff( $employee_ids, $manager_ids);
                        $nv_employees = Employee::whereIn('id', $final_employe_ids)->get();
                        foreach ($nv_employees as $employee) {
                            $my_employee_work = Work::where('employee_id', $employee->id)
                                                    ->where('status', 'On')
                                                    ->first();
                            $child = [
                                'id' => $employee->img_path,
                                'name' => $my_employee_work->off_type_id ? $employee->name . ' (Off)' : $employee->name,
                                'title' => $my_employee_work->position->name,
                            ];
                            array_push($datasource['children'][0]['children'][$key]['children'], $child);

                            //Gom các employee là nhân viên thuộc tổ/nhóm
                            array_push($manager_ids, $employee->id);
                        }
                    } else { // Không có trưởng bộ phận/tổ/nhóm
                        $child = [
                            'id' => 'images/default-avatar.png',
                            'name' => 'Chưa có',
                            'title' => $division->name,
                            'children' => []
                        ];
                        array_push($datasource['children'][0]['children'], $child);

                        // Tạo node nhân viên thuộc tổ nhóm
                        $position_ids = Position::where('department_id', $department->id)
                                                    ->where('division_id', $division->id)
                                                    ->pluck('id')
                                                    ->toArray();
                        $employee_ids = Work::whereIn('position_id', $position_ids)
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5]) //2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->where('status', 'On')
                                            ->pluck('employee_id')
                                            ->toArray();
                        $nv_employees = Employee::whereIn('id', $employee_ids)->get();
                        foreach ($nv_employees as $employee) {
                            $my_employee_work = Work::where('employee_id', $employee->id)
                                                    ->where('status', 'On')
                                                    ->first();
                            $child = [
                                'id' => $employee->img_path,
                                'name' => $my_employee_work->off_type_id ? $employee->name . ' (Off)' : $employee->name,
                                'title' => $my_employee_work->position->name,
                            ];
                            array_push($datasource['children'][0]['children'][$key]['children'], $child);

                            //Gom các employee là nhân viên thuộc tổ/nhóm
                            array_push($manager_ids, $employee->id);
                        }
                    }
                }

                // Tạo node nhân viên không thuộc tổ/nhóm
                $dept_position_ids = Position::where('department_id', $department->id)
                                            ->pluck('id')
                                            ->toArray();
                $dept_employee_ids = Work::whereIn('position_id', $dept_position_ids)
                                        ->where(function ($query) {
                                            $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                ->orWhereNull('off_type_id');
                                        })
                                        ->where('status', 'On')
                                        ->pluck('employee_id')
                                        ->toArray();
                $remain_dept_employee_ids = array_diff( $dept_employee_ids, $manager_ids);
                $remain_nv_employees = Employee::whereIn('id', $remain_dept_employee_ids)->get();
                foreach ($remain_nv_employees as $item) {
                    $my_work = Work::where('employee_id', $item->id)->where('status', 'On')->first();
                    $child = [
                        'id' => $item->img_path,
                        'name' => $my_work->off_type_id ? $item->name . ' (Off)' : $item->name,
                        'title' => $my_work->position->name,
                    ];
                    array_push($datasource['children'][0]['children'], $child);
                }
            }

        } else { //Không có trưởng phòng
            // Tạo node phó phòng
            $datasource = [
                'id' => 'images/default-avatar.png',
                'name'=> 'Chưa có',
                'title' => 'Trưởng phòng',
                'children' => [],
            ];

            // Tạo node phó phòng
            if ($department->department_vice) { // Có phó phòng
                $child = [
                    'id' => $department->department_vice->vice->img_path,
                    'name' =>$department->department_vice->vice->name,
                    'title' => 'Phó phòng',
                    'children' => []
                ];
                array_push($datasource['children'], $child);


                //Gom các employee là quản lý
                array_push($manager_ids, $department->department_vice->vice->id);

                // Tạo node trưởng bộ phận/tổ/nhóm
                foreach ($department->divisions as $key => $division) {
                    if ($division->division_manager) { // Có trưởng bộ phận/tổ/nhóm
                        $child = [
                            'id' => $division->division_manager->manager->img_path,
                            'name' => $division->division_manager->manager->name,
                            'title' => $division->name,
                            'children' => []
                        ];
                        array_push($datasource['children'][0]['children'], $child);

                        //Gom các employee là quản lý
                        array_push($manager_ids, $division->division_manager->manager->id);

                        // Tạo node nhân viên thuộc tổ nhóm
                        $position_ids = Position::where('department_id', $department->id)
                                            ->where('division_id', $division->id)
                                            ->pluck('id')
                                            ->toArray();
                        $employee_ids = Work::whereIn('position_id', $position_ids)
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->where('status', 'On')
                                            ->pluck('employee_id')
                                            ->toArray();

                        $final_employe_ids = array_diff( $employee_ids, $manager_ids);
                        $nv_employees = Employee::whereIn('id', $final_employe_ids)->get();
                        foreach ($nv_employees as $employee) {
                            $my_employee_work = Work::where('employee_id', $employee->id)
                                                    ->where('status', 'On')
                                                    ->first();
                            $child = [
                                'id' => $employee->img_path,
                                'name' => $my_employee_work->off_type_id ? $employee->name . ' (Off)' : $employee->name,
                                'title' => $my_employee_work->position->name,
                            ];
                            array_push($datasource['children'][0]['children'][$key]['children'], $child);

                            //Gom các employee là nhân viên thuộc tổ/nhóm
                            array_push($manager_ids, $employee->id);
                        }
                    } else { // Không có trưởng bộ phận/tổ/nhóm
                        $child = [
                            'id' => 'images/default-avatar.png',
                            'name' => 'Chưa có',
                            'title' => $division->name,
                            'children' => []
                        ];
                        array_push($datasource['children'][0]['children'], $child);

                        // Tạo node nhân viên thuộc tổ nhóm
                        $position_ids = Position::where('department_id', $department->id)
                                                    ->where('division_id', $division->id)
                                                    ->pluck('id')
                                                    ->toArray();
                        $employee_ids = Work::whereIn('position_id', $position_ids)
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5]) //2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->pluck('employee_id')
                                            ->toArray();
                        $nv_employees = Employee::whereIn('id', $employee_ids)->get();
                        foreach ($nv_employees as $employee) {
                            $my_employee_work = Work::where('employee_id', $employee->id)
                                                    ->where('status', 'On')
                                                    ->first();
                            $child = [
                                'id' => $employee->img_path,
                                'name' => $my_employee_work->off_type_id ? $employee->name . ' (Off)' : $employee->name,
                                'title' => $my_employee_work->position->name,
                            ];
                            array_push($datasource['children'][0]['children'][$key]['children'], $child);

                            //Gom các employee là nhân viên thuộc tổ/nhóm
                            array_push($manager_ids, $employee->id);
                        }
                    }
                }

                // Tạo node nhân viên không thuộc tổ/nhóm
                $dept_position_ids = Position::where('department_id', $department->id)
                                            ->pluck('id')
                                            ->toArray();
                $dept_employee_ids = Work::whereIn('position_id', $dept_position_ids)
                                        ->where(function ($query) {
                                            $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                ->orWhereNull('off_type_id');
                                        })
                                        ->where('status', 'On')
                                        ->pluck('employee_id')
                                        ->toArray();
                $remain_dept_employee_ids = array_diff( $dept_employee_ids, $manager_ids);
                $remain_nv_employees = Employee::whereIn('id', $remain_dept_employee_ids)->get();
                foreach ($remain_nv_employees as $item) {
                    $my_work = Work::where('employee_id', $item->id)
                                    ->where('status', 'On')
                                    ->first();
                    $child = [
                        'id' => $item->img_path,
                        'name' => $my_work->off_type_id ? $item->name . ' (Off)' : $item->name,
                        'title' => $my_work->position->name,
                    ];
                    array_push($datasource['children'][0]['children'], $child);
                }
            } else { //Không có phó phòng
                $child = [
                    'id' => 'images/default-avatar.png',
                    'name' => 'Chưa có',
                    'title' => 'Phó phòng',
                    'children' => []
                ];
                array_push($datasource['children'], $child);

                // Tạo node trưởng bộ phận/tổ/nhóm
                foreach ($department->divisions as $key => $division) {
                    if ($division->division_manager) { // Có trưởng bộ phận/tổ/nhóm
                        $child = [
                            'id' => $division->division_manager->manager->img_path,
                            'name' => $division->division_manager->manager->name,
                            'title' => $division->name,
                            'children' => []
                        ];
                        array_push($datasource['children'][0]['children'], $child);

                        //Gom các employee là quản lý
                        array_push($manager_ids, $division->division_manager->manager->id);

                        // Tạo node nhân viên thuộc tổ nhóm
                        $position_ids = Position::where('department_id', $department->id)
                                            ->where('division_id', $division->id)
                                            ->pluck('id')
                                            ->toArray();
                        $employee_ids = Work::whereIn('position_id', $position_ids)
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->where('status', 'On')
                                            ->pluck('employee_id')
                                            ->toArray();
                        $final_employe_ids = array_diff( $employee_ids, $manager_ids);
                        $nv_employees = Employee::whereIn('id', $final_employe_ids)->get();
                        foreach ($nv_employees as $employee) {
                            $my_employee_work = Work::where('employee_id', $employee->id)
                                                    ->where('status', 'On')
                                                    ->first();
                            $child = [
                                'id' => $employee->img_path,
                                'name' => $my_employee_work->off_type_id ? $employee->name . ' (Off)' : $employee->name,
                                'title' => $my_employee_work->position->name,
                            ];
                            array_push($datasource['children'][0]['children'][$key]['children'], $child);

                            //Gom các employee là nhân viên thuộc tổ/nhóm
                            array_push($manager_ids, $employee->id);
                        }
                    } else { // Không có trưởng bộ phận/tổ/nhóm
                        $child = [
                            'id' => 'images/default-avatar.png',
                            'name' => 'Chưa có',
                            'title' => $division->name,
                            'children' => []
                        ];
                        array_push($datasource['children'][0]['children'], $child);

                        // Tạo node nhân viên thuộc tổ nhóm
                        $position_ids = Position::where('department_id', $department->id)
                                                    ->where('division_id', $division->id)
                                                    ->pluck('id')
                                                    ->toArray();
                        $employee_ids = Work::whereIn('position_id', $position_ids)
                                            ->where(function ($query) {
                                                $query->whereIn('off_type_id', [2,3,4,5]) //2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm
                                                    ->orWhereNull('off_type_id');
                                            })
                                            ->where('status', 'On')
                                            ->pluck('employee_id')
                                            ->toArray();
                        $nv_employees = Employee::whereIn('id', $employee_ids)->get();
                        foreach ($nv_employees as $employee) {
                            $my_employee_work = Work::where('employee_id', $employee->id)
                                                    ->where('status', 'On')
                                                    ->first();
                            $child = [
                                'id' => $employee->img_path,
                                'name' => $my_employee_work->off_type_id ? $employee->name . ' (Off)' : $employee->name,
                                'title' => $my_employee_work->position->name,
                            ];
                            array_push($datasource['children'][0]['children'][$key]['children'], $child);

                            //Gom các employee là nhân viên thuộc tổ/nhóm
                            array_push($manager_ids, $employee->id);
                        }
                    }
                }

                // Tạo node nhân viên không thuộc tổ/nhóm
                $dept_position_ids = Position::where('department_id', $department->id)
                                            ->pluck('id')
                                            ->toArray();
                $dept_employee_ids = Work::whereIn('position_id', $dept_position_ids)
                                        ->where(function ($query) {
                                            $query->whereIn('off_type_id', [2,3,4,5])//2: Nghỉ thai sản, 3: Nghỉ không lương, 4: Nghỉ ốm, 5: Thay đổi chức danh
                                                ->orWhereNull('off_type_id');
                                        })
                                        ->where('status', 'On')
                                        ->pluck('employee_id')
                                        ->toArray();
                $remain_dept_employee_ids = array_diff( $dept_employee_ids, $manager_ids);
                $remain_nv_employees = Employee::whereIn('id', $remain_dept_employee_ids)->get();
                foreach ($remain_nv_employees as $item) {
                    $my_work = Work::where('employee_id', $item->id)->where('status', 'On')->first();
                    $child = [
                        'id' => $item->img_path,
                        'name' => $my_work->off_type_id ? $item->name . ' (Off)' : $item->name,
                        'title' => $my_work->position->name,
                    ];
                    array_push($datasource['children'][0]['children'], $child);
                }
            }

        }
        return $datasource;
    }
}
