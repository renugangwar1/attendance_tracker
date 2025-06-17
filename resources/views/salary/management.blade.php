<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Salary</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            padding: 30px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f3f5;
        }

        .container {
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        h2 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 20px;
            margin-top: 30px;
        }

        h4 {
            font-size: 18px;
        }

        .credited {
            color: #28a745;
            font-weight: bold;
        }

        .not-credited {
            color: #dc3545;
            font-weight: bold;
        }

        .form-control-sm {
            font-size: 0.9rem;
            padding: 0.4rem 0.75rem;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.875rem;
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #6c757d;
            color: white;
            padding: 8px 14px;
            font-size: 14px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #495057;
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <a href="{{ route('salary.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i> ← Back to Salary Overview</a>

    @if(isset($salary))
        <h2>Manage Salary – {{ $salary->month }}</h2>

        <h4>Credited Status:
            @if($salary->is_credited)
                <span class="credited">Credited</span>
            @else
                <span class="not-credited">Not Credited</span>
            @endif
        </h4>

        <hr>

        <h3>Add Expense</h3>
        <form action="{{ route('salary.addExpense', $salary->id) }}" method="POST" class="row g-2 align-items-end">
            @csrf
            <div class="col-sm-6">
                <input type="text" class="form-control form-control-sm" name="title" placeholder="Expense title" required>
            </div>
            <div class="col-sm-4">
                <input type="number" class="form-control form-control-sm" name="amount" placeholder="Amount" required>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary btn-sm w-100">Add</button>
            </div>
        </form>

        <hr>

        <h3>Expenses</h3>
        @if(isset($salary->expenses) && count($salary->expenses) > 0)
            <table class="table table-bordered table-striped mt-3">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Amount (₹)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salary->expenses as $expense)
                        <tr>
                            <td>{{ $expense->title }}</td>
                            <td>₹{{ number_format($expense->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total Expense</th>
                        <th>₹{{ number_format($salary->expenses->sum('amount'), 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        @else
            <p class="text-muted">No expenses added yet.</p>
        @endif

    @else
        <div class="alert alert-danger mt-4">
            <strong>Error:</strong> Salary data not found.
        </div>
    @endif
</div>

<!-- Font Awesome CDN for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>
