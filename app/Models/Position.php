<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = ["code","name","position_type","description","is_active"];
    protected $casts = ["is_active"=>"boolean"];
    public function assignments() { return $this->hasMany(EmployeeAssignment::class); }
}
