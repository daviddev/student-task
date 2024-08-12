<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\HttpErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\GenerateReportRequest;
use App\Http\Requests\Student\GetStudentsRequest;
use App\Http\Requests\Student\ImportTargetDataRequest;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StudentController extends Controller
{
    /**
     * StudentController constructor.
     *
     * @param StudentService $studentService
     */
    public function __construct(private readonly StudentService $studentService)
    {
        //
    }

    /**
     * Get students list controller function.
     *
     * @param GetStudentsRequest $request
     * @return JsonResponse
     */
    public function index(GetStudentsRequest $request): JsonResponse
    {
        $students = $this->studentService->listStudents($request->validated());

        return StudentResource::collection($students->items())
            ->additional([
                'success' => true,
                'total' => $students->total(),
                'page' => $students->currentPage(),
                'per_page' => $students->perPage(),
                'last_page' => $students->lastPage(),
            ])
            ->response();
    }

    /**
     * Store student controller function.
     *
     * @param StoreStudentRequest $request
     * @return JsonResponse
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = $this->studentService->storeStudent($request->validated());

        return (new StudentResource($student))
            ->additional([
                'success' => true,
                'message' => __('response.student.created'),
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Import student's target data controller function.
     *
     * @param ImportTargetDataRequest $request
     * @param Student $student
     * @return JsonResponse
     */
    public function importTargetData(ImportTargetDataRequest $request, Student $student): JsonResponse
    {
        $this->studentService->importTargetData($student, $request->validated());

        return $this->sendSuccessResponse(__('response.student.target-data-imported'));
    }

    /**
     * Generate student's report controller function.
     *
     * @param GenerateReportRequest $request
     * @param Student $student
     * @return BinaryFileResponse
     * @throws HttpErrorResponse
     */
    public function generateReport(GenerateReportRequest $request, Student $student): BinaryFileResponse
    {
        $fileName = $this->studentService->generateReport($student, $request->validated());

        return response()->download(public_path($fileName));
    }
}
