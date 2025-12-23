<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>Appointment Confirmation</title>
    </head>
    <body style="margin:0;background:#f4f6f8;font-family:Arial,Helvetica,sans-serif;color:#111;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="padding:20px;">
                    <table width="600" cellpadding="0" cellspacing="0" role="presentation" style="background:#fff;border-radius:6px;overflow:hidden;">
                        <tr>
                            <td style="background:#0b5cff;color:#fff;padding:14px 18px;font-weight:700;">Appointment Confirmed</td>
                        </tr>
                        <tr>
                            <td style="padding:18px;">
                                <p style="margin:0 0 8px;">Hello,</p>
                                <p style="margin:0 0 12px;">Your appointment is confirmed. Details below:</p>

                                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="font-size:14px;color:#1f2937;">
                                    <tr>
                                        <td style="padding:6px 0;"><strong>Number:</strong> {{ $appointmentId }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;"><strong>When:</strong> {{ $startsAt }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;"><strong>Doctor:</strong> {{ $doctor->name ?? 'TBA' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:6px 0;"><strong>Location:</strong> {{ $clinic->name ?? '' }}{{ $clinic->address ? ' — '.$clinic->address : '' }}</td>
                                    </tr>
                                </table>

                                <p style="margin:12px 0 0;font-size:13px;color:#475569;">Please arrive 15 minutes early. Contact the clinic to cancel or reschedule.</p>
                                <p style="margin:14px 0 0;font-size:13px;color:#64748b;">Thank you — {{ $clinic->name ?? 'the clinic' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px 18px;font-size:12px;color:#94a3b8;background:#f8fafc;text-align:center;">Automated message — do not reply</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
