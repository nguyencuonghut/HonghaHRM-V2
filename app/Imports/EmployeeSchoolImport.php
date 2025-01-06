<?php

namespace App\Imports;

use App\Models\Degree;
use App\Models\Employee;
use App\Models\EmployeeSchool;
use App\Models\School;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeSchoolImport implements ToCollection
{
    private $rows = 0;
    private $invalid_employee_name_row = 0;
    private $invalid_school_name_row = 0;
    private $invalid_degree_name_row = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if Employee is valid
                $employee = Employee::where('code', $row[2])->where('name', $row[3])->first();
                if (null == $employee) {
                    $this->invalid_employee_name_row = $i;
                    break;
                }

                //Check if School is valid
                $school = School::where('name', $row[4])->first();
                if (null == $school) {
                    $this->invalid_school_name_row = $i;
                    break;
                }

                //Check if Degree is valid
                $degree = Degree::where('name', $row[5])->first();
                if (null == $degree) {
                    $this->invalid_degree_name_row = $i;
                    break;
                }

                //Create EmployeeSchool
                $employee_school = new EmployeeSchool();
                $employee_school->employee_id = $employee->id;
                $employee_school->school_id = $school->id;
                $employee_school->degree_id = $degree->id;
                $employee_school->major = $row[6];
                $employee_school->save();

                ++$this->rows;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getInvalidEmployeeNameRow(): int
    {
        return $this->invalid_employee_name_row;
    }

    public function getInvalidSchoolNameRow(): int
    {
        return $this->invalid_school_name_row;
    }

    public function getInvalidDegreeNameRow(): int
    {
        return $this->invalid_degree_name_row;
    }
}
