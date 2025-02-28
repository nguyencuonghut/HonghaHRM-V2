<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppendixRequest;
use App\Http\Requests\UpdateAppendixRequest;
use App\Models\Appendix;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Position;
use App\Models\UserDepartment;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
        $contract = Contract::findOrFail($request->contract_id);
        $appendix->code = preg_replace("/[^0-9]/", "", $employee->code) . '/' . date('m', strtotime($contract->start_date)) . '/' . date('Y', strtotime($contract->start_date)) . '/' . 'HH-PLHĐ';
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
            if (file_exists($appendix->file_path)) {
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
        if (file_exists($appendix->file_path)) {
            unlink(public_path($appendix->file_path));
        }
        $appendix->delete();

        Alert::toast('Xóa phụ lục thành công. Bạn cần cập nhật QT công tác!', 'success', 'top-right');
        return redirect()->back();
    }

    public function anyData()
    {
        //List all Appendixes based on User's role
        if ('Trưởng đơn vị' == Auth::user()->role->name) {
            $department_ids = UserDepartment::where('user_id', Auth::user()->id)->pluck('department_id')->toArray();
            $position_ids = Position::whereIn('department_id', $department_ids)->pluck('id')->toArray();
            $employee_ids = Work::whereIn('position_id', $position_ids)->pluck('employee_id')->toArray();
            $data = Appendix::whereIn('employee_id', $employee_ids)
                                ->join('employees', 'employees.id', 'appendixes.employee_id')
                                ->select('appendixes.*', 'employees.code as employees_code')
                                ->orderBy('employees_code', 'desc')
                                ->get();
        } else {
            $data = Appendix::join('employees', 'employees.id', 'appendixes.employee_id')
                                ->select('appendixes.*', 'employees.code as employees_code')
                                ->orderBy('employees_code', 'desc')
                                ->get();
        }

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


    public function export(Appendix $appendix)
    {
        $file_name = $this->makeSampleAppendix($appendix);
        if ($file_name) {
            Alert::toast('Tải file thành công!!', 'success', 'top-right');
            return response()->download($file_name)->deleteFileAfterSend(true);
        } else {
            Alert::toast('Không tìm thấy lương khớp với ngày hợp đồng!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    private function makeSampleAppendix(Appendix $appendix)
    {
        $employee = Employee::findOrFail($appendix->employee_id);

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
        $w_sheet->setTitle("PLHĐ");


        // Thông tin cty
        $w_sheet->mergeCells("A2:D3");
        $w_sheet->setCellValue('A2', 'CÔNG TY CP DINH DƯỠNG HỒNG HÀ');
        $w_sheet->getStyle("A2")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);
        $w_sheet->getStyle("A2")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A2")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Thông tin nước
        $w_sheet->mergeCells("E2:J2");
        $w_sheet->getRowDimension('2')->setRowHeight(30);
        $w_sheet->setCellValue('E2', 'CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM');
        $w_sheet->getStyle("E2")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);
        $w_sheet->getStyle("E2")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("E2")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("E2")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $w_sheet->mergeCells("E3:J3");
        $w_sheet->setCellValue('E3', 'Độc lập - Tự do - Hạnh phúc');
        $w_sheet->getStyle("E3")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);
        $w_sheet->getStyle("E3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->mergeCells("E4:J4");
        $w_sheet->setCellValue('E4', '-----o0o-----');
        $w_sheet->getStyle("E4")
                    ->getFont()
                    ->setSize(13);
        $w_sheet->getStyle("E4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Số hợp đồng
        $w_sheet->mergeCells("A4:D4");
        $w_sheet->setCellValue('A4', 'Số: ' . $appendix->code);
        $w_sheet->getStyle("A4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Add a drawing to the worksheet
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('PhpSpreadsheet logo');
        $drawing->setDescription('PhpSpreadsheet logo');
        $img_path = public_path('images/LogoHH.png');
        $drawing->setPath($img_path);
        $drawing->setHeight(75);
        $drawing->setCoordinates('B6');
        $drawing->setOffsetX(30);
        $drawing->setOffsetY(-15);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        // Tên phụ lục
        $w_sheet->mergeCells('A10:J11');
        $w_sheet->getStyle("A10")
                ->getFont()
                ->setBold(true)
                ->setSize(18);
        $w_sheet->getStyle("A10")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A10")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('A10', 'PHỤ LỤC HỢP ĐỒNG LAO ĐỘNG');

        // Ngày ký
        $w_sheet->setCellValue('A12', 'Hôm nay ngày ' . Carbon::now()->format('d') . ' tháng ' . Carbon::now()->format('m') . ' năm ' . Carbon::now()->format('Y') . ' tại Công ty cổ phần dinh dưỡng Hồng Hà, các bên có thông tin dưới đây gồm:');
        $w_sheet->mergeCells("A12:J12");
        $w_sheet->getRowDimension('12')->setRowHeight(30);
        $w_sheet->getStyle("A12")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A12")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A15', 'NGƯỜI SỬ DỤNG LAO ĐỘNG');
        $w_sheet->getStyle("A15")
                ->getFont()
                ->setUnderline(true);

        $w_sheet->setCellValue('A16', 'CÔNG TY CỔ PHẦN DINH DƯỠNG HỒNG HÀ');
        $w_sheet->getStyle('A16')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A17', 'Địa chỉ:');
        $w_sheet->setCellValue('D17', 'KCN Đồng Văn, phường Bạch Thượng - thị xã Duy Tiên - tỉnh Hà Nam');
        $w_sheet->getStyle("A17")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('A18', 'Điện thoại:');
        $w_sheet->setCellValue('D18', '02263.836.840');
        $w_sheet->setCellValue('H18', 'Fax:');
        $w_sheet->setCellValue('I18', '02263.582.628');

        $w_sheet->setCellValue('A19', 'Đại diện pháp luật:');
        $w_sheet->setCellValue('D19', 'Ông Tạ Văn Toại');
        $w_sheet->getStyle('D19')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A20', 'Chức vụ:');
        $w_sheet->setCellValue('D20', 'Giám đốc khối Kiểm Soát');
        $w_sheet->getStyle('D20')
                ->getFont()
                ->setBold(true);

        $objRichText = new RichText();
        $objRichText->createText('(sau đây gọi là “');

        $objBold = $objRichText->createTextRun('Công ty');
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $objRichText->createText('” hoặc “');
        $objBold->getFont()->setName("Times New Roman");

        $objBold = $objRichText->createTextRun('Người sử dụng lao động');
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $objRichText->createText('”)');

        $w_sheet->getCell('A21')->setValue($objRichText);
        $w_sheet->getStyle('A21')
                ->getFont()
                ->setName('Times New Roman');

        $w_sheet->setCellValue('A23', 'NGƯỜI LAO ĐỘNG');
        $w_sheet->getStyle('A23')
                ->getFont()
                ->setUnderline(true);

        $w_sheet->setCellValue('A24', 'Ông/bà:');
        $w_sheet->setCellValue('D24', $employee->name);
        $w_sheet->getStyle('D24')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A25', 'Ngày sinh:');
        $w_sheet->setCellValue('D25', date('d/m/Y', strtotime($employee->date_of_birth)));

        $w_sheet->setCellValue('A26', 'Số CCCD:');
        $w_sheet->setCellValue('D26', $employee->cccd);

        $w_sheet->setCellValue('A27', 'Ngày cấp');
        $w_sheet->setCellValue('D27', date('d/m/Y', strtotime($employee->issued_date)));

        $w_sheet->setCellValue('A28', 'Nơi cấp:');
        $w_sheet->setCellValue('D28', $employee->issued_by);

        $w_sheet->setCellValue('A29', 'Địa chỉ thường trú:');
        $w_sheet->setCellValue('D29', $employee->address . ', ' . $employee->commune->name . ', ' . $employee->commune->district->name . ', ' . $employee->commune->district->province->name);

        $w_sheet->setCellValue('A30', 'Địa chỉ hiện tại:');
        if ($employee->temporary_address) {
            $temp_addr = $employee->temporary_address . ', ' . $employee->temporary_commune->name . ', ' . $employee->temporary_commune->district->name . ', ' . $employee->temporary_commune->district->province->name;
        } else {
            $temp_addr = $employee->address . ', ' . $employee->commune->name . ', ' . $employee->commune->district->name . ', ' . $employee->commune->district->province->name;
        }
        $w_sheet->setCellValue('D30', $temp_addr);

        $w_sheet->setCellValue('A31', 'Số điện thoại:');
        $w_sheet->setCellValue('D31', $employee->phone);

        $w_sheet->setCellValue('A32', 'Email cá nhân:');
        $w_sheet->setCellValue('D32', $employee->private_email);

        $w_sheet->setCellValue('A33', 'Giới tính:');
        $w_sheet->setCellValue('D33', $employee->gender);

        $objRichText = new RichText();
        $objRichText->createText('(sau đây gọi là “');

        $objBold = $objRichText->createTextRun('Người lao động');
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $objRichText->createText('”)');

        $w_sheet->getCell('A34')->setValue($objRichText);
        $w_sheet->getStyle('A34')
                ->getFont()
                ->setName('Times New Roman');


        // Căn cứ
        $w_sheet->setCellValue('A36', 'Căn cứ Hợp đồng lao động số ' . $appendix->contract->code . ' ký ngày ' . date('d/m/Y', strtotime($appendix->contract->start_date)) . ' và nhu cầu lao động, sử dụng lao động, Công ty và Người lao động thỏa thuận thay đổi một số nội dung của Hợp đồng lao động mà hai bên đã ký kết như sau:');
        $w_sheet->mergeCells("A36:J36");
        $w_sheet->getRowDimension('36')->setRowHeight(35);
        $w_sheet->getStyle("A36")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A36")
                ->getFont()
                ->setItalic(true);
        $w_sheet->getStyle("A36")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        //Điều 1
        $w_sheet->setCellValue('A37', 'Điều 1.');
        $w_sheet->getStyle('A37')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A37")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B37', 'Sửa đổi, bổ sung khoản ..... Điều ..... tại Hợp đồng lao động số '. $appendix->code);

        $w_sheet->setCellValue('A38', 'như sau:');

        $w_sheet->setCellValue('A39', '(ghi rõ các nội dung thay đổi)');
        $w_sheet->getStyle('A39')
                ->getFont()
                ->setItalic('true');

        //Điều 2
        $w_sheet->setCellValue('A47', 'Điều 2.');
        $w_sheet->getStyle('A47')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A47")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B47', 'Tất cả nội dung của Hợp đồng lao đồng số' . $appendix->code . ' không được');

        $w_sheet->setCellValue('A48', 'sửa đổi tại Phụ lục này sẽ giữ nguyên hiệu lực.');

        //Điều 3
        $w_sheet->setCellValue('A49', 'Điều 3.');
        $w_sheet->getStyle('A49')
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('B49', 'Phụ lục này được lập thành hai (02) bản có giá trị như nhau, mỗi bên giữ một (01) bản');
        $w_sheet->setCellValue('A50', 'và có hiệu lực kể từ ngày ký.');


        $w_sheet->getStyle("A52")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("A52:E52");
        $w_sheet->setCellValue('A52', 'NGƯỜI LAO ĐỘNG');
        $w_sheet->getStyle("A52")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("F52")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("F52:J52");
        $w_sheet->setCellValue('F52', 'ĐẠI DIỆN NGƯỜI SỬ DỤNG LAO ĐỘNG');
        $w_sheet->getStyle("F52")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("A57")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("A57:E57");
        $w_sheet->setCellValue('A57', $employee->name);
        $w_sheet->getStyle("A57")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("F57")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("F57:J57");
        $w_sheet->setCellValue('F57', 'Tạ Văn Toại');
        $w_sheet->getStyle("F57")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'PLHĐ-' . $employee->code . '-' . $employee->name . '.xlsx';
        $writer->save($file_name);

        return $file_name;
    }

}
