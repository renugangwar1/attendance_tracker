<!DOCTYPE html>
<html>
<head>
    <title>Salary PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; padding: 20px; }
        .container { background: #fff; padding: 20px; border: 1px solid #ccc; }
        h2 { text-align: center; }
        .summary p { margin: 8px 0; }
        .summary p strong { width: 200px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Monthly Salary Summary</h2>
        <p style="text-align: center;"><strong>Employee:</strong> {{ $user->name }}</p>
        <p style="text-align: center;"><strong>Month:</strong> {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>

        <div class="summary">
            <p><strong>Working Days:</strong> {{ $data['working_days'] }}</p>
            <p><strong>Per Day Salary:</strong> ₹{{ number_format($data['per_day_salary'], 2) }}</p>
            <p><strong>Present Days:</strong> {{ $data['present'] }}</p>
            <p><strong>Absent Days:</strong> {{ $data['absent'] }}</p>
            <p><strong>Overtime Days:</strong> {{ $data['overtime'] }}</p>
            <p><strong>Total Present (Present + Overtime):</strong> {{ $data['present'] + $data['overtime'] }}</p>
            <p><strong>Total Salary (with overtime):</strong> ₹{{ number_format($data['salary'], 2) }}</p>
        </div>

        <p style="text-align:center; margin-top:20px;">Note: Salary is based on 22 working days/month.</p>
    </div>
</body>
</html>
