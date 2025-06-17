<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Salary Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #343a40;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .header h2 {
            font-size: 30px;
            color: #212529;
        }

        .back-link {
            text-decoration: none;
            background-color: #6c757d;
            color: #fff;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #495057;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            text-align: left;
            padding: 14px 16px;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f1f3f5;
            font-weight: 600;
            color: #495057;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .btn-manage {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-manage:hover {
            background-color: #0056b3;
        }

        .yes {
            color: #28a745;
            font-weight: bold;
        }

        .no {
            color: #dc3545;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #dee2e6;
                border-radius: 10px;
                background: #fff;
                padding: 10px;
            }

            td {
                position: relative;
                padding-left: 50%;
                text-align: right;
            }

            td::before {
                position: absolute;
                top: 12px;
                left: 15px;
                width: 45%;
                font-weight: 600;
                color: #6c757d;
                white-space: nowrap;
            }

            td:nth-of-type(1)::before { content: "Employee"; }
            td:nth-of-type(2)::before { content: "Month"; }
            td:nth-of-type(3)::before { content: "Credited Salary"; }
            td:nth-of-type(4)::before { content: "Total Expenses"; }
            td:nth-of-type(5)::before { content: "Net Salary"; }
            td:nth-of-type(6)::before { content: "Is Credited"; }
            td:nth-of-type(7)::before { content: "Manage"; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <a href="{{ route('users.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h2>Salary Management</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Month</th>
                <th>Credited Salary</th>
                <th>Total Expenses</th>
                <th>Net Salary</th>
                <th>Is Credited</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($salaries as $month => $monthSalaries)
            @foreach ($monthSalaries as $salary)
                <tr>
                    <td>{{ $salary->user->name ?? 'N/A' }}</td>
                    <td>{{ $salary->month }}</td>

                    <td>
                        @if($salary->is_credited)
                            ₹{{ number_format($salary->credited_salary, 2) }}
                        @else
                            <span style="color: gray;">--</span>
                        @endif
                    </td>

                    <td>
                        @if($salary->is_credited)
                            ₹{{ number_format($salary->total_expenses, 2) }}
                        @else
                            <span style="color: gray;">--</span>
                        @endif
                    </td>

                    <td>
                        @if($salary->is_credited)
                            ₹{{ number_format($salary->credited_salary - $salary->total_expenses, 2) }}
                        @else
                            <span style="color: gray;">--</span>
                        @endif
                    </td>

                    <td>
                        @if($salary->is_credited)
                            <span class="yes">Yes</span>
                        @else
                            <span class="no">No</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('salary.manage', $salary->id) }}" class="btn-manage">
                            <i class="fas fa-cogs"></i> Manage
                        </a>
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
