<?php

namespace App\Imports;

use App\Models\Commune;
use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CommuneImport implements ToCollection
{
    private $rows = 0;
    private $duplicates = 0;
    private $duplicate_rows = [];
    private $invalid_province_name_row = 0;
    private $invalid_district_name_row = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if Commune is existed
                $data = Commune::where('name', $row['1'])->get();

                //Check if Province'name is valid
                $province = Province::where('name', $row['3'])->first();
                if (null == $province) {
                    $this->invalid_province_name_row = $i;
                    break;
                }
                //Check if District'name is valid
                $district = District::where('province_id', $province->id)->where('name', $row['2'])->first();
                if (null == $district) {
                    $this->invalid_district_name_row = $i;
                    break;
                }

                if ($data->count() == 0
                    && $province
                    && $district) {
                    //Create Commune
                    $commune = new Commune();
                    $commune->name = $row[1];
                    $commune->district_id = $district->id;
                    $commune->save();

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

    public function getInvalidProvinceNameRow(): int
    {
        return $this->invalid_province_name_row;
    }

    public function getInvalidDistrictNameRow(): int
    {
        return $this->invalid_district_name_row;
    }
}
