<!DOCTYPE html>
<html>
<head>
    <title>Monthly Attendance & Salary Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f4f4f4;
        }
        .month-block {
            background: #fff;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background: #eee;
        }
        .summary {
            margin-top: 15px;
        }
        .summary p {
            margin: 5px 0;
        }
    </style>
</head>
<body>

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
