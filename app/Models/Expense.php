<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['salary_id', 'title', 'amount'];

    public function salary()
    {
        return $this->belongsTo(Salary::class);
    }
}

