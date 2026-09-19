<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Approved Leaves Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        h1 { font-size: 18px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Approved Leaves Report</h1>
    <table>
        <thead><tr><th>Employee</th><th>Type</th><th>Start</th><th>End</th><th>Purpose</th></tr></thead>
        <tbody>
            @foreach($leaves as $leave)
                <tr>
                    <td>{{ $leave->employee->full_name ?? '--' }}</td>
                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ $leave->start_date?->format('Y-m-d') }}</td>
                    <td>{{ $leave->end_date?->format('Y-m-d') }}</td>
                    <td>{{ $leave->purpose ?? '--' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
