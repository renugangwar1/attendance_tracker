<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            color: #fff;
            margin-bottom: 30px;
        }

        .sidebar a,
        .sidebar form button {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px 15px;
            background-color: #495057;
            border-radius: 6px;
            text-decoration: none;
            color: white;
            font-size: 15px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover,
        .sidebar form button:hover {
            background-color: #6c757d;
        }

        .sidebar i {
            margin-right: 10px;
        }

        .main-content {
            flex-grow: 1;
            padding: 40px;
        }

        .main-content h2 {
            margin-bottom: 20px;
            color: #343a40;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            max-width: 600px;
        }

        .card h3 {
            color: #444;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 16px;
            color: #666;
        }

        .alert {
            margin-top: 10px;
            color: green;
            font-weight: 500;
        }

    </style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="{{ route('attendance.calendar', auth()->user()->id) }}"><i class="fas fa-calendar-check"></i> Mark Attendance</a>
        <a href="{{ route('salary.calculate', auth()->user()->id) }}"><i class="fas fa-wallet"></i> Monthly Total</a>
        <a href="{{ route('salary.summary', auth()->user()->id) }}"><i class="fas fa-file-invoice-dollar"></i> Full Summary</a>
        <a href="{{ route('users.create') }}"><i class="fas fa-user-cog"></i> Setup</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Welcome, {{ auth()->user()->name }}</h2>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <div class="card">
            <h3>Account Overview</h3>
            <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
            {{-- You may conditionally show salary like below --}}
            @if(auth()->user()->is_admin) 
                <p><strong>Monthly Salary:</strong> ₹{{ number_format(auth()->user()->detail->monthly_salary, 2) }}</p>
            @endif
        </div>
    </div>

</div>

</body>
</html>
