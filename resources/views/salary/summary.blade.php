<!DOCTYPE html>
<html>
<head>
    <title>Monthly Attendance & Salary Summary</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
        }

        .top-bar {
            background-color: #ffffff;
            padding: 15px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .back-link {
            background-color: #2563eb;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }

        .back-link:hover {
            background-color: #1d4ed8;
        }

       .card-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* Force 4 cards in a row */
    gap: 24px;
}

        .month-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .month-card:hover {
            transform: translateY(-5px);
        }

        .month-card h3 {
            color: #0f172a;
            font-size: 20px;
            margin-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            color: #334155;
        }

        td {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
        }

        tr:nth-child(even) td {
            background-color: #f9fafb;
        }

        .summary {
            font-size: 14px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }

        .summary p {
            margin: 6px 0;
            color: #374151;
        }

        .summary p strong {
            color: #1f2937;
        }

@media (max-width: 1200px) {
    .card-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 900px) {
    .card-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .card-container {
        grid-template-columns: 1fr;
    }
}
    </style>
</head>
<body>
    <div class="top-bar">
        <a href="{{ route('users.index') }}" class="back-link">← Back to Dashboard</a>
    </div>

    <div class="card-container">
        @foreach ($monthly_summary as $month => $data)
            <div class="month-card">
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
                    <p><strong>Total Salary:</strong> ₹{{ number_format($data['salary'], 2) }}</p>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
