<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationalUnit extends Model
{
    protected $fillable = ["parent_id","code","name","unit_type","description","is_active"];
    protected $casts = ["is_active"=>"boolean"];
    public function parent() { return $this->belongsTo(self::class, "parent_id"); }
    public function children() { return $this->hasMany(self::class, "parent_id"); }
    public function assignments() { return $this->hasMany(EmployeeAssignment::class); }
}
