<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting_participant';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meeting_id',
        'participant_id',
        // Add additional fields here, like 'role', etc.
    ];

    /**
     * Relationships
     */

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function participant()
    {
        return $this->belongsTo(User::class);
    }
}
