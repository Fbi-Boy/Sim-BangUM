<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ["employee_number","full_name","email","phone","employment_status","joined_at","left_at"];
    protected $casts = ["joined_at"=>"date","left_at"=>"date"];
    public function assignments() { return $this->hasMany(EmployeeAssignment::class); }
    public function currentAssignment() { return $this->hasOne(EmployeeAssignment::class)->whereNull("ends_on")->latestOfMany("starts_on"); }
}
