<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e0f7fa, #e1f5fe);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: 600;
        }

        .form-container p {
            font-size: 16px;
            color: #444;
            margin-bottom: 10px;
        }

        .form-container p strong {
            color: #000;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .back-link,
        .edit-button a {
            display: inline-block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        .edit-button a {
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
        }

        .edit-button a:hover,
       .back-link {
    display: inline-block;
    text-align: center;
    margin-top: 20px;
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease, text-decoration 0.3s ease;
}

.back-link:hover {
    text-decoration: underline;
    color: #0056b3;

        }

        .error-messages {
            background-color: #ffe6e6;
            border: 1px solid #ff4d4d;
            color: #cc0000;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error-messages ul {
            margin: 0;
            padding-left: 20px;
        }

        @media (max-width: 520px) {
            .form-container {
                padding: 25px;
                border-radius: 10px;
            }
        }
    </style>
</head>
<body>
<div class="form-container">
    @if (isset($user))
        @if (request()->query('edit') == 'true')
            <h2>Edit Your Details</h2>
<p><em>Note: Please fill in the <strong>Monthly In-Hand Salary</strong> and <strong>Working Days</strong> as per your salary calculation.</em></p>
            @if ($errors->any())
                <div class="error-messages">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="text" name="name" placeholder="Name" value="{{ old('name', $user->name) }}" required>
                <input type="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                <input type="number" name="working_days" placeholder="Working Days" min="1" max="31" value="{{ old('working_days', $user->working_days) }}" required>
                <input type="number" name="monthly_salary" placeholder="Monthly Salary" value="{{ old('monthly_salary', $user->monthly_salary) }}" required>
                <button type="submit">Update</button>
            </form>

            <a href="{{ route('users.create') }}" class="back-link">← Cancel</a>
        @else
            <h2>User Details</h2>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Working Days:</strong> {{ $user->working_days }}</p>
            <p><strong>Monthly Salary:</strong> ₹{{ number_format($user->monthly_salary, 2) }}</p>

            <div class="edit-button">
                <a href="{{ route('users.create', ['edit' => 'true']) }}">Edit</a>
            </div>
        @endif
    @else
        <h2>Add Your Details</h2>
<p><em>Note: Please fill in the <strong>Monthly In-Hand Salary</strong> and <strong>Working Days</strong> as per your salary calculation.</em></p>

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
            <input type="number" name="working_days" placeholder="Working Days per Month" required min="1" max="31">
            <input type="number" name="monthly_salary" placeholder="Monthly Salary (₹)" required>
            <button type="submit">Save</button>
        </form>
    @endif

    <a href="{{ route('users.index') }}" class="back-link">← Back to Dashboard</a>
</div>
</body>
</html>
