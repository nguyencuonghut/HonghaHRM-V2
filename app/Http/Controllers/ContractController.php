<?php

namespace App\Http\Controllers;

use App\Http\Requests\OffContractRequest;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Salary;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
    public function update(UpdateContractRequest $request, Contract $contract)
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
                    <a href="'.route("contracts.getOff", $row->id) . '" class="btn btn-secondary btn-sm"><i class="fas fa-power-off"></i></a>
                <form style="display:inline" action="'. route("contracts.destroy", $row->id) . '" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" name="submit" onclick="return confirm(\'Bạn có muốn xóa?\');" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    <input type="hidden" name="_token" value="' . csrf_token(). '"></form>';
                return $action;
            })
            ->rawColumns(['employee_name', 'status', 'file', 'actions'])
            ->make(true);
    }

    public function getOff(Contract $contract)
    {
        if (Auth::user()->cannot('off', $contract)) {
            Alert::toast('Bạn không có quyền!', 'error', 'top-right');
            return redirect()->back();
        }
        return view('contract.off', ['contract' => $contract]);
    }

    public function off(OffContractRequest $request, Contract $contract)
    {
        // Off the EmployeeContract
        $contract->status = 'Off';
        $contract->end_date = Carbon::createFromFormat('d/m/Y', $request->e_date);
        if ($contract->request_terminate_date) {
            $contract->request_terminate_date = Carbon::createFromFormat('d/m/Y', $request->request_terminate_date);
        }
        $contract->save();

        Alert::toast('Cập nhật thành công. Bạn cần cập nhật QT công tác!', 'success', 'top-right');
        return redirect()->route('employees.show', $contract->employee_id);
    }

    public function terminateForm (Contract $contract)
    {
        $file_name = $this->makeSampleTerminateForm($contract);
        Alert::toast('Tải file thành công!!', 'success', 'top-right');
        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    public function export(Contract $contract)
    {
        // Check if Salary is existed
        $salary = Salary::where('employee_id', $contract->employee_id)
                        ->where('status', 'On')->first();
        if (!$salary) {
            Alert::toast('Nhân sự chưa có thông tin lương!', 'error', 'top-right');
            return redirect()->back();

        }
        switch ($contract->contract_type_id) {
            case 1: // HĐ thử việc
                $file_name = $this->makeSampleHdtv($contract);
                if ($file_name) {
                    Alert::toast('Tải file thành công!!', 'success', 'top-right');
                    return response()->download($file_name)->deleteFileAfterSend(true);
                } else {
                    Alert::toast('Có lỗi khi tạo file!!', 'error', 'top-right');
                    return redirect()->back();
                }

                break;
            case 2: //HĐ lao động
                $file_name = $this->makeSampleHdld($contract);
                if ($file_name) {
                    Alert::toast('Tải file thành công!!', 'success', 'top-right');
                    return response()->download($file_name)->deleteFileAfterSend(true);
                } else {
                    Alert::toast('Có lỗi khi tạo file!!', 'error', 'top-right');
                    return redirect()->back();
                }

                break;
            case 3: //HĐ cộng tác viên
                break;
        }
    }

    private function makeSampleTerminateForm(Contract $contract)
    {
        $employee = Employee::findOrFail($contract->employee_id);

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
        $w_sheet->setTitle("HĐLĐ");


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
        $w_sheet->mergeCells("A5:D5");
        $w_sheet->setCellValue('A5', 'Số: ' . preg_replace("/[^0-9]/", "", $employee->code) .'/' .  date('Y', strtotime($contract->end_date)) .'/QĐ-HH');
        $w_sheet->getStyle("A5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Thời gian
        $w_sheet->mergeCells("E5:J5");
        $w_sheet->setCellValue('E5', 'Hà Nam, ngày ' . date('d/m/Y', strtotime($contract->end_date)));
        $w_sheet->getStyle("E5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        // Add a drawing to the worksheet
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('PhpSpreadsheet logo');
        $drawing->setDescription('PhpSpreadsheet logo');
        $img_path = public_path('images/LogoHH.png');
        $drawing->setPath($img_path);
        $drawing->setHeight(75);
        $drawing->setCoordinates('B7');
        $drawing->setOffsetX(30);
        $drawing->setOffsetY(-15);
        $drawing->setWorksheet($spreadsheet->getActiveSheet());

        // Quyết định
        $w_sheet->mergeCells('A10:J11');
        $w_sheet->getStyle("A10")
                ->getFont()
                ->setBold(true)
                ->setSize(18);
        $w_sheet->getStyle("A10")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A10")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('A10', 'QUYẾT ĐỊNH');

        $w_sheet->mergeCells('A12:J12');
        $w_sheet->getStyle("A12")
                ->getFont()
                ->setBold(true)
                ->setSize(13);
        $w_sheet->getStyle("A12")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A12")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('A12', '(Về việc: Chấm dứt hợp đồng lao động với ông/bà ' .  $contract->employee->name . ')');

        // Ban giám đốc
        $w_sheet->mergeCells('A14:J14');
        $w_sheet->getStyle("A14")
                ->getFont()
                ->setBold(true)
                ->setSize(14);
        $w_sheet->getRowDimension('14')->setRowHeight(30);
        $w_sheet->getStyle("A14")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A14")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('A14', 'BAN GIÁM ĐỐC CÔNG TY CỔ PHẦN DINH DƯỠNG HỒNG HÀ');

        // Căn cứ
        $w_sheet->setCellValue('A16', '- Căn cứ Bộ luật Lao động năm 2019 và các văn bản hướng dẫn thi hành;');
        $w_sheet->mergeCells("A16:J16");

        $w_sheet->setCellValue('A17', '- Căn cứ Điều lệ và các quy định về lao động của Công ty cổ phần dinh dưỡng Hồng Hà;');
        $w_sheet->mergeCells("A17:J17");

        $w_sheet->mergeCells("A18:J19");
        $w_sheet->setCellValue('A18', '- Căn cứ Hợp đồng lao động số: ' . $contract->code . ' ký ngày ' . date('d/m/Y', strtotime($contract->start_date)) . ' giữa Công ty và Người lao động;');
        $w_sheet->getStyle("A18")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A18")
                ->getFont();
        $w_sheet->getStyle("A18")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A20', '- Xét đơn xin nghỉ việc của ông/bà ' . $contract->employee->name . ' đề nghị ngày ' . date('d/m/Y', strtotime($contract->request_terminate_date)) . '.');

        // Quyết định
        $w_sheet->mergeCells('A21:J22');
        $w_sheet->getStyle("A21")
                ->getFont()
                ->setBold(true)
                ->setSize(18);
        $w_sheet->getStyle("A21")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A21")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('A21', 'QUYẾT ĐỊNH');

        // Điều 1
        $w_sheet->mergeCells('A24:A25');
        $w_sheet->getStyle("A24")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A24")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $objRichText = new RichText();
        $objUnderlined = $objRichText->createTextRun("Điều 1:");
        $objUnderlined->getFont()->setUnderline(true);
        $objUnderlined->getFont()->setBold(true);
        $objUnderlined->getFont()->setSize(12);
        $objUnderlined->getFont()->setName("Times New Roman");
        $w_sheet->getCell("A24")->setValue($objRichText);
        $w_sheet->getStyle("A24")
                ->getFont();

        $w_sheet->mergeCells('B24:J25');
        $w_sheet->getStyle("B24")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B24")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $w_sheet->getStyle("B24")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('B24', 'Chấm dứt Hợp đồng lao động đối với ông/bà ' . $contract->employee->name . ' , chức danh: ' . $contract->position->name . ' thuộc ' . $contract->position->department->name . ' kể từ ngày ' . date('d/m/Y', strtotime($contract->end_date)) . '.');

        // Điều 2
        $w_sheet->mergeCells('A26:A29');
        $w_sheet->getStyle("A26")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A26")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $objRichText = new RichText();
        $objUnderlined = $objRichText->createTextRun("Điều 2:");
        $objUnderlined->getFont()->setUnderline(true);
        $objUnderlined->getFont()->setBold(true);
        $objUnderlined->getFont()->setSize(12);
        $objUnderlined->getFont()->setName("Times New Roman");
        $w_sheet->getCell("A26")->setValue($objRichText);
        $w_sheet->getStyle("A26")
                ->getFont();

        $w_sheet->mergeCells('B26:J27');
        $w_sheet->getStyle("B26")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B26")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $w_sheet->getStyle("B26")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('B26', 'Phòng HCNS có trách nhiệm giải quyết các thủ tục chấm dứt hợp đồng lao động cho Người lao động theo quy định.');

        $w_sheet->mergeCells('B28:J29');
        $w_sheet->getStyle("B28")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B28")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $w_sheet->getStyle("B28")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('B28', 'Phòng kế toán thực hiện thanh toán các khoản phải trả, phải thu của Người lao động (nếu có).');


        // Điều 3
        $w_sheet->mergeCells('A30:A32');
        $w_sheet->getStyle("A30")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A30")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $objRichText = new RichText();
        $objUnderlined = $objRichText->createTextRun("Điều 3:");
        $objUnderlined->getFont()->setUnderline(true);
        $objUnderlined->getFont()->setBold(true);
        $objUnderlined->getFont()->setSize(12);
        $objUnderlined->getFont()->setName("Times New Roman");
        $w_sheet->getCell("A30")->setValue($objRichText);
        $w_sheet->getStyle("A30")
                ->getFont();

        $w_sheet->mergeCells('B30:J31');
        $w_sheet->getStyle("B30")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B30")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $w_sheet->getStyle("B30")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('B30', 'Phòng HCNS, Phòng kế toán, các phòng liên quan và ông/bà ' . $contract->employee->name . ' có trách nhiệm thi hành quyết định này.');

        $w_sheet->setCellValue('B32', 'Quyết định này có hiệu lực kể từ ngày ' . date('d/m/Y', strtotime($contract->end_date)) . '.');

        // Nơi nhận
        $w_sheet->getStyle("A36")
                ->getFont()
                ->setBold(true)
                ->setUnderline(true);
        $w_sheet->mergeCells("A36:B36");
        $w_sheet->setCellValue('A36', 'Nơi nhận:');
        $w_sheet->getStyle("A36")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

        $w_sheet->setCellValue('A37', '- Ban GĐ;');
        $w_sheet->setCellValue('A38', '- Như Điều 3;');
        $w_sheet->setCellValue('A39', '- Lưu HCNS;');

        $w_sheet->getStyle("F36")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("F36:J36");
        $w_sheet->setCellValue('F36', 'TUQ. CHỦ TỊCH HĐQT');
        $w_sheet->getStyle("F36")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'HĐLĐ-' . $employee->code . '-' . $employee->name . '.xlsx';
        $writer->save($file_name);

        return $file_name;
    }

    private function makeSampleHdtv(Contract $contract)
    {
        $employee = Employee::findOrFail($contract->employee_id);
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
        $w_sheet->setTitle("HĐTV");

        // Thông tin cty
        $w_sheet->mergeCells("A2:D4");
        $w_sheet->setCellValue('A2', 'CÔNG TY CỔ PHẦN DINH DƯỠNG HỒNG HÀ');
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
        $w_sheet->mergeCells("A5:D5");
        $w_sheet->setCellValue('A5', 'Số: ' . $employee->code .'/' .  Carbon::now()->format('Y') .'/HH-HĐTVH');
        $w_sheet->getStyle("A5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Thời gian
        $w_sheet->mergeCells("E5:J5");
        $w_sheet->setCellValue('E5', 'Hà Nam, ngày ' . Carbon::now()->format('d') . ' tháng ' . Carbon::now()->format('m') . ' năm ' . Carbon::now()->format('Y'));
        $w_sheet->getStyle("E5")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Tên hợp đồng
        $w_sheet->mergeCells("A7:J8");
        $w_sheet->setCellValue('A7', 'HỢP ĐỒNG THỬ VIỆC');
        $w_sheet->getStyle("A7")
                    ->getFont()
                    ->setBold(true)
                    ->setSize(18);
        $w_sheet->getStyle("A7")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A7")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        // Căn cứ
        $w_sheet->mergeCells("A9:J9");
        $w_sheet->getRowDimension('9')->setRowHeight(30);
        $w_sheet->setCellValue('A9', '- Căn cứ Bộ Luật lao động được Quốc Hội nước Cộng Hòa Xã Hội Chủ Nghĩa Việt Nam thông qua ngày 18/6/2012.');
        $w_sheet->getStyle("A9")->getAlignment()->setWrapText(true);

        // Nhu cầu
        //$w_sheet->mergeCells("A10:J10");
        $w_sheet->setCellValue('A10', '- Theo nhu cầu và thỏa thuận của các Bên.');

        // Thời gian, địa điểm
        $w_sheet->mergeCells("A11:J11");
        $w_sheet->getRowDimension('11')->setRowHeight(30);
        $w_sheet->setCellValue('A11', 'Hôm nay, ngày ' . Carbon::now()->format('d') . ' tháng ' . Carbon::now()->format('m') . ' năm ' . Carbon::now()->format('Y') . ' tại Công ty Cổ phần Dinh Dưỡng Hồng Hà, chúng tôi gồm:');
        $w_sheet->getStyle("A11")->getAlignment()->setWrapText(true);

        // Bên A
        $w_sheet->setCellValue('A12', 'Bên A: Công ty cổ phần dinh dưỡng Hồng Hà');
        $w_sheet->getStyle("A12")
                    ->getFont()
                    ->setBold(true);

        // Địa chỉ bên A
        $w_sheet->mergeCells("A13:J13");
        $w_sheet->setCellValue('A13', '- Địa chỉ: KCN Đồng Văn, phường Bạch Thượng, huyện Duy Tiên, tỉnh Hà Nam.');
        $w_sheet->getStyle("A13")->getAlignment()->setWrapText(true);

        // Đại diện bên A
        $w_sheet->setCellValue('A14', '- Đại diện');
        $w_sheet->setCellValue('C14', 'Ông Tạ Văn Toại');
        $w_sheet->getStyle("C14")
                    ->getFont()
                    ->setBold(true);

        $w_sheet->setCellValue('G14', '- Quốc tịch: Việt Nam');
        $w_sheet->setCellValue('A15', '- Chức vụ:');
        $w_sheet->setCellValue('C15', 'Giám đốc khối Kiểm Soát');

        // Bên B
        $w_sheet->setCellValue('A16', 'Bên B:');
        $w_sheet->getStyle("A16")
                    ->getFont()
                    ->setBold(true);
        $w_sheet->setCellValue('C16', $employee->name);
        $w_sheet->getStyle("C16")
                    ->getFont()
                    ->setBold(true);
        $w_sheet->setCellValue('G16', '- Quốc tịch: Việt Nam');

        $w_sheet->setCellValue('A17', '- Sinh ngày:');
        $w_sheet->setCellValue('C17', date('d/m/Y', strtotime($employee->date_of_birth)));
        $w_sheet->getStyle("C17")
                    ->getFont()
                    ->setBold(true);

        $objRichText = new RichText();
        $objRichText->createText('- Số CCCD: ');
        $objBold = $objRichText->createTextRun($employee->cccd);
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");
        $w_sheet->getCell('A18')->setValue($objRichText);
        $w_sheet->getStyle("A18")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->mergeCells("A18:C18");

        $objRichText = new RichText();
        $objRichText->createText('- Ngày cấp: ');
        $objBold = $objRichText->createTextRun(date('d/m/Y', strtotime($employee->issued_date)));
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");
        $w_sheet->getCell('D18')->setValue($objRichText);
        $w_sheet->getStyle("D18")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->mergeCells("D18:F18");

        $objRichText = new RichText();
        $objRichText->createText('- Nơi cấp: ');
        $objBold = $objRichText->createTextRun($employee->issued_by);
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");
        $w_sheet->getCell('G18')->setValue($objRichText);
        $w_sheet->mergeCells("G18:J18");
        $w_sheet->getRowDimension('18')->setRowHeight(30);
        $w_sheet->getStyle("G18")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("G18")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Địa chỉ bên B
        $w_sheet->setCellValue('A19', '- Địa chỉ:');
        $w_sheet->setCellValue('C19', $employee->address . ', ' . $employee->commune->name . ', ' . $employee->commune->district->name . ', ' . $employee->commune->district->province->name);
        $w_sheet->getStyle("C19")
                    ->getFont()
                    ->setBold(true);

        // Thỏa thuận
        $w_sheet->setCellValue('A20', 'Thỏa thuận ký kết Hợp đồng thử việc và cam kết làm đúng những điều khoản sau đây:');

        // Điều 1
        $objRichText = new RichText();
        $objUnderlined = $objRichText->createTextRun("Điều 1:");
        $objUnderlined->getFont()->setUnderline(true);
        $objUnderlined->getFont()->setBold(true);
        $objUnderlined->getFont()->setSize(12);
        $objUnderlined->getFont()->setName("Times New Roman");
        $objRichText->createText(' Thời gian hợp đồng, địa điểm làm việc, công việc phải làm');
        $w_sheet->getCell("A21")->setValue($objRichText);
        $w_sheet->getStyle("A21")
                ->getFont()
                ->setBold(true);

        $objRichText = new RichText();
        $objRichText->createText('1. Loại hợp đồng: ');
        $objBold = $objRichText->createTextRun('Hợp đồng thử việc');
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");
        $w_sheet->getCell('A22')->setValue($objRichText);


        $objRichText = new RichText();
        $objRichText->createText('- Thời hạn hợp đồng ');
        $objBold = $objRichText->createTextRun('từ ngày ' . date('d/m/Y', strtotime($contract->start_date))
                                                            .' đến ngày '
                                                            . date('d/m/Y', strtotime($contract->end_date)));
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");
        $w_sheet->getCell('A23')->setValue($objRichText);

        $w_sheet->setCellValue('A24', '2. Địa điểm làm việc: Nhà máy Công ty Cổ phần dinh dưỡng Hồng Hà và địa bàn khác theo sự phân công của cấp trên.');
        $w_sheet->getRowDimension('24')->setRowHeight(30);
        $w_sheet->getStyle("A24")->getAlignment()->setWrapText(true);
        $w_sheet->mergeCells("A24:J24");

        $w_sheet->setCellValue('A25', '3. Công việc phải làm');
        $w_sheet->setCellValue('A26', '- Chức danh:');

        $employee_work = Work::where('employee_id', $employee->id)->where('status', 'On')->orderBy('id', 'desc')->first();
        $w_sheet->setCellValue('C26', $employee_work->position->name);
        $w_sheet->getStyle("C26")
                ->getFont()
                ->setBold(true);

        $w_sheet->mergeCells("A27:J27");
        $w_sheet->setCellValue('A27', '- Công việc phải làm: Theo bản mô tả công việc và những công việc theo sự phân công của cấp trên.');
        $w_sheet->getRowDimension('27')->setRowHeight(30);
        $w_sheet->getStyle("27")->getAlignment()->setWrapText(true);
        $w_sheet->mergeCells("A27:J27");

        // Điều 2
        $objRichText = new RichText();
        $objUnderlined = $objRichText->createTextRun("Điều 2:");
        $objUnderlined->getFont()->setUnderline(true);
        $objUnderlined->getFont()->setBold(true);
        $objUnderlined->getFont()->setSize(12);
        $objUnderlined->getFont()->setName("Times New Roman");
        $objRichText->createText(' Chế độ làm việc');
        $w_sheet->getCell("A28")->setValue($objRichText);
        $w_sheet->getStyle("A28")
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('A29', '- Thời giờ làm việc: Thời gian làm việc theo quy định tại công ty.');
        $w_sheet->setCellValue('A30', '- Được cấp phát những thiết bị, dụng cụ: Cần thiết theo yêu cầu của công việc.');
        $w_sheet->setCellValue('A31', '- Được đảm bảo điều kiện an toàn và vệ sinh lao động tại nơi làm việc theo quy định hiện hành của Nhà nước.');
        $w_sheet->mergeCells("A31:J31");
        $w_sheet->getRowDimension('31')->setRowHeight(30);
        $w_sheet->getStyle("A31")->getAlignment()->setWrapText(true);


        // Điều 3
        $objRichText = new RichText();
        $objUnderlined = $objRichText->createTextRun("Điều 3:");
        $objUnderlined->getFont()->setUnderline(true);
        $objUnderlined->getFont()->setBold(true);
        $objUnderlined->getFont()->setSize(12);
        $objUnderlined->getFont()->setName("Times New Roman");
        $objRichText->createText(' Quyền lợi và nghĩa vụ của người lao động');
        $w_sheet->getCell("A32")->setValue($objRichText);
        $w_sheet->getStyle("A32")
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('A33', '1. Quyền lợi:');
        $w_sheet->getStyle("A33")
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('A34', '- Phương tiện đi lại làm việc: tự túc.');
        $w_sheet->setCellValue('A35', '- Mức lương:');

        $employee_salary = Salary::where('employee_id', $employee->id)->whereDate('start_date', $contract->start_date)->orderBy('id', 'desc')->first();
        if (!$employee_salary) {
            return null;
        }
        $w_sheet->setCellValue('C35', number_format($employee_salary->insurance_salary, 0, '.', ',') . ' đồng/tháng');
        $w_sheet->getStyle("C35")
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('A36', 'Bằng chữ:');

        $w_sheet->setCellValue('A37', '- Hình thức trả lương: Tiền mặt hoặc chuyển khoản qua Ngân hàng 01 lần trước ngày 10 của  tháng kế tiếp.');
        $w_sheet->mergeCells("A37:J37");
        $w_sheet->getRowDimension('37')->setRowHeight(30);
        $w_sheet->getStyle("A37")->getAlignment()->setWrapText(true);

        $w_sheet->setCellValue('A38', '- Được trang bị bảo hộ lao động: Theo quy định của Công ty.');

        $w_sheet->setCellValue('A39', '- Tiền thưởng: Tùy thuộc vào kết quả kinh doanh của Công ty và theo sự đánh giá kết quả làm việc của Tổng Giám đốc Công ty.');
        $w_sheet->mergeCells("A39:J39");
        $w_sheet->getRowDimension('39')->setRowHeight(30);
        $w_sheet->getStyle("A39")->getAlignment()->setWrapText(true);

        $w_sheet->setCellValue('A40', '- Chế độ nghỉ ngơi, nghỉ phép, lễ Tết...: Được nghỉ hàng tuần vào ngày Chủ Nhật, các ngày lễ Tết theo sự quy định của Nhà nước và theo quy định của Công ty.');
        $w_sheet->mergeCells("A40:J40");
        $w_sheet->getRowDimension('40')->setRowHeight(30);
        $w_sheet->getStyle("A40")->getAlignment()->setWrapText(true);

        $w_sheet->setCellValue('A41', '- Những thoả thuận khác: Phải được sự đồng ý của hai bên.');

        $w_sheet->setCellValue('A42', '2. Nghĩa vụ:');
        $w_sheet->getStyle("A42")
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A43', '- Hoàn thành những nội dung công việc đã cam kết trong Hợp đồng.');
        $w_sheet->setCellValue('A44', '- Chấp hành nội quy, kỷ luật lao động, quy định của Công ty....');

        $w_sheet->setCellValue('A45', '- Nêu cao tinh thần tự giác trong công việc, cộng đồng doanh nghiệp và các mối quan hệ nơi đang làm việc.');
        $w_sheet->mergeCells("A45:J45");
        $w_sheet->getRowDimension('45')->setRowHeight(30);
        $w_sheet->getStyle("A45")->getAlignment()->setWrapText(true);

        $w_sheet->setCellValue('A46', '- Có trách nhiệm bảo vệ tài sản, vật chất trong Công ty.');
        $w_sheet->setCellValue('A47', '- Tuyệt đối không sử dụng khách hàng của công ty để trục lợi cá nhân.');

        $w_sheet->setCellValue('A48', '- Trường hợp nhân viên được cử đi học các khóa đào tạo nâng cao nghiệp vụ: Phải cam kết sau khóa học sẽ phục vụ cho Công ty, nếu nhân viên nghỉ việc trước thời gian quy định của Công ty thì phải hoàn trả 100% số tiền học phí mà công ty đã chi trả cho việc đào tạo nhân viên đó.');
        $w_sheet->mergeCells("A48:J48");
        $w_sheet->getRowDimension('48')->setRowHeight(45);
        $w_sheet->getStyle("A48")->getAlignment()->setWrapText(true);

        $w_sheet->setCellValue('A49', '- Trong thời gian còn hiệu lực hợp đồng và sau khi nghỉ việc tại Công ty nhân viên không được phép tiết lộ, cung cấp thông tin của Công ty cho bất kỳ tổ chức bên ngoài nào khi chưa được sự đồng ý từ phía Công ty.');
        $w_sheet->mergeCells("A49:J49");
        $w_sheet->getRowDimension('49')->setRowHeight(45);
        $w_sheet->getStyle("A49")->getAlignment()->setWrapText(true);

        $w_sheet->setCellValue('A50', '- Trong thời gian còn hiệu lực hợp đồng, nếu nghỉ việc nhân viên phải có trách nhiệm thông báo cho Công ty.');
        $w_sheet->getRowDimension('50')->setRowHeight(30);
        $w_sheet->getStyle("A50")->getAlignment()->setWrapText(true);
        $w_sheet->mergeCells("A50:J50");

        // Điều 4
        $objRichText = new RichText();
        $objUnderlined = $objRichText->createTextRun("Điều 4:");
        $objUnderlined->getFont()->setUnderline(true);
        $objUnderlined->getFont()->setBold(true);
        $objUnderlined->getFont()->setSize(12);
        $objUnderlined->getFont()->setName("Times New Roman");
        $objRichText->createText(' Nghĩa vụ và quyền hạn của người sử dụng lao động');
        $w_sheet->getCell("A51")->setValue($objRichText);
        $w_sheet->getStyle("A51")
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A52', '1. Nghĩa vụ:');
        $w_sheet->getStyle("A52")
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A53', '- Bảo đảm điều kiện làm việc và thực hiện đầy đủ những điều khoản trong hợp đồng.');
        $w_sheet->setCellValue('A54', '- Thanh toán đầy đủ, đúng thời hạn các chế độ và quyền lợi cho người lao động theo hợp đồng.');

        $w_sheet->setCellValue('A55', '2. Quyền hạn:');
        $w_sheet->getStyle("A55")
                ->getFont()
                ->setBold(true);

        $w_sheet->mergeCells("A56:J56");
        $w_sheet->getRowDimension('56')->setRowHeight(30);
        $w_sheet->getStyle("A56")->getAlignment()->setWrapText(true);
        $w_sheet->setCellValue('A56', '- Quản lý và điều hành người lao động hoàn thành công việc theo Hợp đồng (bố trí, điều chuyển, tạm ngừng việc).');

        $w_sheet->mergeCells("A57:J57");
        $w_sheet->getRowDimension('57')->setRowHeight(30);
        $w_sheet->getStyle("A57")->getAlignment()->setWrapText(true);
        $w_sheet->setCellValue('A57', '- Tạm hoãn, chấm dứt hợp đồng lao động mà không phải báo trước khi người lao động thử việc không đạt yêu cầu.');

        $w_sheet->setCellValue('A58', '- Các quyền và nghĩa vụ khác theo quy định của pháp luật.');

        // Điều 5
        $objRichText = new RichText();
        $objUnderlined = $objRichText->createTextRun("Điều 5:");
        $objUnderlined->getFont()->setUnderline(true);
        $objUnderlined->getFont()->setBold(true);
        $objUnderlined->getFont()->setSize(12);
        $objUnderlined->getFont()->setName("Times New Roman");
        $objRichText->createText(' Điều khoản thi hành');
        $w_sheet->getCell("A59")->setValue($objRichText);
        $w_sheet->getStyle("A59")
                ->getFont()
                ->setBold(true);

        $w_sheet->mergeCells("A60:J60");
        $w_sheet->getRowDimension('60')->setRowHeight(30);
        $w_sheet->getStyle("A60")->getAlignment()->setWrapText(true);
        $w_sheet->setCellValue('A60', '- Những vấn đề về lao động không ghi trong hợp đồng này thì áp dụng theo quy định của pháp luật về lao động.');

        $w_sheet->setCellValue('A61', '- Hợp đồng này được lập thành 02 bản có giá trị như nhau, mỗi bên giữ 01 bản và có hiệu lực kể từ ngày ký.');
        $w_sheet->mergeCells("A61:J61");
        $w_sheet->getRowDimension('61')->setRowHeight(30);
        $w_sheet->getStyle("A61")->getAlignment()->setWrapText(true);

        $w_sheet->mergeCells("A62:J62");
        $w_sheet->setCellValue('A62', '- Khi hai bên ký kết phụ lục hợp đồng thì nội dung của phụ lục hợp đồng cũng có giá trị như các nội dung của bản hợp đồng này.');
        $w_sheet->getRowDimension('62')->setRowHeight(30);
        $w_sheet->getStyle("A62")->getAlignment()->setWrapText(true);
        $w_sheet->mergeCells("A62:J62");

        $w_sheet->getStyle("A64")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("A64:E64");
        $w_sheet->setCellValue('A64', 'Đại diện bên B');$w_sheet->getStyle("A64")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        $w_sheet->getStyle("F64")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("F64:J64");
        $w_sheet->setCellValue('F64', 'Đại diện bên A');$w_sheet->getStyle("F64")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("A69")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("A69:E69");
        $w_sheet->setCellValue('A69', $employee->name);
        $w_sheet->getStyle("A69")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("F69")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("F69:J69");
        $w_sheet->setCellValue('F69', 'Tạ Văn Toại');
        $w_sheet->getStyle("F69")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'HĐTV-' . $employee->code . '-' . $employee->name . '.xlsx';
        $writer->save($file_name);

        return $file_name;
    }

    private function makeSampleHdld(Contract $contract)
    {
        $employee = Employee::findOrFail($contract->employee_id);

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
        $w_sheet->setTitle("HĐLĐ");


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
        $w_sheet->setCellValue('A4', 'Số: ' . $employee->code .'/' .  Carbon::now()->format('Y') .'/HH-HĐLĐ');
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

        // Tên hđ
        $w_sheet->mergeCells('A10:J11');
        $w_sheet->getStyle("A10")
                ->getFont()
                ->setBold(true)
                ->setSize(18);
        $w_sheet->getStyle("A10")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $w_sheet->getStyle("A10")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $w_sheet->setCellValue('A10', 'HỢP ĐỒNG LAO ĐỘNG');

        // Căn cứ
        $w_sheet->setCellValue('A12', '- Căn cứ Bộ luật Lao động số 45/2019/QH14 ngày 20 tháng 11 năm 2019 của Quốc hội nước Cộng hoà Xã hội Chủ nghĩa Việt Nam.');
        $w_sheet->mergeCells("A12:J12");
        $w_sheet->getRowDimension('12')->setRowHeight(30);
        $w_sheet->getStyle("A12")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A12")
                ->getFont()
                ->setItalic(true);
        $w_sheet->getStyle("A12")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A13', '- Căn cứ Nghị định số 45/2020/NĐ-CP ngày 14 tháng 12 năm 2020 của Chính phủ quy định chi tiết và hướng dẫn thi hành một số điều của Bộ luật Lao động về điều kiện lao động và quan hệ lao động.');
        $w_sheet->mergeCells("A13:J13");
        $w_sheet->getRowDimension('13')->setRowHeight(45);
        $w_sheet->getStyle("A13")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A13")
                ->getFont()
                ->setItalic(true);
        $w_sheet->getStyle("A13")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A14', '- Căn cứ vào nhu cầu lao động và sử dụng lao động của các bên.');
        $w_sheet->mergeCells("A14:J14");
        $w_sheet->getStyle("A14")
                ->getFont()
                ->setItalic(true);
        $w_sheet->getStyle("A14")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Ngày ký
        $w_sheet->setCellValue('A16', 'Hôm nay ngày ' . date('d', strtotime($contract->start_date)) . ' tháng ' . date('m', strtotime($contract->start_date)) . ' năm ' . date('Y', strtotime($contract->start_date)) . ' tại Công ty cổ phần dinh dưỡng Hồng Hà, các bên có thông tin dưới đây gồm:');
        $w_sheet->mergeCells("A16:J16");
        $w_sheet->getRowDimension('16')->setRowHeight(30);
        $w_sheet->getStyle("A16")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A16")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $w_sheet->setCellValue('A18', 'NGƯỜI SỬ DỤNG LAO ĐỘNG');
        $w_sheet->getStyle("A18")
                ->getFont()
                ->setUnderline(true);

        $w_sheet->setCellValue('A19', 'CÔNG TY CỔ PHẦN DINH DƯỠNG HỒNG HÀ');
        $w_sheet->getStyle('A19')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A20', 'Địa chỉ:');
        $w_sheet->setCellValue('D20', 'KCN Đồng Văn, phường Bạch Thượng - thị xã Duy Tiên - tỉnh Hà Nam');
        $w_sheet->getStyle("A20")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->getRowDimension('20')->setRowHeight(30);
        $w_sheet->mergeCells('D20:J20');
        $w_sheet->getStyle("D20")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("D20")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A21', 'Điện thoại:');
        $w_sheet->setCellValue('D21', '02263.836.840');
        $w_sheet->setCellValue('H21', 'Fax:');
        $w_sheet->setCellValue('I21', '02263.582.628');

        $w_sheet->setCellValue('A22', 'Đại diện pháp luật:');
        $w_sheet->setCellValue('D22', 'Ông Tạ Văn Toại');
        $w_sheet->getStyle('D22')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A23', 'Chức vụ:');
        $w_sheet->setCellValue('D23', 'Giám đốc khối Kiểm Soát');
        $w_sheet->getStyle('D23')
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

        $w_sheet->getCell('A24')->setValue($objRichText);
        $w_sheet->getStyle('A24')
                ->getFont()
                ->setName('Times New Roman');

        $w_sheet->setCellValue('A26', 'NGƯỜI LAO ĐỘNG');
        $w_sheet->getStyle('A26')
                ->getFont()
                ->setUnderline(true);

        $w_sheet->setCellValue('A27', 'Ông/bà:');
        $w_sheet->setCellValue('D27', $employee->name);
        $w_sheet->getStyle('D27')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A28', 'Ngày sinh:');
        $w_sheet->setCellValue('D28', date('d/m/Y', strtotime($employee->date_of_birth)));

        $w_sheet->setCellValue('A29', 'Số CCCD:');
        $w_sheet->setCellValue('D29', $employee->cccd);

        $w_sheet->setCellValue('A30', 'Ngày cấp');
        $w_sheet->setCellValue('D30', date('d/m/Y', strtotime($employee->issued_date)));

        $w_sheet->setCellValue('A31', 'Nơi cấp:');
        $w_sheet->setCellValue('D31', $employee->issued_by);

        $w_sheet->setCellValue('A32', 'Địa chỉ thường trú:');
        $w_sheet->setCellValue('D32', $employee->address . ', ' . $employee->commune->name . ', ' . $employee->commune->district->name . ', ' . $employee->commune->district->province->name);

        $w_sheet->setCellValue('A33', 'Địa chỉ hiện tại:');
        if ($employee->temporary_address) {
            $temp_addr = $employee->temporary_address . ', ' . $employee->temporary_commune->name . ', ' . $employee->temporary_commune->district->name . ', ' . $employee->temporary_commune->district->province->name;
        } else {
            $temp_addr = $employee->address . ', ' . $employee->commune->name . ', ' . $employee->commune->district->name . ', ' . $employee->commune->district->province->name;
        }
        $w_sheet->setCellValue('D33', $temp_addr);

        $w_sheet->setCellValue('A34', 'Email cá nhân:');
        $w_sheet->setCellValue('D34', $employee->private_email);

        $objRichText = new RichText();
        $objRichText->createText('(sau đây gọi là “');

        $objBold = $objRichText->createTextRun('Người lao động');
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $objRichText->createText('”)');

        $w_sheet->getCell('A35')->setValue($objRichText);
        $w_sheet->getStyle('A35')
                ->getFont()
                ->setName('Times New Roman');


        $objRichText = new RichText();
        $objRichText->createText('Hai bên thỏa thuận ký kết hợp đồng lao động này (“');

        $objBold = $objRichText->createTextRun('Hợp đồng');
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $objRichText->createText('”) và cam kết thực hiện đúng những điều khoản sau đây:');

        $w_sheet->getCell('A37')->setValue($objRichText);
        $w_sheet->getStyle('A37')
                ->getFont()
                ->setName('Times New Roman');

        $w_sheet->getRowDimension('37')->setRowHeight(30);
        $w_sheet->mergeCells('A37:J37');
        $w_sheet->getStyle("A37")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A37")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A38', 'Điều 1. Thời hạn và công việc Hợp đồng');
        $w_sheet->getStyle('A38')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A39', '1.1.');
        $w_sheet->getStyle('A39')
                ->getFont()
                ->setBold('true');

        $objRichText = new RichText();
        $objRichText->createText('Loại Hợp đồng lao động: ');
        if ($contract->end_date) {
            $diff_months = Carbon::parse($contract->start_date)->diffInMonths(Carbon::parse($contract->end_date));
            $objBold = $objRichText->createTextRun('Xác định thời hạn ' . $diff_months .' tháng');
        } else {
            $objBold = $objRichText->createTextRun('Không xác định thời hạn');
        }
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $w_sheet->getCell('B39')->setValue($objRichText);

        $w_sheet->setCellValue('A40', '1.2.');
        $w_sheet->getStyle('A40')
                ->getFont()
                ->setBold('true');

        $objRichText = new RichText();
        $objRichText->createText('Hợp đồng chính thức từ: ');
        if ($contract->end_date) {
            $objBold = $objRichText->createTextRun(date('d/m/Y', strtotime($contract->start_date)) . ' đến ' . date('d/m/Y', strtotime($contract->end_date)));
        } else {
            // Không thời hạn
            $objBold = $objRichText->createTextRun(date('d/m/Y', strtotime($contract->start_date)));
        }
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $w_sheet->getCell('B40')->setValue($objRichText);

        $w_sheet->setCellValue('A41', '1.3.');
        $w_sheet->getStyle('A41')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A41")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B41', 'Địa điểm làm việc: Hà Nam, Việt Nam và các địa điểm khác khi có yêu cầu của Công ty vào từng thời điểm. Tuy nhiên, Người lao động sẽ làm việc tại và/hoặc đi công tác đến những nơi khác (bên trong hoặc bên ngoài Việt Nam) theo yêu cầu hợp lý của Công ty.');
        $w_sheet->getRowDimension('41')->setRowHeight(52);
        $w_sheet->mergeCells('B41:J41');
        $w_sheet->getStyle("B41")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B41")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A42', '1.4.');
        $w_sheet->getStyle('A42')
                ->getFont()
                ->setBold(true);

        $objRichText = new RichText();
        $objRichText->createText('Chức danh chuyên môn: ');
        $employee_work = Work::where('employee_id', $employee->id)->orderBy('id', 'desc')->first();
        $objBold = $objRichText->createTextRun($employee_work->position->name);
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $w_sheet->getCell('B42')->setValue($objRichText);

        $w_sheet->setCellValue('A43', '1.5.');
        $w_sheet->getStyle('A43')
                ->getFont()
                ->setBold(true);

        $objRichText = new RichText();
        $objRichText->createText('Phòng/ban: ');
        $employee_work = Work::where('employee_id', $employee->id)->orderBy('id', 'desc')->first();
        $objBold = $objRichText->createTextRun($employee_work->position->department->name);
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");

        $w_sheet->getCell('B43')->setValue($objRichText);

        $w_sheet->setCellValue('A44', '1.6.');
        $w_sheet->getStyle('A44')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A44")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $objRichText = new RichText();
        $objRichText->createText('Công việc phải làm: ');
        $objBold = $objRichText->createTextRun('Theo bản mô tả công việc và mục tiêu công việc được giao kèm theo Hợp đồng lao động này.');
        $objBold->getFont()->setItalic(true);
        $objBold->getFont()->setName("Times New Roman");

        $objRichText->createText(PHP_EOL);

        $objRichText->createText('Chức danh chuyên môn và công việc phải làm được mô tả trong bản trách nhiệm công việc và/hoặc mục tiêu công việc theo từng thời điểm, được công bố trong cơ sở dữ liệu của Công ty.');

        $w_sheet->getCell('B44')->setValue($objRichText);
        $w_sheet->mergeCells('B44:J44');
        $w_sheet->getRowDimension('44')->setRowHeight(65);
        $w_sheet->getStyle("B44")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B44")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A46', 'Điều 2.');
        $w_sheet->getStyle('A46')
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('B46', 'Chế độ làm việc');
        $w_sheet->getStyle('B46')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A47', '2.1.');
        $w_sheet->getStyle('A47')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A47")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B47', 'Thời giờ làm việc: tổng giờ làm việc tiêu chuẩn 48 giờ/tuần. Thời điểm bắt đầu và kết thúc của ngày, tuần hoặc ca làm việc; số ngày làm việc trong tuần; thời điểm bắt đầu, thời điểm kết thúc nghỉ trong giờ làm việc sẽ tuân theo Nội quy lao động của Công ty.');
        $w_sheet->mergeCells('B47:J47');
        $w_sheet->getRowDimension('47')->setRowHeight(45);
        $w_sheet->getStyle("B47")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B47")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A48', '2.2.');
        $w_sheet->getStyle('A48')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A48")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B48', 'Người lao động đồng ý làm thêm giờ khi được yêu cầu vào từng thời điểm phụ thuộc vào nhu cầu công việc của Công ty.');
        $w_sheet->mergeCells('B48:J48');
        $w_sheet->getRowDimension('48')->setRowHeight(30);
        $w_sheet->getStyle("B48")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B48")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A50', 'Điều 3.');
        $w_sheet->getStyle('A50')
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('B50', 'Nghĩa vụ và quyền lợi của Người lao động');
        $w_sheet->getStyle('B50')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A51', '3.1.');
        $w_sheet->getStyle('A51')
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('B51', 'Quyền lợi');
        $w_sheet->getStyle('B51')
                ->getFont()
                ->setBold(true);

        $objRichText = new RichText();
        $objRichText->createText('- Mức lương: ');

        $employee_salary = Salary::where('employee_id', $employee->id)->whereDate('start_date', $contract->start_date)->orderBy('id', 'desc')->first();
        if (!$employee_salary) {
            return null;
        }
        $objBold = $objRichText->createTextRun(number_format($employee_salary->insurance_salary, 0, '.', ','));
        $objBold->getFont()->setBold(true);
        $objBold->getFont()->setName("Times New Roman");
        $objRichText->createText(' đồng/tháng.');
        $w_sheet->getCell('B52')->setValue($objRichText);

        $w_sheet->setCellValue('B53', '- Hình thức trả lương: Tiền mặt hoặc chuyển khoản ngân hàng.');

        if ('Phòng Kinh Doanh' == $contract->position->department->name) {
            $pay_date = 15;
        } else {
            $pay_date = 10;
        }
        $w_sheet->setCellValue('B54', '- Thời hạn trả lương: Người lao động được trả một lần vào ngày '. $pay_date . ' hàng tháng.');

        $w_sheet->setCellValue('B55', '- Phụ cấp trách nhiệm/ Chức vụ: Theo quy định của Công ty.');

        $w_sheet->setCellValue('B56', '- Phụ cấp nặng nhọc, độc hại, nguy hiểm: Theo quy định của Công ty.');

        $w_sheet->setCellValue('B57', '- Các khoản thu nhập khác: Theo quy định của Công ty.');

        $w_sheet->setCellValue('B58', '- Phụ cấp đắt đỏ: Theo quy định của Công ty.');

        $w_sheet->setCellValue('B59', '- Chế độ nghỉ ngơi (nghỉ hàng tuần, phép năm, lễ tết…): Theo Nội quy lao động của Công ty và quy định của pháp luật Việt Nam.');
        $w_sheet->mergeCells('B59:J59');
        $w_sheet->getRowDimension('59')->setRowHeight(30);
        $w_sheet->getStyle("B59")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B59")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('B60', '- Bảo hiểm Xã hội, Bảo hiểm Y tế và Bảo hiểm Thất nghiệp: Theo quy định của pháp luật Việt Nam và quy định của Công ty.');
        $w_sheet->mergeCells('B60:J60');
        $w_sheet->getRowDimension('60')->setRowHeight(30);
        $w_sheet->getStyle("B60")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B60")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('B61', '- Trang bị bảo hộ lao động cho người lao động: Theo Nội quy, quy định của công ty.');

        $w_sheet->setCellValue('A63', 'Những thỏa thuận khác:');
        $w_sheet->getStyle('A63')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A64', 'Chế độ nâng bậc, nâng lương: việc xem xét lương hàng năm sẽ tuân theo quy chế lương của công ty và kết quả đánh giá thực hiện công việc của cá nhân và công ty hàng năm. Lương sẽ được xem xét tùy vào quyết định của Chủ tịch Hội đồng quản trị. Quyết định nâng lương sẽ được xem là phụ lục Hợp đồng về việc điều chỉnh lương của Hợp đồng này.');
        $w_sheet->mergeCells('A64:J64');
        $w_sheet->getRowDimension('64')->setRowHeight(60);
        $w_sheet->getStyle("A64")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A64")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A65', 'Tiền thưởng: tùy thuộc vào kết quả kinh doanh của công ty, quyết định của Chủ tịch Hội đồng quản trị và theo Quy chế Thưởng của Công ty theo từng thời kỳ. Tiền thưởng được thanh toán chỉ khi nhân viên làm việc tại Công Ty đến thời điểm trả thưởng.');
        $w_sheet->mergeCells('A65:J65');
        $w_sheet->getRowDimension('65')->setRowHeight(45);
        $w_sheet->getStyle("A65")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A65")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('B66', '- Tiền ăn giữa ca: Theo quy định Công ty.');
        $w_sheet->getStyle('B66')
                ->getFont()
                ->setItalic(true);
        $w_sheet->setCellValue('B67', '- Tiền điện thoại: Theo quy định Công ty.');
        $w_sheet->getStyle('B67')
                ->getFont()
                ->setItalic(true);
        $w_sheet->setCellValue('B68', '- Phương tiện đi lại: Theo quy định Công ty.');
        $w_sheet->getStyle('B68')
                ->getFont()
                ->setItalic(true);

        $w_sheet->setCellValue('A69', '3.2.');
        $w_sheet->getStyle('A69')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('B69', 'Nghĩa vụ');
        $w_sheet->getStyle('B69')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A70', '3.2.1.');
        $w_sheet->getStyle("A70")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A70")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B70', 'Hoàn thành công việc và trách nhiệm theo như mô tả trong bản trách nhiệm công việc và trong bản thỏa thuận mục tiêu công việc phải hoàn thành hàng năm.');
        $w_sheet->mergeCells('B70:J70');
        $w_sheet->getRowDimension('70')->setRowHeight(30);
        $w_sheet->getStyle("B70")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B70")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A71', '3.2.2.');
        $w_sheet->getStyle("A71")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A71")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B71', 'Chấp hành lệnh điều hành sản xuất kinh doanh, Nội quy lao động, an toàn lao động và quy định pháp luật.');
        $w_sheet->mergeCells('B71:J71');
        $w_sheet->getRowDimension('71')->setRowHeight(30);
        $w_sheet->getStyle("B71")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B71")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A72', '3.2.3.');
        $w_sheet->getStyle("A72")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("A72")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B72', 'Giữ gìn, bảo quản tất cả thiết bị, công cụ và tư liệu làm việc được giao an toàn và luôn ở trong tình trạng tốt, không bị hư hỏng, tổn hại.');
        $w_sheet->mergeCells('B72:J72');
        $w_sheet->getRowDimension('72')->setRowHeight(30);
        $w_sheet->getStyle("B72")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B72")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('A73', '3.2.4.');
        $w_sheet->setCellValue('B73', 'Bồi thường vi phạm vật chất: theo Nội quy lao động của Công ty và quy định pháp luật.');

        $w_sheet->setCellValue('A74', '3.2.5.');
        $w_sheet->getStyle("A74")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $w_sheet->setCellValue('B74', 'Chịu trách nhiệm nộp thuế thu nhập cá nhân đối với thu nhập từ tiền lương, tiền công và thu nhập khác mà người lao động nhận được từ Công ty.');
        $w_sheet->mergeCells('B74:J74');
        $w_sheet->getRowDimension('74')->setRowHeight(30);
        $w_sheet->getStyle("B74")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B74")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A75', '3.2.6.');
        $w_sheet->setCellValue('B75', 'Nghĩa vụ bảo mật thông tin');

        $w_sheet->setCellValue('A76', 'a)');
        $w_sheet->getStyle("A76")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B76', 'Bất kỳ thông tin nào liên quan đến công việc kinh doanh, nhà cung cấp hay khách hàng của Công ty sẽ được xem là bí mật. Tất cả các thông tin mà Người lao động có được trong thời gian làm việc phải được giữ bí mật trong suốt thời gian làm việc và sau khi kết thúc thời gian làm việc cho Công ty. Người lao động đồng ý rằng tại mọi thời điểm mình sẽ không tiết lộ hay sử dụng Thông tin mật (như định nghĩa dưới đây và Thỏa Thuận Bảo Mật) cho mục đích cá nhân, không công bố, tiết lộ hay phổ biến Thông tin mật bằng bất kỳ phương tiện nào tới bất kỳ người, công ty hay thực thể nào với bất kỳ lý do hay mục đích nào ngoại trừ trường hợp cần thiết cho công việc kinh doanh của Công ty, mà không có sự chấp thuận trước bằng văn bản của Công ty.');
        $w_sheet->mergeCells('B76:J76');
        $w_sheet->getRowDimension('76')->setRowHeight(115);
        $w_sheet->getStyle("B76")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B76")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A77', 'b)');
        $w_sheet->getStyle("A77")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B77', 'Cho mục đích của Hợp đồng này, “Thông tin mật” có nghĩa là bất kỳ hay tất cả các thông tin chưa được Công ty hay các công ty liên kết trực tiếp hay gián tiếp của Công ty công bố, các thông tin này Người lao động có được hay được tiết lộ cho Người lao động trong quá trình làm hoặc phát sinh từ quá trình làm việc của Người lao động với Công ty, bao gồm nhưng không giới hạn ở các thông tin sau:');
        $w_sheet->mergeCells('B77:J77');
        $w_sheet->getRowDimension('77')->setRowHeight(70);
        $w_sheet->getStyle("B77")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B77")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B78', '+');
        $w_sheet->getStyle("B78")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('C78', 'Tất cả bí mật thương mại, công thức, định mức tạo thành sản phẩm; bất cứ thông tin kỹ thuật, kinh tế, tài chính, tiếp thị hay các thông tin khác như số liệu tài chính, thống kê kế toán, thông tin về khách hàng, sản phẩm mà các đối thủ cạnh tranh hay các công ty khác muốn có; các hoạt động, chiến lược kinh doanh; phát hiện khoa học, nghiên cứu phát triển hay phân tích khoa học, hợp đồng và giấy phép, hoạt động của Công ty trong quá khứ, hiện tại hoặc được đặt kế hoạch (tương lai) cùng các thông tin liên quan đến hồ sơ Công ty; các dữ liệu tồn kho, dữ liệu xuất – nhập; thông tin nguyên vật liệu và các loại thông tin khác thu thập hoặc có được từ quá trình hoạt động của Công ty; hệ thống kế toán, hệ thống kinh doanh.');
        $w_sheet->mergeCells('C78:J78');
        $w_sheet->getRowDimension('78')->setRowHeight(125);
        $w_sheet->getStyle("C78")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("C78")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B79', '+');
        $w_sheet->getStyle("B79")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('C79', 'Các thông tin liên quan đến việc kiện tụng và nguy cơ kiện tụng liên quan tới hoặc ảnh hưởng đến Công ty.' . PHP_EOL .'Các Thông tin mật này có thể tồn tại dưới bất kỳ hình thức nào, kể cả trên giấy tờ, bản in, thẻ, micro phim, hoặc microfiche, băng từ, đĩa mềm, thông tin trong các file máy tính.' . PHP_EOL . 'Người lao động đồng ý rằng các Thông tin mật nói trên là tài sản của Công ty. Khi kết thúc Hợp đồng, Người lao động đồng ý và có nghĩa vụ hoàn lại cho Công ty tất cả các tài liệu, hồ sơ hay các thông tin thuộc bất kỳ dạng nào về hoặc liên quan đến các Thông tin mật như định nghĩa trên đây và Thỏa Thuận Bảo Mật. Nếu Công ty có yêu cầu, Người lao động sẽ có trách nhiệm chuyển giao các Thông tin mật đó cho Công ty tại bất kỳ thời điểm nào trong thời hạn của Hợp đồng.');
        $w_sheet->mergeCells('C79:J79');
        $w_sheet->getRowDimension('79')->setRowHeight(150);
        $w_sheet->getStyle("C79")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("C79")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A80', 'c)');
        $w_sheet->getStyle("A80")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B80', 'Việc Người lao động vi phạm nghĩa vụ bảo mật theo quy định tại Hợp đồng này trong thời gian làm việc cho Công ty sẽ cấu thành sự vi phạm nghiêm trọng và là cơ sở cho việc xử lý kỷ luật Người lao động bằng hình thức sa thải theo quy định của pháp luật Việt Nam và Nội quy lao động của Công ty.');
        $w_sheet->mergeCells('B80:J80');
        $w_sheet->getRowDimension('80')->setRowHeight(60);
        $w_sheet->getStyle("B80")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B80")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A81', 'd)');
        $w_sheet->getStyle("A81")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B81', 'Ngoài các trách nhiệm bồi thường thiệt hại và trách nhiệm vật chất theo quy định của luật lao động Việt Nam và quy chế của Công ty, Người lao động, nếu tiết lộ các Thông tin mật theo quy định và Thỏa Thuận Bảo Mật, đồng ý và cam kết bảo đảm bồi thường cho Công ty bất kỳ thiệt hại nào bao gồm nhưng không giới hạn các chi phí pháp lý do Công ty gánh chịu phát sinh từ việc Người lao động vi phạm điều khoản về bảo mật quy định trong Hợp đồng này.');
        $w_sheet->mergeCells('B81:J81');
        $w_sheet->getRowDimension('81')->setRowHeight(90);
        $w_sheet->getStyle("B81")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B81")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A82', 'e)');
        $w_sheet->getStyle("A82")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B82', 'Trong mọi trường hợp, Thông tin mật không nhất thiết phải đóng dấu “mật” hay ‘Tuyệt mật” mới gọi là Thông tin mật.');
        $w_sheet->mergeCells('B82:J82');
        $w_sheet->getRowDimension('82')->setRowHeight(30);
        $w_sheet->getStyle("B82")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B82")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A83', '3.2.7.');
        $w_sheet->getStyle("A83")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B83', 'Nghĩa vụ không làm việc cho đối thủ cạnh tranh của Công ty: Trong suốt thời gian làm việc tại Công ty, Người lao động sẽ không được làm việc cho công ty khác là đối thủ cạnh tranh trừ trường hợp có sự đồng ý bằng văn bản của Công ty.');
        $w_sheet->mergeCells('B83:J83');
        $w_sheet->getRowDimension('83')->setRowHeight(45);
        $w_sheet->getStyle("B83")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B83")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A85', 'Điều 4.');
        $w_sheet->getStyle('A85')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('B85', 'Nghĩa vụ và quyền hạn của công ty');
        $w_sheet->getStyle('B85')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A86', '4.1.');
        $w_sheet->getStyle('A86')
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('B86', 'Nghĩa vụ ');
        $w_sheet->getStyle('B86')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A87', '4.2.1.');
        $w_sheet->getStyle("A87")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B87', 'Điều hành Người lao động hoàn thành công việc theo Hợp đồng. Người sử dụng lao động có quyền bố trí, điều chuyển, bổ nhiệm, bãi nhiệm trong phạm vi công việc theo quy định tại Điều 1 Hợp đồng này; tạm ngừng công việc của Người lao động theo nhu cầu kinh doanh và tuân theo quy định của Bộ luật Lao động Việt Nam.');
        $w_sheet->mergeCells('B87:J87');
        $w_sheet->getRowDimension('87')->setRowHeight(60);
        $w_sheet->getStyle("B87")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B87")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A88', '4.2.2.');
        $w_sheet->getStyle("A88")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B88', 'Tạm hoãn, chấm dứt Hợp đồng, kỷ luật Người lao động theo quy định của pháp luật, thỏa ước lao động tập thể và Nội quy lao động của Công ty.');
        $w_sheet->mergeCells('B88:J88');
        $w_sheet->getRowDimension('88')->setRowHeight(30);
        $w_sheet->getStyle("B88")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B88")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A89', '4.2.');
        $w_sheet->getStyle('A89')
                ->getFont()
                ->setBold(true);
        $w_sheet->setCellValue('B89', 'Quyền hạn');
        $w_sheet->getStyle('B89')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A90', '4.2.1.');
        $w_sheet->getStyle("A90")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B90', 'Điều hành Người lao động hoàn thành công việc theo Hợp đồng. Người sử dụng lao động có quyền bố trí, điều chuyển, bổ nhiệm, bãi nhiệm trong phạm vi công việc theo quy định tại Điều 1 Hợp đồng này; tạm ngừng công việc của Người lao động theo nhu cầu kinh doanh và tuân theo quy định của Bộ luật Lao động Việt Nam.');
        $w_sheet->mergeCells('B90:J90');
        $w_sheet->getRowDimension('90')->setRowHeight(60);
        $w_sheet->getStyle("B90")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B90")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A91', '4.2.2.');
        $w_sheet->getStyle("A91")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B91', 'Tạm hoãn, chấm dứt Hợp đồng, kỷ luật Người lao động theo quy định của pháp luật, thỏa ước lao động tập thể và Nội quy lao động của Công ty.');
        $w_sheet->mergeCells('B91:J91');
        $w_sheet->getRowDimension('91')->setRowHeight(30);
        $w_sheet->getStyle("B91")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B91")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A93', 'Điều 5.');
        $w_sheet->getStyle('A93')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('B93', 'Điều khoản thi hành');
        $w_sheet->getStyle('B93')
                ->getFont()
                ->setBold(true);

        $w_sheet->setCellValue('A94', '5.1.');
        $w_sheet->getStyle('A94')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A94")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B94', 'Những vấn đề về lao động không ghi trong Hợp đồng này thì áp dụng quy định của Nội quy lao động, Thỏa ước lao động tập thể của Công ty và pháp luật lao động hiện hành.');
        $w_sheet->mergeCells('B94:J94');
        $w_sheet->getRowDimension('94')->setRowHeight(45);
        $w_sheet->getStyle("B94")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B94")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A95', '5.2.');
        $w_sheet->getStyle('A95')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A95")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B95', 'Hợp đồng này được lập thành hai (02) bản có giá trị pháp lý ngang nhau, mỗi bên giữ một (01) bản và Hợp đồng này thay thế cho các hợp đồng được ký kết trước đây, có hiệu lực từ ngày ký.');
        $w_sheet->mergeCells('B95:J95');
        $w_sheet->getRowDimension('95')->setRowHeight(45);
        $w_sheet->getStyle("B95")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B95")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('A96', '5.3.');
        $w_sheet->getStyle('A96')
                ->getFont()
                ->setBold(true);
        $w_sheet->getStyle("A96")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->setCellValue('B96', 'Khi hai bên ký kết phụ lục Hợp đồng thì nội dung của phụ lục Hợp đồng cũng có giá trị như các nội dung của bản Hợp đồng này.');
        $w_sheet->mergeCells('B96:J96');
        $w_sheet->getRowDimension('96')->setRowHeight(30);
        $w_sheet->getStyle("B96")->getAlignment()->setWrapText(true);
        $w_sheet->getStyle("B96")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

        $w_sheet->getStyle("A99")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("A99:E99");
        $w_sheet->setCellValue('A99', 'NGƯỜI LAO ĐỘNG');       $w_sheet->getStyle("A99")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("F99")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("F99:J99");
        $w_sheet->setCellValue('F99', 'ĐẠI DIỆN NGƯỜI SỬ DỤNG LAO ĐỘNG');
        $w_sheet->getStyle("F99")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("A104")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("A104:E104");
        $w_sheet->setCellValue('A104', $employee->name);
        $w_sheet->getStyle("A104")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $w_sheet->getStyle("F104")
                ->getFont()
                ->setBold(true);
        $w_sheet->mergeCells("F104:J104");
        $w_sheet->setCellValue('F104', 'Tạ Văn Toại');
        $w_sheet->getStyle("F104")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);



        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'HĐLĐ-' . $employee->code . '-' . $employee->name . '.xlsx';
        $writer->save($file_name);

        return $file_name;
    }
}
