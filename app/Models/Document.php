<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Document extends Model
{
    protected $fillable = ["title","documentable_type","documentable_id","disk","path","original_filename","mime_type","file_size_bytes","uploaded_by_user_id"];
    public function documentable() { return $this->morphTo(); }
    public function uploader() { return $this->belongsTo(User::class, "uploaded_by_user_id"); }
    public function humanSize(): string {
        $b = (int)$this->file_size_bytes;
        if ($b < 1024) return $b." B";
        if ($b < 1048576) return round($b/1024, 1)." KB";
        return round($b/1048576, 1)." MB";
    }
}
