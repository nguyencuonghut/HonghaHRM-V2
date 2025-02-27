<?php

namespace App\Imports;

use App\Models\Commune;
use App\Models\Employee;
use App\Models\Department;
use App\Models\District;
use App\Models\Position;
use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class EmployeeImport implements ToCollection
{
    private $rows = 0;
    private $duplicates = 0;
    private $duplicate_rows = [];

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if Employee is existed
                $data = Employee::where('code', $row['2'])->get();
                if (0 == $data->count()) {
                    //Create Employee
                    $employee = new Employee();
                    $employee->code = $row[2];
                    $employee->name = $row[3];
                    $employee->img_path = 'dist/employee_img/' . $row[4];
                    $employee->private_email = $row[5];
                    $employee->company_email = $row[6];
                    $employee->phone = $row[7];
                    $employee->relative_phone = $row[8];
                    $employee->date_of_birth = Carbon::createFromFormat('d/m/Y', $row[9]);
                    $employee->cccd = $row[10];
                    $employee->issued_date = Carbon::createFromFormat('d/m/Y', $row[11]);
                    $employee->issued_by = $row[12];
                    $employee->gender = $row[13];
                    //Get the address information
                    $address_arr = explode(', ', $row[14]);
                    if (4 == count($address_arr)) {//have full address
                        $addr = $address_arr[0];
                        $commune_str = $address_arr[1];
                        $district_str = $address_arr[2];
                        $province_str = $address_arr[3];
                        $province = Province::where('name', $province_str)->first();
                        $district = District::where('name', $district_str)->where('province_id', $province->id)->first();
                        $commune = Commune::where('name', $commune_str)->where('district_id', $district->id)->first();
                    } else {
                        $addr = null;
                        $commune_str = $address_arr[0];
                        $district_str = $address_arr[1];
                        $province_str = $address_arr[2];
                        $province = Province::where('name', $province_str)->first();
                        $district = District::where('name', $district_str)->where('province_id', $province->id)->first();
                        $commune = Commune::where('name', $commune_str)->where('district_id', $district->id)->first();
                    }
                    $employee->address = $addr;
                    $employee->commune_id = $commune->id;

                    //Get the temporary address information
                    $temp_addr = '';
                    if ($row[15]) {
                        $temp_address_arr = explode(', ', $row[15]);
                        if (4 == count($temp_address_arr)) {//have full address
                            $temp_addr = $temp_address_arr[0];
                            $temp_commune_str = $temp_address_arr[1];
                            $temp_district_str = $temp_address_arr[2];
                            $temp_province_str = $temp_address_arr[3];
                            $temp_province = Province::where('name', $temp_province_str)->first();
                            $temp_district = District::where('name', $temp_district_str)->where('province_id', $temp_province->id)->first();
                            $temp_commune = Commune::where('name', $temp_commune_str)->where('district_id', $temp_district->id)->first();
                        } else {
                            $temp_commune_str = $temp_address_arr[0];
                            $temp_district_str = $temp_address_arr[1];
                            $temp_province_str = $temp_address_arr[2];
                            $temp_province = Province::where('name', $temp_province_str)->first();
                            $temp_district = District::where('name', $temp_district_str)->where('province_id', $temp_province->id)->first();
                            $temp_commune = Commune::where('name', $temp_commune_str)->where('district_id', $temp_district->id)->first();
                        }
                        $employee->temporary_commune_id = $temp_commune->id;
                    }
                    $employee->temporary_address = $temp_addr;
                    $employee->experience = $row[16];
                    $employee->marriage_status = $row[17];
                    $employee->bhxh = $row[18];
                    $employee->save();

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
}
