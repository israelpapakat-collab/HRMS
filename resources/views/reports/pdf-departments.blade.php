<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Department Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        h1 { font-size: 18px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Department Report</h1>
    <table>
        <thead><tr><th>Department</th><th>Total Employees</th></tr></thead>
        <tbody>
            @foreach($departments as $dept)
                <tr><td>{{ $dept->department_name }}</td><td>{{ $dept->employees_count }}</td></tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
