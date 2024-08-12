<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\TargetData;

class TargetDataRepository
{
    /**
     * Store session repository function.
     *
     * @param Student $student
     * @param array $data
     * @return TargetData
     */
    public function storeTargetData(Student $student, array $data): TargetData
    {
        return $student->targetData()->create($data);
    }
}
