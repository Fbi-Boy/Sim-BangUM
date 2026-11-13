<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ["name", "email", "password"];
    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(\App\Models\Role::class, "user_roles")
            ->withPivot(["scope_unit_id", "valid_from", "valid_until", "revoked_at"])
            ->wherePivotNull("revoked_at");
    }

    public function hasRole(string $code): bool
    {
        return $this->roles()
            ->where("code", $code)
            ->where("valid_from", "<=", now())
            ->where(function ($q) {
                $q->whereNull("valid_until")->orWhere("valid_until", ">=", now());
            })
            ->exists();
    }

    public function hasAnyRole(array $codes): bool
    {
        foreach ($codes as $code) {
            if ($this->hasRole($code)) return true;
        }
        return false;
    }
}
