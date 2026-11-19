<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LetterDisposition extends Model
{
    protected $fillable = [
        'parent_disposition_id', 'incoming_letter_id', 'from_user_id', 'to_user_id', 'to_unit_id',
        'instruction', 'priority', 'due_at', 'status', 'submitted_at', 'reviewed_at', 'reviewed_by',
    ];
    protected $casts = [
        'due_at' => 'datetime',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    // Statuses yang dianggap "aktif" (tampil di list)
    const ACTIVE_STATUSES = ['assigned', 'accepted', 'in_progress', 'returned'];
    const SUBMITTED_STATUSES = ['submitted'];
    const CLOSED_STATUSES = ['approved', 'cancelled'];

    public function incomingLetter() { return $this->belongsTo(IncomingLetter::class); }
    public function fromUser() { return $this->belongsTo(User::class, 'from_user_id'); }
    public function toUser() { return $this->belongsTo(User::class, 'to_user_id'); }
    public function toUnit() { return $this->belongsTo(OrganizationalUnit::class, 'to_unit_id'); }
    public function updates() { return $this->hasMany(LetterDispositionUpdate::class)->latest(); }
    public function parent() { return $this->belongsTo(LetterDisposition::class, 'parent_disposition_id'); }
    public function children() { return $this->hasMany(LetterDisposition::class, 'parent_disposition_id'); }

    // Scopes
    public function scopeActive($q) { return $q->whereIn('status', self::ACTIVE_STATUSES); }
    public function scopeSubmitted($q) { return $q->whereIn('status', self::SUBMITTED_STATUSES); }
    public function scopeNotClosed($q) { return $q->whereNotIn('status', self::CLOSED_STATUSES); }
    public function scopeForUser($q, $userId) { return $q->where('to_user_id', $userId); }
    public function scopeFromUser($q, $userId) { return $q->where('from_user_id', $userId); }

    // Helpers
    public function isClosed() { return in_array($this->status, self::CLOSED_STATUSES); }
    public function isSubmitted() { return $this->status === 'submitted'; }
    public function isActive() { return in_array($this->status, self::ACTIVE_STATUSES); }
}
