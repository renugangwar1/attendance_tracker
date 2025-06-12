<!DOCTYPE html>
<html>
<head>
    <title>Monthly Attendance & Salary Summary</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; }
        h2, h3 { color: #333; }
        p { margin: 5px 0; }
        hr { margin: 15px 0; }
    </style>
</head>
<body>
    <h2>Monthly Salary Summary for {{ $user->name }}</h2>

    @if(isset($monthly_summary) && count($monthly_summary))
        @foreach ($monthly_summary as $month => $summary)
            <h3>{{ \Carbon\Carbon::parse($month)->format('F Y') }}</h3>
            <p><strong>Present Days:</strong> {{ $summary['present'] }}</p>
            <p><strong>Overtime Days:</strong> {{ $summary['overtime'] }}</p>
            <p><strong>Calculated Salary:</strong> ₹{{ number_format($summary['salary'], 2) }}</p>
            <hr>
        @endforeach
    @else
        <p>No attendance records found for this user.</p>
    @endif

</body>
</html>
