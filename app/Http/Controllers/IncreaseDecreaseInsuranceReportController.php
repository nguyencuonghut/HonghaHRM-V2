<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\DecreaseInsurance;
use App\Models\DepartmentVice;
use App\Models\Employee;
use App\Models\IncreaseInsurance;
use App\Models\Insurance;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class IncreaseDecreaseInsuranceReportController extends Controller
{
    public function show()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        return view('report.increase_decrease_insurance.show', [
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function byMonth(Request $request)
    {
        $filter_month_year = explode('/', $request->month_of_year);
        $month = $filter_month_year[0];
        $year   = $filter_month_year[1];

        return view('report.increase_decrease_insurance.by_month',
                    [
                        'month' => $month,
                        'year' => $year,
                    ]);
    }

    public function increaseByMonthData($month, $year)
    {
        $data = IncreaseInsurance::with('work')
                                ->whereMonth('confirmed_month', $month)
                                ->whereYear('confirmed_month', $year)
                                ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->work->employee->code;
            })
            ->editColumn('name', function ($data) {
                return '<a href=' . route("employees.show", $data->work->employee->id) . '>' . $data->work->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                return $data->work->position->name;
            })
            ->editColumn('start_date', function ($data) {
                return date('d/m/Y', strtotime($data->work->start_date));
            })
            ->editColumn('confirmed_month', function ($data) {
                return date('m/Y', strtotime($data->confirmed_month));
            })
            ->editColumn('insurance_salary', function ($data) use ($month, $year){
                // Tính lương bhxh tại tháng này
                $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                if ($salary) {
                    return number_format($salary->insurance_salary, 0, '.', ',');
                } else {
                    return '';
                }
            })
            ->editColumn('bhxh_increase', function ($data) use ($month, $year){
                // Tính toán số tiền tăng cho 1- bhxh
                $insurance = Insurance::where('employee_id', $data->work->employee_id)
                                                        ->where('insurance_type_id', 1)
                                                        ->first();
                if ($insurance) {
                    $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                    if ($salary) {
                        $bhxh_increase = $salary->insurance_salary * $insurance->pay_rate / 100;
                        return number_format($bhxh_increase, 0, '.', ',');
                    } else {
                        return '';
                    }
                } else {
                    return 'Chưa khai báo BHXH';
                }
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    public function decreaseByMonthData($month, $year)
    {
        $data = DecreaseInsurance::with('work')
                                ->whereMonth('confirmed_month', $month)
                                ->whereYear('confirmed_month', $year)
                                ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                return $data->work->employee->code;
            })
            ->editColumn('name', function ($data) {
                return '<a href=' . route("employees.show", $data->work->employee->id) . '>' . $data->work->employee->name . '</a>' ;
            })
            ->editColumn('position', function ($data) {
                return $data->work->position->name;
            })
            ->editColumn('start_date', function ($data) {
                return date('d/m/Y', strtotime($data->work->start_date));
            })
            ->editColumn('confirmed_month', function ($data) {
                return date('m/Y', strtotime($data->confirmed_month));
            })
            ->editColumn('insurance_salary', function ($data) use ($month, $year){
                // Tính lương bhxh tại tháng này
                $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                if ($salary) {
                    return number_format($salary->insurance_salary, 0, '.', ',');
                } else {
                    return '';
                }
            })
            ->editColumn('bhxh_decrease', function ($data) use ($month, $year){
                // Tính toán số tiền giảm cho 1- bhxh
                $insurance = Insurance::where('employee_id', $data->work->employee_id)
                                                        ->where('insurance_type_id', 1)
                                                        ->first();
                if ($insurance) {
                    $salary = $this->getEmployeeSalaryByMonthYear($data->work->employee_id, $month, $year);
                    if ($salary) {
                        $bhxh_increase = $salary->insurance_salary * $insurance->pay_rate / 100;
                        return number_format($bhxh_increase, 0, '.', ',');
                    } else {
                        return '';
                    }
                } else {
                    return 'Chưa khai báo BHXH';
                }
            })
            ->rawColumns(['name'])
            ->make(true);
    }

    public function exportIncBhxh($month, $year)
    {
        $file_name = $this->createIncBhxhFile($month, $year);

        Alert::toast('Tải file thành công!!', 'success', 'top-right');
        return response()->download($file_name)->deleteFileAfterSend(true);
    }


    public function exportDecBhxh($month, $year)
    {
        $file_name = $this->createDecBhxhFile($month, $year);

        Alert::toast('Tải file thành công!!', 'success', 'top-right');
        return response()->download($file_name)->deleteFileAfterSend(true);
    }

    private function getEmployeeSalaryByMonthYear($employee_id, $month, $year)
    {
        // Tìm các Salary với trạng thái On
        $on_salary = Salary::where('employee_id', $employee_id)
                            ->where('status', 'On')
                            ->whereYear('start_date', '<=', $year)
                            ->whereMonth('start_date', '<=', $month)
                            ->first();
        if ($on_salary) {
            return $on_salary;
        } else {
            // Tìm các Salary với trạng thái Off
            $off_salaries = Salary::where('employee_id', $employee_id)
                                    ->where('status', 'Off')
                                    ->whereYear('start_date', '<=', $year)
                                    ->whereYear('end_date', '>=', $year)
                                    ->get();
            if ($off_salaries->count() > 1) {
                // Tiếp tục lọc theo tháng
            return Salary::where('employee_id', $employee_id)
                        ->where('status', 'Off')
                        ->whereYear('start_date', '<=', $year)
                        ->whereYear('end_date', '>=', $year)
                        ->whereMonth('start_date', '<=', $month)
                        ->whereMonth('end_date', '>=', $month)
                        ->first();
            } else {
                // Trả về luôn
                return Salary::where('employee_id', $employee_id)
                            ->where('status', 'Off')
                            ->whereYear('start_date', '<=', $year)
                            ->whereYear('end_date', '>=', $year)
                            ->first();
            }
        }
    }

    private function createIncBhxhFile($month, $year)
    {
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

        //Set sheet title
        $w_sheet->setTitle("Tăng BHXH");

        //Set title of report
        $w_sheet->setCellValue('C1', 'BÁO CÁO PHÁT SINH TĂNG BHXH THÁNG ' . $month . '-' . $year);
        $w_sheet->getStyle("C1")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);

        //Set column width and column name
        $w_sheet->getColumnDimension('A')->setWidth(6);
        $w_sheet->getStyle("A3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('A3', 'STT');

        $w_sheet->getColumnDimension('B')->setWidth(6);
        $w_sheet->getStyle("B3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('B3', 'MÃ');

        $w_sheet->getColumnDimension('C')->setWidth(30);
        $w_sheet->getStyle("C3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('C3', 'HỌ TÊN');

        $w_sheet->getColumnDimension('D')->setWidth(15);
        $w_sheet->getStyle("D3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('D3', 'SỐ BHXH');

        $w_sheet->getColumnDimension('E')->setWidth(15);
        $w_sheet->getStyle("E3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('E3', 'SỐ CCCD');

        $w_sheet->getColumnDimension('F')->setWidth(15);
        $w_sheet->getStyle("F3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('F3', 'NGÀY SINH');

        $w_sheet->getColumnDimension('G')->setWidth(15);
        $w_sheet->getStyle("G3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('G3', 'GIỚI TÍNH');

        $w_sheet->getColumnDimension('H')->setWidth(25);
        $w_sheet->getStyle("H3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('H3', 'VỊ TRÍ');

        $w_sheet->getColumnDimension('I')->setWidth(35);
        $w_sheet->getStyle("I3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('I3', 'ĐỊA CHỈ');

        $w_sheet->getColumnDimension('J')->setWidth(20);
        $w_sheet->getStyle("J3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('J3', 'SỐ ĐIỆN THOẠI');

        $w_sheet->getColumnDimension('K')->setWidth(25);
        $w_sheet->getStyle("K3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('K3', 'SỐ HỢP ĐỒNG');

        $w_sheet->getColumnDimension('L')->setWidth(50);
        $w_sheet->getStyle("L3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);

        $w_sheet->setCellValue('L3', 'NGÀY KÝ HĐ');

        $w_sheet->getColumnDimension('M')->setWidth(20);
        $w_sheet->getStyle("M3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('M3', 'LƯƠNG BHXH');

        $w_sheet->getColumnDimension('N')->setWidth(15);
        $w_sheet->getStyle("N3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('N3', 'TỶ LỆ ĐÓNG');

        $w_sheet->getColumnDimension('O')->setWidth(25);
        $w_sheet->getStyle("O3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);

        $w_sheet->setCellValue('O3', 'TIỀN TĂNG BHXH');

        //Set bold for column name
        $w_sheet->getStyle("A3:O3")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);

        //Get all increase bhxh by month, year
        $inc_insurances = IncreaseInsurance::whereMonth('confirmed_month', $month)
                                            ->whereYear('confirmed_month', $year)
                                            ->get();


        $index = 0;
        $start_row = 3;
        foreach ($inc_insurances as $inc_insurance) {
            $index += 1;
            //Write STT
            $w_sheet->getStyle('A'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('A' . ($start_row + $index), $index);
            //Write code
            $w_sheet->getStyle('B'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('B' . ($start_row + $index), $inc_insurance->work->employee->code);
            //Write name
            $w_sheet->getStyle('C'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('C' . ($start_row + $index), $inc_insurance->work->employee->name);
            //Write BHXH
            $w_sheet->getStyle('D'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('D' . ($start_row + $index), $inc_insurance->work->employee->bhxh);
            //Write CCCD
            $w_sheet->getStyle('E'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('E' . ($start_row + $index), $inc_insurance->work->employee->cccd);
            //Write date of birth
            $w_sheet->getStyle('F'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('F' . ($start_row + $index), date('d/m/Y', strtotime($inc_insurance->work->employee->date_of_birth)));
            //Write gender
            $w_sheet->getStyle('G'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('G' . ($start_row + $index), $inc_insurance->work->employee->gender);
            //Write company job
            $w_sheet->getStyle('H'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('H' . ($start_row + $index), $inc_insurance->work->position->name);
            //Write address
            $w_sheet->getStyle('I'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('I' . ($start_row + $index),
                                    $inc_insurance->work->employee->address
                                    . ', '
                                    .  $inc_insurance->work->employee->commune->name
                                    .', '
                                    .  $inc_insurance->work->employee->commune->district->name
                                    .', '
                                    . $inc_insurance->work->employee->commune->district->province->name);
            //Write phone
            $w_sheet->getStyle('J'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('J' . ($start_row + $index), $inc_insurance->work->employee->phone);
            //Write contract code
            $w_sheet->getStyle('K'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);

            $contract = Contract::where('code', $inc_insurance->work->contract_code)->first();

            $w_sheet->setCellValue('K' . ($start_row + $index), $contract->code);
            //Write contract start date/end date
            $w_sheet->getStyle('L'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('L' . ($start_row + $index), $inc_insurance->work->start_date);

            //Write insurance salary
            $w_sheet->getStyle('M'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $salary = $this->getEmployeeSalaryByMonthYear($inc_insurance->work->employee_id, $month, $year);
            $w_sheet->setCellValue('M' . ($start_row + $index), $salary->insurance_salary);

            //Write pay rate
            $w_sheet->getStyle('N'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $insurance = Insurance::where('employee_id', $inc_insurance->work->employee_id)
                                                    ->where('insurance_type_id', 1)
                                                    ->first();
            $w_sheet->setCellValue('N' . ($start_row + $index), $insurance->pay_rate . '%');

            //Write bhxh increase/decrease
            $w_sheet->getStyle('O'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            if ($insurance) {
                $employee_salary = $this->getEmployeeSalaryByMonthYear($inc_insurance->work->employee_id, $month, $year);
                if ($employee_salary) {
                    $bhxh_increase = $employee_salary->insurance_salary * $insurance->pay_rate / 100;
                    $w_sheet->setCellValue('O' . ($start_row + $index), $bhxh_increase);
                } else {
                    $w_sheet->setCellValue('O' . ($start_row + $index), 'Chưa khai báo lương');
                }
            } else {
                $w_sheet->setCellValue('O' . ($start_row + $index), 'Chưa khai báo BHXH');
            }
        }

        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'Báo cáo phát sinh tăng BHXH tháng ' . Carbon::now()->format('m-Y') . '.xlsx';

        $writer->save($file_name);

        return $file_name;
    }

    private function createDecBhxhFile($month, $year)
    {
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

        //Set sheet title
        $w_sheet->setTitle("Tăng BHXH");

        //Set title of report
        $w_sheet->setCellValue('C1', 'BÁO CÁO PHÁT SINH GIẢM BHXH THÁNG ' . $month . '-' . $year);
        $w_sheet->getStyle("C1")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);

        //Set column width and column name
        $w_sheet->getColumnDimension('A')->setWidth(6);
        $w_sheet->getStyle("A3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('A3', 'STT');

        $w_sheet->getColumnDimension('B')->setWidth(6);
        $w_sheet->getStyle("B3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('B3', 'MÃ');

        $w_sheet->getColumnDimension('C')->setWidth(30);
        $w_sheet->getStyle("C3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('C3', 'HỌ TÊN');

        $w_sheet->getColumnDimension('D')->setWidth(15);
        $w_sheet->getStyle("D3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('D3', 'SỐ BHXH');

        $w_sheet->getColumnDimension('E')->setWidth(15);
        $w_sheet->getStyle("E3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('E3', 'SỐ CCCD');

        $w_sheet->getColumnDimension('F')->setWidth(15);
        $w_sheet->getStyle("F3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('F3', 'NGÀY SINH');

        $w_sheet->getColumnDimension('G')->setWidth(15);
        $w_sheet->getStyle("G3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('G3', 'GIỚI TÍNH');

        $w_sheet->getColumnDimension('H')->setWidth(25);
        $w_sheet->getStyle("H3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('H3', 'VỊ TRÍ');

        $w_sheet->getColumnDimension('I')->setWidth(35);
        $w_sheet->getStyle("I3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('I3', 'ĐỊA CHỈ');

        $w_sheet->getColumnDimension('J')->setWidth(20);
        $w_sheet->getStyle("J3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('J3', 'SỐ ĐIỆN THOẠI');

        $w_sheet->getColumnDimension('K')->setWidth(25);
        $w_sheet->getStyle("K3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('K3', 'SỐ HỢP ĐỒNG');

        $w_sheet->getColumnDimension('L')->setWidth(50);
        $w_sheet->getStyle("L3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('L3', 'NGÀY NGHỈ');

        $w_sheet->getColumnDimension('M')->setWidth(20);
        $w_sheet->getStyle("M3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('M3', 'LƯƠNG BHXH');

        $w_sheet->getColumnDimension('N')->setWidth(15);
        $w_sheet->getStyle("N3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('N3', 'TỶ LỆ ĐÓNG');

        $w_sheet->getColumnDimension('O')->setWidth(25);
        $w_sheet->getStyle("O3")
                ->getBorders()
                ->getOutline()
                ->setBorderStyle(Border::BORDER_THIN);
        $w_sheet->setCellValue('O3', 'TIỀN GIẢM BHXH');

        //Set bold for column name
        $w_sheet->getStyle("A3:O3")
                    ->getFont()
                    ->setSize(13)
                    ->setBold(true);

        //Get all decrease bhxh by month, year
        $dec_insurances = DecreaseInsurance::whereMonth('confirmed_month', $month)
                                            ->whereYear('confirmed_month', $year)
                                            ->get();


        $index = 0;
        $start_row = 3;
        foreach ($dec_insurances as $dec_insurance) {
            $index += 1;
            //Write STT
            $w_sheet->getStyle('A'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('A' . ($start_row + $index), $index);
            //Write code
            $w_sheet->getStyle('B'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('B' . ($start_row + $index), $dec_insurance->work->employee->code);
            //Write name
            $w_sheet->getStyle('C'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('C' . ($start_row + $index), $dec_insurance->work->employee->name);
            //Write BHXH
            $w_sheet->getStyle('D'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('D' . ($start_row + $index), $dec_insurance->work->employee->bhxh);
            //Write CCCD
            $w_sheet->getStyle('E'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('E' . ($start_row + $index), $dec_insurance->work->employee->cccd);
            //Write date of birth
            $w_sheet->getStyle('F'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('F' . ($start_row + $index), date('d/m/Y', strtotime($dec_insurance->work->employee->date_of_birth)));
            //Write gender
            $w_sheet->getStyle('G'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('G' . ($start_row + $index), $dec_insurance->work->employee->gender);
            //Write company job
            $w_sheet->getStyle('H'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('H' . ($start_row + $index), $dec_insurance->work->position->name);
            //Write address
            $w_sheet->getStyle('I'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('I' . ($start_row + $index),
                                    $dec_insurance->work->employee->address
                                    . ', '
                                    .  $dec_insurance->work->employee->commune->name
                                    .', '
                                    .  $dec_insurance->work->employee->commune->district->name
                                    .', '
                                    . $dec_insurance->work->employee->commune->district->province->name);
            //Write phone
            $w_sheet->getStyle('J'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $w_sheet->setCellValue('J' . ($start_row + $index), $dec_insurance->work->employee->phone);
            //Write contract code
            $w_sheet->getStyle('K'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);

            $contract = Contract::where('code', $dec_insurance->work->contract_code)->first();

            $w_sheet->setCellValue('K' . ($start_row + $index), $contract->code);
            //Write contract start date/end date
            $w_sheet->getStyle('L'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
                $w_sheet->setCellValue('L' .($start_row + $index),
                                        $dec_insurance->work->end_date .
                                        ' (' . $dec_insurance->work->off_type->name .
                                        ' - số QĐ: ' .
                                        $dec_insurance->work->employee->code .
                                        '/' .
                                        date('Y', strtotime($dec_insurance->work->end_date)) .
                                        '/QĐ-HH' .
                                        ')');
            //Write insurance salary
            $w_sheet->getStyle('M'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $employee_salary = $this->getEmployeeSalaryByMonthYear($dec_insurance->work->employee_id, $month, $year);
            if ($employee_salary) {
                $w_sheet->setCellValue('M' . ($start_row + $index), $employee_salary->insurance_salary);
            } else {
                $w_sheet->setCellValue('M' . ($start_row + $index), 'Chưa khai báo lương');
            }
            //Write pay rate
            $w_sheet->getStyle('N'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            $insurance = Insurance::where('employee_id', $dec_insurance->work->employee_id)
                                                    ->where('insurance_type_id', 1)
                                                    ->first();
            if ($insurance) {
                $w_sheet->setCellValue('N' . ($start_row + $index), $insurance->pay_rate . '%');
            } else {
                $w_sheet->setCellValue('N' . ($start_row + $index), 'Chưa khai báo BHXH');
            }
            //Write bhxh increase/decrease
            $w_sheet->getStyle('O'. ($start_row + $index))
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_THIN);
            if ($insurance) {
                $salary = $this->getEmployeeSalaryByMonthYear($dec_insurance->work->employee_id, $month, $year);
                if ($salary) {
                    $bhxh_increase = $salary->insurance_salary * $insurance->pay_rate / 100;
                    $w_sheet->setCellValue('O' . ($start_row + $index), $bhxh_increase);
                } else {
                    $w_sheet->setCellValue('O' . ($start_row + $index), 'Chưa khai báo lương');
                }
            } else {
                $w_sheet->setCellValue('O' . ($start_row + $index), 'Chưa khai báo BHXH');
            }
        }

        //Save to file
        $writer = new Xlsx($spreadsheet);
        $file_name = 'Báo cáo phát sinh giảm BHXH tháng ' . Carbon::now()->format('m-Y') . '.xlsx';
        $writer->save($file_name);

        return $file_name;
    }
}
