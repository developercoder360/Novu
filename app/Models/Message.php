<?php

namespace App\Models;

use App\Notifications\NewMessageNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;

class Message extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'is_read',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    /**
     * Get the sender of the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    /**
     * Get the recipient of the message.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
    /**
     * Scope a query to only include messages between specified users.
     */
    public function scopeConversation($query, $userId, $otherUserId)
    {
        return $query->where(function ($query) use ($userId, $otherUserId) {
            $query->where('sender_id', $userId)
                ->where('recipient_id', $otherUserId);
        })->orWhere(function ($query) use ($userId, $otherUserId) {
            $query->where('sender_id', $otherUserId)
                ->where('recipient_id', $userId);
        })->orderBy('created_at', 'asc');
    }
    /**
     * Mark the message as read.
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->is_read = true;
            $this->save();
        }
    }
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Message $message) {
            // Send notification to recipient
            $recipient = $message->recipient;
            $recipient->notify(new NewMessageNotification($message));
        });
    }
}
