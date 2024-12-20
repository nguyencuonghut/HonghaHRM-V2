<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Kpi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class KpiImport implements ToCollection
{
    private $rows = 0;
    private $duplicates = 0;
    private $duplicate_rows = [];
    private $invalid_employee_name_row = 0;
    private $invalid_position_name_row = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if employee_id is valid
                $employee = Employee::where('code', $row['1'])->first();
                if (null == $employee) {
                    $this->invalid_employee_name_row = $i;
                    break;
                }
                //Check if position_id is valid
                $position = Position::where('name', $row['3'])->first();
                if (null == $position) {
                    $this->invalid_position_name_row = $i;
                    break;
                }

                //Check for duplicate
                $data = Kpi::where('employee_id', $employee->id)
                            ->where('position_id', $position->id)
                            ->where('year', $row[5])
                            ->where('month', $row[6])
                            ->get();
                if (0 != $data->count()) {
                    //Count the duplicated rows
                    ++$this->duplicates;
                    array_push($this->duplicate_rows, $i);

                } else {
                    //Create Kpi
                    $kpi = new Kpi();
                    $kpi->employee_id = $employee->id;
                    $kpi->position_id = $position->id;
                    $kpi->year = $row[5];
                    $kpi->month = $row[6];
                    $kpi->score = $row[7];
                    $kpi->save();

                    ++$this->rows;
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
}
