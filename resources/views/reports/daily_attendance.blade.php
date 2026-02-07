@php
    /** @var \Illuminate\Support\Collection<int, \App\Models\Attendance> $attendances */
    /** @var string $date */
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Attendance Report - {{ $date }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h1>Daily Attendance Report</h1>
    <p>Date: {{ $date }}</p>

    <table>
        <thead>
        <tr>
            <th>Employee</th>
            <th>Email</th>
            <th>Check in at</th>
            <th>Check out at</th>
        </tr>
        </thead>
        <tbody>
        @forelse($attendances as $attendance)
            <tr>
                <td>{{ $attendance->employee->names }}</td>
                <td>{{ $attendance->employee->email }}</td>
                <td>{{ $attendance->check_in_at }}</td>
                <td>{{ $attendance->check_out_at ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No attendance records for this date.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>

