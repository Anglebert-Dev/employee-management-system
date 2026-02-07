<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Employees', description: 'Employee management')]
class EmployeeController extends Controller
{
    #[OA\Get(
        path: '/api/employees',
        summary: 'List employees',
        tags: ['Employees'],
        security: [['bearer' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of employees',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Employee'))
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $employees = Employee::query()->latest()->get();

        return response()->json([
            'message' => 'Employees retrieved successfully',
            'data' => $employees,
            'status' => 200
        ]);
    }


    #[OA\Post(
        path: '/api/employees',
        summary: 'Create a new employee',
        tags: ['Employees'],
        security: [['bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['names', 'email', 'employee_identifier'],
                properties: [
                    new OA\Property(property: 'names', type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'employee_identifier', type: 'string'),
                    new OA\Property(property: 'phone_number', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Employee created',
                content: new OA\JsonContent(ref: '#/components/schemas/Employee')
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'names' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:employees,email'],
            'employee_identifier' => ['required', 'string', 'max:255', 'unique:employees,employee_identifier'],
            'phone_number' => ['nullable', 'string', 'max:50'],
        ]);

        $employee = Employee::create($validated);

        return response()->json([
            'message' => 'Employee created successfully',
            'data' => $employee,
            'status' => 201
        ], 201);
    }

    #[OA\Get(
        path: '/api/employees/{id}',
        summary: 'Get a single employee',
        tags: ['Employees'],
        security: [['bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Employee details',
                content: new OA\JsonContent(ref: '#/components/schemas/Employee')
            ),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(Employee $employee): JsonResponse
    {
        return response()->json([
            'message' => 'Employee details retrieved successfully',
            'data' => $employee,
            'status' => 200
        ]);
    }

    #[OA\Put(
        path: '/api/employees/{id}',
        summary: 'Update an employee',
        tags: ['Employees'],
        security: [['bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'names', type: 'string'),
                    new OA\Property(property: 'email', type: 'string', format: 'email'),
                    new OA\Property(property: 'employee_identifier', type: 'string'),
                    new OA\Property(property: 'phone_number', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Employee updated',
                content: new OA\JsonContent(ref: '#/components/schemas/Employee')
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(Request $request, Employee $employee): JsonResponse
    {
        $validated = $request->validate([
            'names' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:employees,email,'.$employee->id],
            'employee_identifier' => ['sometimes', 'required', 'string', 'max:255', 'unique:employees,employee_identifier,'.$employee->id],
            'phone_number' => ['nullable', 'string', 'max:50'],
        ]);

        $employee->update($validated);

        return response()->json([
            'message' => 'Employee updated successfully',
            'data' => $employee,
            'status' => 200
        ]);
    }

    #[OA\Delete(
        path: '/api/employees/{id}',
        summary: 'Delete an employee',
        tags: ['Employees'],
        security: [['bearer' => []]],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Deleted'),
        ]
    )]
    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully',
            'status' => 200
        ]);
    }
}

