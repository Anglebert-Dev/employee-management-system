<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Anglebert Team Employee Manager API',
    description: 'Advanced employee management system (Anglebert Team) featuring Sanctum authentication, attendance tracking, and reporting.',

)]

#[OA\SecurityScheme(

    securityScheme: 'bearer',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
)]
#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]

#[OA\Schema(
    schema: 'Employee',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'names', type: 'string', example: 'John Doe'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
        new OA\Property(property: 'employee_identifier', type: 'string', example: 'EMP-001'),
        new OA\Property(property: 'phone_number', type: 'string', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]

#[OA\Schema(
    schema: 'Attendance',

    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'employee_id', type: 'integer', example: 1),
        new OA\Property(property: 'check_in_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'check_out_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
class OpenApi
{
}

