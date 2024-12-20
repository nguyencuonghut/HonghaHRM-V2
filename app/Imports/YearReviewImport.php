<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Position;
use App\Models\YearReview;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class YearReviewImport implements ToCollection
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
                $employee = Employee::where('code', $row['2'])->first();
                if (null == $employee) {
                    $this->invalid_employee_name_row = $i;
                    break;
                }
                //Check if position_id is valid
                $position = Position::where('name', $row['4'])->first();
                if (null == $position) {
                    $this->invalid_position_name_row = $i;
                    break;
                }

                //Check for duplicate
                $data = YearReview::where('employee_id', $employee->id)
                            ->where('position_id', $position->id)
                            ->where('year', $row[5])
                            ->get();
                if (0 != $data->count()) {
                    //Count the duplicated rows
                    ++$this->duplicates;
                    array_push($this->duplicate_rows, $i);

                } else {
                    //Create YearReview
                    $year_review = new YearReview();
                    $year_review->employee_id = $employee->id;
                    $year_review->position_id = $position->id;
                    $year_review->year = $row[5];
                    $year_review->kpi_average = $row[6];
                    $year_review->result = $row[7];
                    $year_review->detail = $row[8];
                    $year_review->save();

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
