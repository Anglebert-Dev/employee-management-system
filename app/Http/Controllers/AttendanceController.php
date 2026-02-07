<?php

namespace App\Http\Controllers;

use App\Mail\AttendanceRecorded;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Attendance', description: 'Employee attendance management')]
class AttendanceController extends Controller
{
    #[OA\Get(
        path: '/api/attendance',
        summary: 'List attendance records',
        tags: ['Attendance'],
        security: [['bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of attendance records',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Attendance'))
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Attendance::with('employee')->latest();

        if ($request->filled('date')) {
            $query->whereDate('check_in_at', $request->string('date')->toString());
        }

        $attendances = $query->get();

        return response()->json([
            'message' => 'Attendance records retrieved successfully',
            'data' => $attendances,
            'status' => 200
        ]);
    }


    #[OA\Post(
        path: '/api/attendance/check-in',
        summary: 'Record employee check-in',
        tags: ['Attendance'],
        security: [['bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['employee_id'],
                properties: [
                    new OA\Property(property: 'employee_id', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Check-in recorded',
                content: new OA\JsonContent(ref: '#/components/schemas/Attendance')
            ),
            new OA\Response(response: 404, description: 'Employee not found'),
        ]
    )]
    public function checkIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
        ]);

        /** @var Employee $employee */
        $employee = Employee::findOrFail($validated['employee_id']);

        $attendance = Attendance::create([
            'employee_id' => $employee->id,
            'check_in_at' => now(),
        ]);

        Mail::to($employee->email)->queue(new AttendanceRecorded($attendance->fresh('employee'), 'check-in'));

        return response()->json([
            'message' => 'Check-in recorded successfully',
            'data' => $attendance->load('employee'),
            'status' => 201
        ], 201);
    }

    #[OA\Post(
        path: '/api/attendance/check-out',
        summary: 'Record employee check-out',
        tags: ['Attendance'],
        security: [['bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['employee_id'],
                properties: [
                    new OA\Property(property: 'employee_id', type: 'integer'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Check-out recorded',
                content: new OA\JsonContent(ref: '#/components/schemas/Attendance')
            ),
            new OA\Response(response: 404, description: 'Open attendance record not found'),
        ]
    )]
    public function checkOut(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
        ]);

        /** @var Employee $employee */
        $employee = Employee::findOrFail($validated['employee_id']);

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereNull('check_out_at')
            ->latest('check_in_at')
            ->firstOrFail();

        $attendance->update([
            'check_out_at' => now(),
        ]);

        Mail::to($employee->email)->queue(new AttendanceRecorded($attendance->fresh('employee'), 'check-out'));

        return response()->json([
            'message' => 'Check-out recorded successfully',
            'data' => $attendance->load('employee'),
            'status' => 200
        ]);
    }
}

