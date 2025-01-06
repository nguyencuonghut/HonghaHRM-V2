<?php

namespace App\Imports;

use App\Models\Contract;
use App\Models\ContractType;
use App\Models\Employee;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ContractImport implements ToCollection
{
    private $rows = 0;
    private $duplicates = 0;
    private $duplicate_rows = [];
    private $invalid_employee_name_row = 0;
    private $invalid_position_name_row = 0;
    private $invalid_contract_type_name_row = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if Contract is existed
                $data = Contract::where('code', $row[2])->get();

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
                //Check if Contract type is valid
                $contract_type = ContractType::where('name', $row[6])->first();
                if (null == $contract_type) {
                    $this->invalid_contract_type_name_row = $i;
                    break;
                }

                if ($data->count() == 0
                    && $employee
                    && $position
                    && $contract_type) {
                    //Create Contract
                    $contract = new Contract();
                    $contract->code = $row[2];
                    $contract->employee_id = $employee->id;
                    $contract->position_id = $position->id;
                    $contract->contract_type_id = $contract_type->id;
                    if ($row[7]) {
                        $contract->file_path = 'dist/employee_contract/' . $row[7];
                    }
                    $contract->status = $row[8];
                    $contract->start_date = Carbon::createFromFormat('d/m/Y', $row[9]);
                    if ($row[10]) {
                        $contract->end_date = Carbon::createFromFormat('d/m/Y', $row[10]);
                    }
                    if ($row[11]) {
                        $contract->request_terminate_date = Carbon::createFromFormat('d/m/Y', $row[11]);
                    }
                    $contract->created_type = $row[12];
                    $contract->save();

                    ++$this->rows;
                } else {
                    //Count the duplicated rows
                    ++$this->duplicates;
                    array_push($this->duplicate_rows, $i);
                }
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getDuplicateCount(): int
    {
        return $this->duplicates;
    }

    public function getDuplicateRows(): array
    {
        return $this->duplicate_rows;
    }

    public function getInvalidEmployeeNameRow(): int
    {
        return $this->invalid_employee_name_row;
    }

    public function getInvalidPositionNameRow(): int
    {
        return $this->invalid_position_name_row;
    }
    public function getInvalidContractTypeNameRow(): int
    {
        return $this->invalid_contract_type_name_row;
    }
}
