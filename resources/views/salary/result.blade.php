<!DOCTYPE html>
<html>
<head>
    <title>Monthly Salary Result</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 40px;
            background-color: #f9f9f9;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px 40px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .summary p {
            font-size: 16px;
            margin: 10px 0;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 5px;
        }

        .summary p strong {
            display: inline-block;
            width: 220px;
            color: #555;
        }

        .footer-note {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Monthly Salary Summary for {{ $user->name }}</h2>
    <form method="GET" action="{{ route('salary.calculate', $user->id) }}" style="text-align:center; margin-bottom: 20px;">
    <label for="month"><strong>Select Month:</strong></label>
    <input type="month" id="month" name="month" value="{{ request('month', now()->format('Y-m')) }}">
    <button type="submit" style="padding: 6px 12px; margin-left: 10px; background-color: #2c3e50; color: #fff; border: none; border-radius: 4px;">
        View
    </button>
</form>

    <div class="summary">
        <p><strong>Working Days:</strong> {{ $data['working_days'] }}</p>
        <p><strong>Per Day Salary:</strong> ₹{{ number_format($data['per_day_net_salary'], 2) }}</p>

      
        <p><strong>Present Days:</strong> {{ $data['present'] }}</p>
        <p><strong>Absent Days:</strong> {{ $data['absent'] }}</p>
        <p><strong>Overtime Days:</strong> {{ $data['overtime'] }}</p>
        <p><strong>Total Present (Present + Overtime):</strong> {{ $data['present'] + $data['overtime'] }}</p>
        <p><strong>Total Salary (with overtime):</strong> ₹{{ number_format($data['salary'], 2) }}</p>

        <div style="text-align:center; margin-top: 20px;">
    <form method="GET" action="{{ route('salary.download', $user->id) }}">
        <input type="hidden" name="month" value="{{ request('month', now()->format('Y-m')) }}">
        <button type="submit" style="padding: 8px 16px; background-color: #27ae60; color: white; border: none; border-radius: 4px;">
            Download PDF
        </button>
    </form>
</div>

    </div>

    <div class="footer-note">
        Note: Salary is calculated based on fixed 22 working days/month and actual attendance.
    </div>
</div>
<div style="text-align:center; margin-top: 10px;">
    <a href="{{ route('users.index') }}" 
       style="padding: 8px 16px; background-color: #2980b9; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">
        ← Back to Dashboard
    </a>
</div>
</body>
</html>
