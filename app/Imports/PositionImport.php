<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class PositionImport implements ToCollection
{
    private $rows = 0;
    private $duplicates = 0;
    private $duplicate_rows = [];
    private $invalid_dept_name_row = 0;
    private $invalid_divi_name_row = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if Position with email is existed
                $data = Position::where('name', $row['1'])->get();

                //Check if department_id is valid
                $department = Department::where('name', $row['2'])->first();
                Log::error("department");
                if (null == $department) {
                    $this->invalid_dept_name_row = $i;
                    break;
                }
                Log::error("department");
                //Check if division_id is valid
                $division = Division::where('name', $row['3'])->first();
                if (null == $division) {
                    $this->invalid_divi_name_row = $i;
                    break;
                }
                Log::error("division");

                Log::error("data - " . $row);
                if ($data->count() == 0
                    && $department) {
                    //Create Position
                    $position = new Position();
                    $position->name = $row[1];
                    $position->department_id = $department->id;
                    if (null != $division) {
                        $position->division_id = $division->id;
                    }
                    $position->insurance_salary = $row[4];
                    $position->position_salary = $row[5];
                    $position->max_capacity_salary = $row[6];
                    $position->position_allowance = $row[7];
                    Log::error("create Position dep " . $department->id);
                    Log::error("create Position div " . $division->id);
                    $position->save();
                    Log::error("create Position done");

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

    public function getInvalidDeptNameRow(): int
    {
        return $this->invalid_dept_name_row;
    }

    public function getInvalidDiviNameRow(): int
    {
        return $this->invalid_divi_name_row;
    }
}
