<!DOCTYPE html>
<html>
<head>
    <title>Add New User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: white;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .form-container h2 {
            margin-bottom: 25px;
            color: #333;
            text-align: center;
        }

        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="email"] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #218838;
        }

        .form-container .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }

        .form-container .back-link:hover {
            text-decoration: underline;
        }

        .form-container .error-messages {
            background-color: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #cc0000;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .form-container .error-messages ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add your details</h2>

    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="number" name="monthly_salary" placeholder="Monthly Salary (₹)" required>
        <button type="submit">Save</button>
    </form>

    <a href="{{ route('users.index') }}" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>
