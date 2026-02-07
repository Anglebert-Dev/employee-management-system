<?php

namespace App\Mail;

use App\Exports\DailyAttendanceExport;
use App\Models\Attendance;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class DailyAttendanceReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        protected string $date
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Attendance Report (' . $this->date . ') - Anglebert Team',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily_report',
            with: [
                'date' => $this->date,
            ],
        );
    }

    public function attachments(): array
    {
        $attendances = Attendance::with('employee')
            ->whereDate('check_in_at', $this->date)
            ->get();

        // Generate PDF
        $pdf = SnappyPdf::loadView('reports.daily_attendance', [
            'date' => $this->date,
            'attendances' => $attendances,
        ]);

        // Generate Excel as raw data
        $excelRaw = Excel::raw(new DailyAttendanceExport($this->date), \Maatwebsite\Excel\Excel::XLSX);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'attendance-' . $this->date . '.pdf')
                ->withMime('application/pdf'),
            Attachment::fromData(fn () => $excelRaw, 'attendance-' . $this->date . '.xlsx')
                ->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }
}
