<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #007bff;
            --dark: #212529;
            --gray-dark: #343a40;
            --gray: #6c757d;
            --light: #f8f9fa;
            --white: #fff;
            --success: #28a745;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--light);
            color: var(--dark);
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--dark);
            color: var(--white);
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sidebar h2 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 30px;
            color: var(--white);
        }

        .sidebar a,
        .sidebar form button {
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: var(--gray-dark);
            color: var(--white);
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            font-size: 15px;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .sidebar a:hover,
        .sidebar form button:hover {
            background-color: #495057;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 40px;
            background-color: #f1f3f5;
        }

        .main-content h2 {
            font-size: 30px;
            margin-bottom: 25px;
            color: var(--gray-dark);
        }

        .card {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 30px;
        }

        .card h3 {
            font-size: 22px;
            margin-bottom: 18px;
            color: var(--gray-dark);
        }

        .card p {
            font-size: 16px;
            color: var(--gray);
            margin-bottom: 10px;
        }
        .card-row {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.card-row .card {
    flex: 1 1 calc(50% - 15px);
}


        .alert {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-left: 6px solid var(--success);
            border-radius: 6px;
            font-weight: 500;
            margin-bottom: 20px;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary);
            color: var(--white);
            border-radius: 6px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: space-around;
                height: auto;
                padding: 20px;
                gap: 10px;
            }

            .sidebar h2 {
                flex: 1 1 100%;
                text-align: center;
            }

            .sidebar a,
            .sidebar form button {
                flex: 1 1 45%;
                justify-content: center;
            }

            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <a href="{{ route('attendance.calendar', auth()->user()->id) }}"><i class="fas fa-calendar-check"></i> Attendance</a>
        <a href="{{ route('salary.calculate', auth()->user()->id) }}"><i class="fas fa-wallet"></i> Monthly Total</a>
        <a href="{{ route('salary.summary', auth()->user()->id) }}"><i class="fas fa-file-invoice-dollar"></i> Summary</a>
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
<div class="card-row">
        <div class="card">
            <h3>Account Overview</h3>
            <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
            @if(auth()->user()->is_admin)
                <p><strong>Monthly Salary:</strong> ₹{{ number_format(auth()->user()->detail->monthly_salary, 2) }}</p>
            @endif
        </div>

        <div class="card">
            <h3>Salary & Expenses</h3>
            <p>View your credited salary, track your monthly expenses, and calculate your net salary.</p>
            <a href="{{ route('salary.index') }}" class="btn">Go to Salary Dashboard</a>
        </div>
 </div>
    </div>
</div>

</body>
</html>
