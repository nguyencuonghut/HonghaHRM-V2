<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Division;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class DivisionImport implements ToCollection
{
    private $rows = 0;
    private $duplicates = 0;
    private $duplicate_rows = [];
    private $invalid_dept_name_row = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if Division with email is existed
                $data = Division::where('name', $row['1'])->get();

                //Check if department_id is valid
                $department = Department::where('name', $row['2'])->first();
                if (null == $department) {
                    $this->invalid_dept_name_row = $i;
                    break;
                }
                if ($data->count() == 0
                    && $department) {
                    //Create Division
                    $division = new Division();
                    $division->name = $row[1];
                    $division->department_id = $department->id;
                    $division->save();

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
}
