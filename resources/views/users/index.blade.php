<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #212529;
            padding: 30px 20px;
            color: #fff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            margin-bottom: 40px;
            font-size: 22px;
            font-weight: 600;
            text-align: center;
            color: #f8f9fa;
        }

        .sidebar a,
        .sidebar form button {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px 15px;
            background-color: #343a40;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            color: #e9ecef;
            font-size: 15px;
            transition: all 0.3s ease;
            width: 100%;
            cursor: pointer;
        }

        .sidebar a:hover,
        .sidebar form button:hover {
            background-color: #495057;
            color: #ffffff;
        }

        .sidebar i {
            margin-right: 12px;
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 40px;
            background-color: #f1f3f5;
        }

        .main-content h2 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            color: #212529;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            max-width: 600px;
        }

        .card h3 {
            font-size: 22px;
            color: #495057;
            margin-bottom: 20px;
        }

        .card p {
            font-size: 16px;
            color: #495057;
            margin-bottom: 12px;
        }

        .alert {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-left: 6px solid #28a745;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 500;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                justify-content: space-around;
                padding: 20px;
            }

            .sidebar a,
            .sidebar form button {
                margin: 10px;
                width: 120px;
                justify-content: center;
            }

            .main-content {
                padding: 20px;
            }

            .card {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="{{ route('attendance.calendar', auth()->user()->id) }}"><i class="fas fa-calendar-check"></i>Attendance</a>
        <a href="{{ route('salary.calculate', auth()->user()->id) }}"><i class="fas fa-wallet"></i>Monthly Total</a>
        <a href="{{ route('salary.summary', auth()->user()->id) }}"><i class="fas fa-file-invoice-dollar"></i>Summary</a>
        <a href="{{ route('users.create') }}"><i class="fas fa-user-cog"></i>Setup</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"><i class="fas fa-sign-out-alt"></i>Logout</button>
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
            @if(auth()->user()->is_admin)
                <p><strong>Monthly Salary:</strong> ₹{{ number_format(auth()->user()->detail->monthly_salary, 2) }}</p>
            @endif
        </div>
    </div>

</div>

</body>
</html>
