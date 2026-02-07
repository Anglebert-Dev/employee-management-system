<?php

namespace App\Http\Controllers;

use App\Exports\DailyAttendanceExport;
use App\Mail\DailyAttendanceReportMail;
use App\Models\Attendance;

use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Reports', description: 'Attendance reports')]
class AttendanceReportController extends Controller
{
    #[OA\Get(
        path: '/api/reports/attendance/daily/pdf',
        summary: 'Download daily attendance report as PDF',
        tags: ['Reports'],
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
                description: 'PDF file'
            ),
        ]
    )]
    public function dailyPdf(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $attendances = Attendance::with('employee')
            ->whereDate('check_in_at', $date)
            ->get();

        $pdf = SnappyPdf::loadView('reports.daily_attendance', [
            'date' => $date,
            'attendances' => $attendances,
        ]);


        $filename = Str::of('attendance-'.$date.'.pdf')->replace(' ', '_')->toString();

        // Automatically send to authenticated user
        if ($user = auth()->user()) {
            Mail::to($user->email)->queue(new DailyAttendanceReportMail($date));
        }

        return $pdf->download($filename);

    }

    #[OA\Get(
        path: '/api/reports/attendance/daily/excel',
        summary: 'Download daily attendance report as Excel',
        tags: ['Reports'],
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
                description: 'Excel file'
            ),
        ]
    )]
    public function dailyExcel(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $filename = 'attendance-'.$date.'.xlsx';

        // Automatically send to authenticated user
        if ($user = auth()->user()) {
            Mail::to($user->email)->queue(new DailyAttendanceReportMail($date));
        }

        return Excel::download(new DailyAttendanceExport($date), $filename);

    }

    #[OA\Post(
        path: '/api/reports/attendance/daily/email',
        summary: 'Send daily attendance report (PDF & Excel) to your email',
        tags: ['Reports'],
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
                description: 'Report queued for delivery',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The report has been queued for delivery to your email.')
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function sendEmailReport(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        Mail::to($user->email)->queue(new DailyAttendanceReportMail($date));

        return response()->json([
            'message' => "The daily attendance report for {$date} has been queued for delivery to: {$user->email}"
        ]);
    }
}


