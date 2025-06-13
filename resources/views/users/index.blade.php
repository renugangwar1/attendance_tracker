<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Attendance System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 220px;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
        }

        .sidebar h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .sidebar a,
        .sidebar form button {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            text-align: center;
        }

        .sidebar a.btn-info { background-color: #17a2b8; }
        .sidebar a.btn-success { background-color: #28a745; }
        .sidebar a.btn-primary { background-color: #007bff; }
        .sidebar a.btn-warning { background-color: #ffc107; color: black; }
        .sidebar form button { background-color: #dc3545; border: none; cursor: pointer; }

        .main-content {
            flex-grow: 1;
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
    </style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Menu</h3>
        <a href="{{ route('attendance.calendar', auth()->user()->id) }}" class="btn btn-info">Mark Attendance</a>
        <a href="{{ route('salary.calculate', auth()->user()->id) }}" class="btn btn-success">Monthly Total</a>
        <a href="{{ route('salary.summary', auth()->user()->id) }}" class="btn btn-primary">Full Summary</a>
        <a href="{{ route('users.create') }}" class="btn btn-warning">Setup</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Welcome, {{ auth()->user()->name }}</h2>

        @if(session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Monthly Salary (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ auth()->user()->name }}</td>
                    <td>{{ number_format(auth()->user()->monthly_salary, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
