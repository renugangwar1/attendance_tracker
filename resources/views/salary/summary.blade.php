<!DOCTYPE html>
<html>
<head>
    <title>Monthly Attendance & Salary Summary</title>
   <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 20px;
    }

    .top-bar {
        background-color: #ffffff;
        padding: 15px 25px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .back-link {
        background-color: #007bff;
        color: #fff;
        padding: 8px 14px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .back-link:hover {
        background-color: #0056b3;
    }

    .month-block {
        background: #fff;
        padding: 25px;
        margin-bottom: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    h3 {
        color: #2c3e50;
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 20px;
        font-size: 15px;
    }

    th, td {
        padding: 12px;
        border: 1px solid #e1e1e1;
        text-align: left;
    }

    th {
        background-color: #f0f4f8;
        font-weight: 600;
    }

    tr:nth-child(even) {
        background-color: #fafafa;
    }

    .summary {
        font-size: 15px;
    }

    .summary p {
        margin: 6px 0;
        color: #333;
    }

    .summary p strong {
        color: #2a2a2a;
    }
</style>

</head>
<body>
<div class="top-bar">
   <a href="{{ route('users.index') }}" class="back-link">← Back to Dashboard</a>

  
</div>

@foreach ($monthly_summary as $month => $data)
    <div class="month-block">
        <h3>{{ \Carbon\Carbon::parse($month)->format('F Y') }}</h3>

       <table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['days'] as $day)
            @if ($day['status'] !== 'unmarked')
                <tr>
                    <td>{{ \Carbon\Carbon::parse($day['date'])->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($day['status']) }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>


        <div class="summary">
            <p><strong>Working Days:</strong> {{ $data['working_days'] }}</p>
            <p><strong>Per Day Salary:</strong> ₹{{ number_format($data['per_day_salary'], 2) }}</p>
            <p><strong>Present Days:</strong> {{ $data['present'] + $data['overtime'] }}</p>
            <p><strong>Absent Days:</strong> {{ $data['absent'] }}</p>
            <p><strong>Overtime Days:</strong> {{ $data['overtime'] }}</p>
            <p><strong>Total Salary (with overtime):</strong> ₹{{ number_format($data['salary'], 2) }}</p>
        </div>
    </div>
@endforeach

</body>
</html>
