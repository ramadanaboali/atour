<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactUs extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 
        'description', 
        'user_id',
        'closed_at',
        'status',
        'notes',
        'priority',
        'assigned_to'
    ];

    protected $casts = [
        'closed_at' => 'datetime'
    ];

    // Ticket statuses
    public const STATUS_OPEN = 'open';
    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_CLOSED = 'closed';

    // Ticket priorities
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function latestReply(): BelongsTo
    {
        return $this->belongsTo(TicketReply::class, 'id', 'contact_us_id')
                    ->latest();
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_OPEN, self::STATUS_IN_PROGRESS]);
    }

    public function scopeClosed($query)
    {
        return $query->whereIn('status', [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, [self::STATUS_OPEN, self::STATUS_IN_PROGRESS]);
    }

    public function isClosed(): bool
    {
        return in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    public function close($notes = null)
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
            'closed_at' => now(),
            'notes' => $notes
        ]);
    }

    public function reopen()
    {
        $this->update([
            'status' => self::STATUS_OPEN,
            'closed_at' => null
        ]);
    }

    public function getUnreadRepliesCount(): int
    {
        return $this->replies()->unread()->count();
    }
}
