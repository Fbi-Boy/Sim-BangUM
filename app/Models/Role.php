<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ["code", "name", "description", "is_system", "is_active"];

    public function users()
    {
        return $this->belongsToMany(User::class, "user_roles");
    }
}
