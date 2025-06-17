<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Salary extends Model
{
    protected $fillable = ['user_id', 'month', 'credited_salary' , 'is_credited'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses->sum('amount');
    }

    public function getNetSalaryAttribute()
    {
        return $this->credited_salary - $this->total_expenses;
    }

public function user()
{
    return $this->belongsTo(UserDetail::class, 'user_id');
}

}

