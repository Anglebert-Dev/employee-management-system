<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyAttendanceExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected string $date,
    ) {
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function collection(): Collection
    {
        return Attendance::with('employee')
            ->whereDate('check_in_at', $this->date)
            ->get()
            ->map(function (Attendance $attendance): array {
                return [
                    'employee' => $attendance->employee->names,
                    'email' => $attendance->employee->email,
                    'check_in_at' => $attendance->check_in_at,
                    'check_out_at' => $attendance->check_out_at,
                ];
            });
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'Employee',
            'Email',
            'Check in at',
            'Check out at',
        ];
    }
}

