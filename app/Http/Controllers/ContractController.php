<?php

namespace App\Http\Controllers;

use App\Http\Requests\OffContractRequest;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Employee;
use App\Models\Position;
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
}
