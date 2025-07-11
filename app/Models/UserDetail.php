<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;
    protected $fillable = ['name','email', 'working_days','monthly_salary'];

    public function attendances()
{
    return $this->hasMany(Attendance::class, 'user_detail_id');
}
}
