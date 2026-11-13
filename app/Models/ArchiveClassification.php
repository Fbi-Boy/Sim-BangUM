<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ArchiveClassification extends Model
{
    protected $fillable = ["code","name","description","retention_active_months","final_disposition","is_active"];
    protected $casts = ["is_active"=>"boolean"];
    public function records() { return $this->hasMany(ArchiveRecord::class); }
}
