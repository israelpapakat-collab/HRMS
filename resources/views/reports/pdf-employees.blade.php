<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employee Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        h1 { font-size: 18px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Employee Report</h1>
    <table>
        <thead>
            <tr><th>ID</th><th>Name</th><th>Gender</th><th>Department</th><th>Position</th><th>Salary</th></tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $emp->full_name }}</td>
                    <td>{{ $emp->gender ?? '--' }}</td>
                    <td>{{ $emp->department_name ?? '--' }}</td>
                    <td>{{ $emp->position_title ?? '--' }}</td>
                    <td>K{{ number_format($emp->annual_salary, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
