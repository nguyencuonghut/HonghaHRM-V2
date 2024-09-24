<?php

namespace App\Imports;

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
                    $user->password = bcrypt(Str::random(8));
                    $user->save();

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
