@php
    /** @var \App\Models\Attendance $attendance */
    /** @var string $type */
    $isCheckIn = $type === 'check-in';
    $color = $isCheckIn ? '#10b981' : '#ef4444';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Recorded - Anglebert Team</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #1e293b; padding: 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: -0.025em;">Anglebert Team Attendance</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #1e293b; margin: 0 0 20px; font-size: 20px;">Hello {{ $attendance->employee->names }},</h2>
                            <p style="color: #64748b; font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
                                We've successfully recorded your <strong>{{ $type }}</strong> for today. Here are the details of your attendance record:
                            </p>
                            
                            <!-- Status Badge -->
                            <div style="display: inline-block; padding: 8px 16px; border-radius: 9999px; background-color: {{ $color }}15; border: 1px solid {{ $color }}; color: {{ $color }}; font-weight: 600; font-size: 14px; margin-bottom: 30px; text-transform: uppercase;">
                                {{ str_replace('-', ' ', $type) }} SUCCESSFUL
                            </div>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9; border-radius: 12px; padding: 24px;">
                                <tr>
                                    <td style="padding-bottom: 16px;">
                                        <span style="color: #94a3b8; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Check In</span><br>
                                        <span style="color: #1e293b; font-size: 16px; font-weight: 500;">{{ \Carbon\Carbon::parse($attendance->check_in_at)->format('M d, Y - h:i A') }}</span>
                                    </td>
                                </tr>
                                @if($attendance->check_out_at)
                                <tr>
                                    <td>
                                        <span style="color: #94a3b8; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Check Out</span><br>
                                        <span style="color: #1e293b; font-size: 16px; font-weight: 500;">{{ \Carbon\Carbon::parse($attendance->check_out_at)->format('M d, Y - h:i A') }}</span>
                                    </td>
                                </tr>
                                @endif
                            </table>

                            <p style="color: #64748b; font-size: 14px; line-height: 1.6; margin: 30px 0 0;">
                                If this was not you, please contact the HR department immediately.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} Anglebert Team. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
