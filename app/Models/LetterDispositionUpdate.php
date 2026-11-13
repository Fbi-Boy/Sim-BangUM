<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LetterDispositionUpdate extends Model {
    protected $fillable = ["letter_disposition_id","updated_by_user_id","status","progress_note"];
    public function disposition() { return $this->belongsTo(LetterDisposition::class, "letter_disposition_id"); }
    public function user() { return $this->belongsTo(User::class, "updated_by_user_id"); }
}
