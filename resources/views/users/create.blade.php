<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Name" required>
    <input type="number" name="monthly_salary" placeholder="Monthly Salary" required>
    <button type="submit">Save</button>
</form>
