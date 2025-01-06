<?php

namespace App\Imports;

use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Employee;
use App\Models\OffType;
use App\Models\OnType;
use App\Models\Position;
use App\Models\Work;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class WorkImport implements ToCollection
{
    private $rows = 0;
    private $invalid_contract_code_row = 0;
    private $invalid_employee_name_row = 0;
    private $invalid_position_name_row = 0;
    private $invalid_on_type_name_row = 0;
    private $invalid_off_type_name_row = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if Contract code is valid
                $contract = Contract::where('code', $row[2])->first();
                if (null == $contract) {
                    $this->invalid_contract_code_row = $i;
                    break;
                }

                //Check if Employee is valid
                $employee = Employee::where('code', $row[3])->where('name', $row[4])->first();
                if (null == $employee) {
                    $this->invalid_employee_name_row = $i;
                    break;
                }

                //Check if Position is valid
                $position = Position::where('name', $row[5])->first();
                if (null == $position) {
                    $this->invalid_position_name_row = $i;
                    break;
                }

                //Check if OffType is valid
                $off_type = null;
                if ($row[6]) {
                    $off_type = OffType::where('name', $row[6])->first();
                    if (null == $off_type) {
                        $this->invalid_off_type_name_row = $i;
                        break;
                    }
                }

                //Check if OnType is valid
                $on_type = OnType::where('name', $row[7])->first();
                if (null == $on_type) {
                    $this->invalid_on_type_name_row = $i;
                    break;
                }

                //Create Work
                $work = new Work();
                $work->contract_code = $row[2];
                $work->employee_id = $employee->id;
                $work->position_id = $position->id;
                if ($off_type) {
                    $work->off_type_id = $off_type->id;
                }
                $work->on_type_id = $on_type->id;
                $work->status = $row[8];
                $work->start_date = Carbon::createFromFormat('d/m/Y', $row[9]);
                if ($row[10]) {
                    $work->end_date = Carbon::createFromFormat('d/m/Y', $row[10]);
                }
                $work->off_reason = $row[11];
                $work->save();

                ++$this->rows;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getInvalidContractCodeRow(): int
    {
        return $this->invalid_contract_code_row;
    }

    public function getInvalidEmployeeNameRow(): int
    {
        return $this->invalid_employee_name_row;
    }

    public function getInvalidPositionNameRow(): int
    {
        return $this->invalid_position_name_row;
    }

    public function getInvalidOnTypeNameRow(): int
    {
        return $this->invalid_on_type_name_row;
    }

    public function getInvalidOffTypeNameRow(): int
    {
        return $this->invalid_off_type_name_row;
    }
}
