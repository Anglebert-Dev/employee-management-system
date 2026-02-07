<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\DailyAttendanceReportMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyAttendanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aam:send-daily-report {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the daily attendance report (PDF & Excel) to all registered users.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') ?: now()->toDateString();
        $users = User::all();

        if ($users->isEmpty()) {
            $this->info('No users found to send the report to.');
            return;
        }

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new DailyAttendanceReportMail($date));
            $this->info("Report for {$date} queued for: {$user->email}");
        }

        $this->info('Operation completed successfully.');
    }
}
