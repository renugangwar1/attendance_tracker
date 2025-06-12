<!DOCTYPE html>
<html>
<head>
    <title>User List - Attendance System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        h2 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            color: white;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .btn-info {
            background-color: #17a2b8;
        }
        .btn-success {
            background-color: #28a745;
        }
    </style>
</head>
<body>

    <h2>User List</h2>

    <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>

    @if($users->count())
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Monthly Salary (₹)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ number_format($user->monthly_salary, 2) }}</td>
                    <td>
                       <a href="{{ route('attendance.calendar', $user->id) }}" class="btn btn-info">Mark Attendance</a>
    <a href="{{ route('salary.calculate', $user->id) }}" class="btn btn-success">Monthly Total</a>
    <a href="{{ route('salary.summary', $user->id) }}" class="btn btn-primary">Full Summary</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No users found.</p>
    @endif

</body>
</html>
