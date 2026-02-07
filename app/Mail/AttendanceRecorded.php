<?php

namespace App\Mail;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceRecorded extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Attendance $attendance,
        public string $type,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Attendance '.$this->type.' recorded - Anglebert Team')
            ->view('emails.attendance_recorded');
    }
}

