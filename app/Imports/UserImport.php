<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
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
                //Check if User with email is existed
                $data = User::where('email', $row['2'])->get();
                if ($data->count() == 0) {
                    //Create User
                    $user = new User();
                    $user->name = $row[1];
                    $user->email = $row[2];
                    //Find Role
                    $role = Role::where('name', $row[3])->first();
                    if (null != $role) {
                        $user->role_id = $role->id;
                    }
                    $user->password = bcrypt(Str::random(8));
                    $user->status = $row[5];
                    $user->save();

                    //Create array of department_id
                    $department_names_str = explode(", ", $row[4]);
                    $deparment_ids_arr = [];
                    foreach ($department_names_str as $dept_name_str) {
                        $dept = Department::where('name', $dept_name_str)->first();
                        if (null != $dept) {
                            array_push($deparment_ids_arr, $dept->id);
                        }
                    }
                    //Create user_department pivot item
                    $user->departments()->attach($deparment_ids_arr);

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
