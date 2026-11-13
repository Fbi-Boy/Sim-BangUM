<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAssignment extends Model
{
    protected $fillable = ["employee_id","organizational_unit_id","position_id","assignment_type","starts_on","ends_on","is_primary","reference_number"];
    protected $casts = ["starts_on"=>"date","ends_on"=>"date","is_primary"=>"boolean"];
    public function employee() { return $this->belongsTo(Employee::class); }
    public function organizationalUnit() { return $this->belongsTo(OrganizationalUnit::class); }
    public function position() { return $this->belongsTo(Position::class); }
}
