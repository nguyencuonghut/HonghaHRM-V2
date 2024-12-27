<?php

namespace App\Imports;

use App\Models\Candidate;
use App\Models\CandidateSchool;
use App\Models\Commune;
use App\Models\Degree;
use App\Models\District;
use App\Models\Province;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class CandidateImport implements ToCollection
{
    private $rows = 0;
    private $duplicates = 0;
    private $duplicate_rows = [];
    private $invalid_province_name_row = 0;
    private $invalid_school_name_row = 0;

    public function collection(Collection $rows)
    {
        $i = 0;
        foreach ($rows as $row)
        {
            $i++;
            //Skip the heading row (first row)
            if ($i > 1) {
                //Check if Candidate is existed
                $data = Candidate::where('name', $row[2])
                                ->whereDate('date_of_birth', Carbon::createFromFormat('d/m/Y', $row[3]))
                                ->get();

                //Check if Address is valid
                $address_arr = explode(', ', $row[4]);
                $commune_name = $address_arr[0];
                $district_name   = $address_arr[1];
                $province_name   = $address_arr[2];
                $province = Province::where('name', $province_name)->first();
                if (null == $province) {
                    $this->invalid_province_name_row = $i;
                    break;
                }
                $district = District::where('name', $district_name)->where('province_id', $province->id)->first();
                if (null == $district) {
                    $this->invalid_province_name_row = $i;
                    break;
                }
                $commune = Commune::where('name', $commune_name)->where('district_id', $district->id)->first();
                if (null == $commune) {
                    $this->invalid_province_name_row = $i;
                    break;
                }

                //Check if School'name is valid
                $school = School::where('name', $row[11])->first();
                $degree = Degree::where('name', $row[12])->first();
                if (null == $school
                    && null == $degree) {
                    $this->invalid_school_name_row = $i;
                    break;
                }

                if ($data->count() == 0
                    && $commune
                    && $school) {
                    //Create Candidate
                    $candidate = new Candidate();
                    $candidate->name = $row[2];
                    $candidate->email = null;
                    $candidate->phone = $row[8];
                    $candidate->relative_phone = $row[18];
                    $candidate->date_of_birth = Carbon::createFromFormat('d/m/Y', $row[3]);
                    $candidate->cccd = $row[5];
                    $candidate->issued_date = Carbon::createFromFormat('d/m/Y', $row[6]);
                    $candidate->issued_by = $row[7];
                    $candidate->gender = $row[9];
                    $candidate->commune_id = $commune->id;
                    $candidate->creator_id = Auth::user()->id;
                    $candidate->experience = $row[16];
                    $candidate->note = $row[20];
                    $candidate->save();

                    //Create CandidateSchool
                    $candidate_school = new CandidateSchool();
                    $candidate_school->candidate_id = $candidate->id;
                    $candidate_school->school_id = $school->id;
                    $candidate_school->degree_id = $degree->id;
                    $candidate_school->major = $row[13];
                    $candidate_school->save();

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

    public function getInvalidSchoolNameRow(): int
    {
        return $this->invalid_school_name_row;
    }
}
