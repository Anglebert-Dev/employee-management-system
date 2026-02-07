<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Attendance Report - Anglebert Team</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4f46e5; padding: 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; letter-spacing: -0.025em;">Anglebert Team Analytics</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #1e293b; margin: 0 0 20px; font-size: 20px;">Daily Attendance Report</h2>
                            <p style="color: #64748b; font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
                                Hello, the attendance report for <strong>{{ $date }}</strong> has been generated successfully. 
                            </p>
                            
                            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; margin-bottom: 30px;">
                                <p style="color: #1e40af; font-size: 14px; margin: 0;">
                                    We have attached the records in both <strong>PDF</strong> (for viewing) and <strong>Excel</strong> (for synchronization) formats to this email.
                                </p>
                            </div>

                            <p style="color: #64748b; font-size: 14px; line-height: 1.6;">
                                Please review the attachments for full details on check-in and check-out times for all registered employees.
                            </p>

                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 30px 0;">
                            
                            <p style="color: #94a3b8; font-size: 13px;">
                                💡 Tip: You can also download these reports manually from the <a href="{{ config('app.url') }}/api/docs" style="color: #4f46e5; text-decoration: none; font-weight: 500;">Interactive Dashboard</a> at any time.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 30px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="color: #94a3b8; font-size: 12px; margin: 0;">
                                &copy; {{ date('Y') }} Anglebert Team. Industrial Grade Employee Management.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
