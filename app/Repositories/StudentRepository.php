<?php

namespace App\Repositories;

use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StudentRepository
{
    /**
     * Get student by ID.
     *
     * @param $studentId
     * @return ?Student
     */
    public function getStudentBiId($studentId): ?Student
    {
        return Student::findOrFail($studentId);
    }

    /**
     * Get students list repository function.
     *
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function listStudents(array $data): LengthAwarePaginator
    {
        return Student::query()
            ->when(
                $data['gender'] ?? null,
                fn($q) => $q->whereGender($data['gender'])
            )
            ->when(
                $search = $data['search'] ?? null,
                fn($q) => $q->where(
                    fn($q) => $q->where(DB::raw('CONCAT_WS(" ", first_name, middle_name, last_name)'), 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                )
            )
            ->paginate($data['per_page'] ?? 20, ['*'], 'page', $data['page'] ?? 1);
    }

    /**
     * Store student repository function.
     *
     * @param array $data
     * @return Student
     */
    public function storeStudent(array $data): Student
    {
        return Student::create($data);
    }
}
