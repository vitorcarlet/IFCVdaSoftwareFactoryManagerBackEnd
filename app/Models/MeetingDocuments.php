<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingDocument extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meetings_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'meeting_id',
        'document_id',
    ];

    /**
     * Relationships
     */

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}