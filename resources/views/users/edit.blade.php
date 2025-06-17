<!DOCTYPE html>
<html>
<head>
    <title>Edit Your Details</title>
   
</head>
<body>

<div class="form-container">
    <h2>Edit your details</h2>

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

        <input type="text" name="name" value="{{ $user->name }}" required>
        <input type="email" name="email" value="{{ $user->email }}" required>
        <input type="number" name="working_days" value="{{ $user->working_days }}" required min="1" max="31">
        <input type="number" name="monthly_salary" value="{{ $user->monthly_salary }}" required>

        <button type="submit">Update</button>
    </form>

    <a href="{{ route('users.index') }}" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>
