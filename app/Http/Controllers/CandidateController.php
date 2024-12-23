<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use App\Models\Candidate;
use App\Models\CandidateSchool;
use App\Models\Commune;
use App\Models\Degree;
use App\Models\Filter;
use App\Models\Position;
use App\Models\RecruitmentCandidate;
use App\Models\School;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Helper\Html;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('candidate.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->cannot('create', Candidate::class)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('candidates.index');
        }

        $communes = Commune::orderBy('name', 'asc')->get();
        $schools = School::orderBy('name', 'asc')->get();
        $degrees = Degree::orderBy('id', 'asc')->get();

        return view('candidate.create',[
            'communes' => $communes,
            'schools' => $schools,
            'degrees' => $degrees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidateRequest $request)
    {
        $candidate = new Candidate();
        $candidate->name = $request->name;
        if ($request->email) {
            $candidate->email = $request->email;
        }
        $candidate->phone = $request->phone;
        if ($request->relative_phone) {
            $candidate->relative_phone = $request->relative_phone;
        }
        $candidate->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth);
        if ($request->cccd) {
            $candidate->cccd = $request->cccd;
        }
        if ($request->issued_date) {
            $candidate->issued_date = Carbon::createFromFormat('d/m/Y', $request->issued_date);
        }
        if ($request->issued_by) {
            $candidate->issued_by = $request->issued_by;
        }
        $candidate->gender = $request->gender;
        if ($request->address) {
            $candidate->address = $request->address;
        }
        $candidate->commune_id = $request->commune_id;
        $candidate->experience = $request->experience;
        if ($request->note) {
            $candidate->issued_by = $request->note;
        }
        $candidate->creator_id = Auth::user()->id;
        $candidate->save();

        // Create CandidateSchool
        foreach ($request->addmore as $item) {
            $candidate_school = new CandidateSchool();
            $candidate_school->candidate_id = $candidate->id;
            $candidate_school->degree_id = $item['degree_id'];
            $candidate_school->school_id = $item['school_id'];
            if ($item['major']) {
                $candidate_school->major = $item['major'];
            }
            $candidate_school->save();
        }

        Alert::toast('Thêm ứng viên mới thành công!', 'success', 'top-right');
        return redirect()->route('candidates.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidate $candidate)
    {
        return view('candidate.show', ['candidate' => $candidate]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidate $candidate)
    {
        if (Auth::user()->cannot('update', $candidate)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('candidates.index');
        }

        $communes = Commune::orderBy('name', 'asc')->get();
        $schools = School::orderBy('name', 'asc')->get();
        $degrees = Degree::orderBy('id', 'asc')->get();

        return view('candidate.edit',[
            'candidate' => $candidate,
            'communes' => $communes,
            'schools' => $schools,
            'degrees' => $degrees,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $candidate->name = $request->name;
        if ($request->email) {
            $candidate->email = $request->email;
        }
        $candidate->phone = $request->phone;
        if ($request->relative_phone) {
            $candidate->relative_phone = $request->relative_phone;
        }
        $candidate->date_of_birth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth);
        if ($request->cccd) {
            $candidate->cccd = $request->cccd;
        }
        if ($request->issued_date) {
            $candidate->issued_date = Carbon::createFromFormat('d/m/Y', $request->issued_date);
        }
        if ($request->issued_by) {
            $candidate->issued_by = $request->issued_by;
        }
        $candidate->gender = $request->gender;
        $candidate->address = $request->address;
        $candidate->commune_id = $request->commune_id;
        $candidate->experience = $request->experience;
        if ($request->note) {
            $candidate->issued_by = $request->note;
        }
        $candidate->creator_id = Auth::user()->id;
        $candidate->save();

        //Delete all old CandidateSchool
        $candidate->schools()->detach();

        // Create CandidateSchool
        foreach ($request->addmore as $item) {
            //dd($item['major']);
            $candidate_school = new CandidateSchool();
            $candidate_school->candidate_id = $candidate->id;
            $candidate_school->degree_id = $item['degree_id'];
            $candidate_school->school_id = $item['school_id'];
            if ($item['major']) {
                $candidate_school->major = $item['major'];
            }
            $candidate_school->save();
        }

        Alert::toast('Sửa ứng viên thành công!', 'success', 'top-right');
        return redirect()->route('candidates.index', $candidate->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        if (Auth::user()->cannot('delete', $candidate)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->route('candidates.index');
        }

        //Check if Candidate is used or not
        if ($candidate->recruitments->count()) {
            Alert::toast('Ứng viên đang được sử dụng. Không thể xóa!', 'error', 'top-right');
            return redirect()->route('candidates.index');
        }
        $candidate->delete();

        Alert::toast('Xóa ứng viên thành công!', 'success', 'top-rigth');
        return redirect()->route('candidates.index');
    }

    public function anyData()
    {
        $data = Candidate::with(['commune'])->orderBy('name', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($data) {
                return '<a href="'.route('candidates.show', $data->id).'">'.$data->name.'</a>';
            })
            ->editColumn('email', function ($data) {
                return $data->email;
            })
            ->editColumn('phone', function ($data) {
                return $data->phone;
            })
            ->editColumn('addr', function ($data) {
                return $data->address . ', ' . $data->commune->name .', ' .  $data->commune->district->name .', ' . $data->commune->district->province->name;
            })
            ->editColumn('cccd', function ($data) {
                return $data->cccd;
            })
            ->addColumn('recruitments', function ($data) {
                $recruitments = '';
                foreach ($data->recruitments as $recruitment) {
                    $url = '<a href="' . route('recruitments.show', $recruitment->id) . '">' . $recruitment->position->name . '</a>';
                    $recruitments = $recruitments . ' - ' . $url . '<br>';
                }
                return $recruitments;
            })
            ->addColumn('actions', function ($data) {
                $action = '<a href="' . route("candidates.edit", $data->id) . '" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                           <form style="display:inline" action="'. route("candidates.destroy", $data->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['actions', 'name', 'recruitments'])
            ->make(true);
    }

    public function export()
    {
        $candidates = Candidate::with(['commune'])->orderBy('name', 'desc')->get();

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
        $w_sheet->setTitle("Danh sách ứng viên");

        //Set column width
        $w_sheet->getColumnDimension('A')->setWidth(5);
        $w_sheet->getColumnDimension('B')->setWidth(10);//STT
        $w_sheet->getColumnDimension('C')->setWidth(30);//Tên
        $w_sheet->getColumnDimension('D')->setWidth(15);//Ngày sinh
        $w_sheet->getColumnDimension('E')->setWidth(50);//Địa chỉ
        $w_sheet->getColumnDimension('F')->setWidth(15);//CCCD
        $w_sheet->getColumnDimension('G')->setWidth(15);//Ngày cấp
        $w_sheet->getColumnDimension('H')->setWidth(50);//Nơi cấp
        $w_sheet->getColumnDimension('I')->setWidth(15);//Số đt
        $w_sheet->getColumnDimension('J')->setWidth(10);//Giới tính
        $w_sheet->getColumnDimension('K')->setWidth(25);//Tình trạng hôn nhân
        $w_sheet->getColumnDimension('L')->setWidth(50);//Trường
        $w_sheet->getColumnDimension('M')->setWidth(50);//Ngành
        $w_sheet->getColumnDimension('N')->setWidth(50);//Vị trí ứng tuyển
        $w_sheet->getColumnDimension('O')->setWidth(30);//Nơi làm việc mong muốn
        $w_sheet->getColumnDimension('P')->setWidth(50);//Kinh nghiệm
        $w_sheet->getColumnDimension('Q')->setWidth(25);//Lương mong muốn
        $w_sheet->getColumnDimension('R')->setWidth(25);//Số đt người thân
        $w_sheet->getColumnDimension('S')->setWidth(25);//Nguồn tin tuyển dụng
        $w_sheet->getColumnDimension('T')->setWidth(30);//Ghi chú

        $w_sheet->getStyle('B2:W2')->getFont()->setBold(true);
        $w_sheet->mergeCells("B2:I2");

        $w_sheet->getStyle('B5:T5')->getFont()->setBold(true);
        $w_sheet->getStyle('B5:T5')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('FFA500');

        //Create the title of sheet
        $w_sheet->setCellValue('B2', 'DANH SÁCH ỨNG VIÊN');
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
        $w_sheet->setCellValue('C5', 'Tên');
        $w_sheet->setCellValue('D5', 'Ngày sinh');
        $w_sheet->setCellValue('E5', 'Địa chỉ');
        $w_sheet->setCellValue('F5', 'CCCD');
        $w_sheet->setCellValue('G5', 'Ngày cấp');
        $w_sheet->setCellValue('H5', 'Nơi cấp');
        $w_sheet->setCellValue('I5', 'Số điện thoại');
        $w_sheet->setCellValue('J5', 'Giới tính');
        $w_sheet->setCellValue('K5', 'Tình trạng hôn nhân');
        $w_sheet->setCellValue('L5', 'Trường');
        $w_sheet->setCellValue('M5', 'Ngành học');
        $w_sheet->setCellValue('N5', 'Ứng tuyển');
        $w_sheet->setCellValue('O5', 'Nơi làm việc mong muốn');
        $w_sheet->setCellValue('P5', 'Kinh nghiệm');
        $w_sheet->setCellValue('Q5', 'Lương mong muốn');
        $w_sheet->setCellValue('R5', 'Số ĐT người thân');
        $w_sheet->setCellValue('S5', 'Nguồn tin tuyển dụng');
        $w_sheet->setCellValue('T5', 'Ghi chú');

        //Fill the data
        foreach ($candidates as $key => $value) {
            $index = $key + 6;
            //STT
            $cell = 'B' . $index;
            $w_sheet->setCellValue($cell, $key + 1);
            //Tên
            $cell = 'C' . $index;
            $w_sheet->setCellValue($cell, $value->name);
            //Ngày sinh
            $cell = 'D' . $index;
            $w_sheet->setCellValue($cell, date('d/m/Y', strtotime($value->date_of_birth)));
            //Địa chỉ
            $cell = 'E' . $index;
            $addr = $value->address . ', ' . $value->commune->name . ', ' . $value->commune->district->name . ', ' . $value->commune->district->province->name;
            $w_sheet->setCellValue($cell, $addr);
            //CCCD
            $cell = 'F' . $index;
            $w_sheet->setCellValue($cell, $value->cccd);
            //Ngày cấp
            $cell = 'G' . $index;
            $w_sheet->setCellValue($cell, date('d/m/Y', strtotime($value->issued_date)));
            //Nơi cấp
            $cell = 'H' . $index;
            $w_sheet->setCellValue($cell, $value->issued_by);
            //Số ĐT
            $cell = 'I' . $index;
            $w_sheet->setCellValue($cell, $value->phone);
            //Giới tính
            $cell = 'J' . $index;
            $w_sheet->setCellValue($cell, $value->gender);
            //Tình trạng hôn nhân
            $cell = 'K' . $index;
            $w_sheet->setCellValue($cell, '');
            //Trường
            $cell = 'L' . $index;
            $candidate_schools = CandidateSchool::where('candidate_id', $value->id)->get();
            $degree_arr = [];
            $degree_str = '';

            $major_arr = [];
            $major_str = '';

            $school_arr = [];
            $school_str = '';
            if ($candidate_schools->count()) {
                foreach ($candidate_schools as $candidate_school) {
                    array_push($degree_arr, $candidate_school->degree->name);

                    if ($candidate_school->major) {
                        array_push($major_arr, $candidate_school->major);
                    }

                    array_push($school_arr, $candidate_school->school->name);
                }
                $degree_arr = array_unique($degree_arr);
                $major_arr = array_unique($major_arr);
                $school_arr = array_unique($school_arr);
                $degree_str = implode(' | ', $degree_arr);
                $major_str = implode(' | ', $major_arr);
                $school_str = implode(' | ', $school_arr);
            }
            $w_sheet->setCellValue($cell, $school_str);
            //Ngành học
            $cell = 'M' . $index;
            $w_sheet->setCellValue($cell, $major_str . ' - ' . $degree_str);
            //Vị trí ứng tuyển
            $cell = 'N' . $index;
            $recruitments_str = '';
            foreach ($value->recruitments as $recruitment) {
                $recruitments_str = $recruitments_str . ' - ' . $recruitment->position->name . '<br>';
            }
            $recruitments_html = new Html();
            $recruitments_HTMLCODE = $recruitments_html->toRichTextObject($recruitments_str);
            $w_sheet->setCellValue($cell, $recruitments_HTMLCODE);
            //Nơi làm việc mong muốn
            $cell = 'O' . $index;
            $recruitment_candidate_ids = RecruitmentCandidate::where('candidate_id', $value->id)->pluck('id')->toArray();
            $filters = Filter::whereIn('recruitment_candidate_id', $recruitment_candidate_ids)->get();
            $work_location_str = '';
            $salary_str = '';
            foreach ($filters as $filter) {
                $work_location_str .= ' - ' . $filter->work_location. '<br>';
                $salary_str .= ' - ' . number_format($filter->salary, 0, '.', ',') . '<sup>đ</sup>' . '<br>';
            }
            $work_location_html = new Html();
            $work_location_HTMLCODE = $work_location_html->toRichTextObject($work_location_str);
            $salary_html = new Html();
            $salary_HTMLCODE = $salary_html->toRichTextObject($salary_str);

            $w_sheet->setCellValue($cell, $work_location_HTMLCODE);
            //Kinh nghiệm
            $experience_html = new Html();
            $experience_HTMLCODE = $experience_html->toRichTextObject($value->experience);

            $cell = 'P' . $index;
            $w_sheet->setCellValue($cell, $experience_HTMLCODE);
            //Lương mong muốn
            $cell = 'Q' . $index;
            $w_sheet->setCellValue($cell, $salary_HTMLCODE);
            //Số ĐT người thân
            $cell = 'R' . $index;
            $w_sheet->setCellValue($cell, $value->relative_phone);
            //Nguồn tin tuyển dụng
            $recruitment_candidates = RecruitmentCandidate::where('candidate_id', $value->id)->get();
            $channel_str = '';
            foreach ($recruitment_candidates as $recruitment_candidate) {
                $channel_str .= ' - ' . $recruitment_candidate->channel->name. '<br>';
            }
            $channel_html = new Html();
            $channel_HTMLCODE = $channel_html->toRichTextObject($channel_str);

            $cell = 'S' . $index;
            $w_sheet->setCellValue($cell, $channel_HTMLCODE);
            //Ghi chú
            $cell = 'T' . $index;
            $w_sheet->setCellValue($cell, $value->note);
        }

        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'Danh sách ứng viên' . '.xlsx';
        $writer->save($file_name);

        Alert::toast('Tải file thành công!!', 'success', 'top-right');
        return response()->download($file_name)->deleteFileAfterSend(true);
    }
}
