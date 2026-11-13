<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ArchiveRecord extends Model
{
    protected $fillable = ["archive_code","archive_classification_id","title","description","record_date","archive_status","physical_location","registered_by_user_id"];
    protected $casts = ["record_date"=>"date"];
    public function classification() { return $this->belongsTo(ArchiveClassification::class, "archive_classification_id"); }
    public function registeredBy() { return $this->belongsTo(User::class, "registered_by_user_id"); }
    public function documents() { return $this->morphMany(Document::class, "documentable"); }
}
