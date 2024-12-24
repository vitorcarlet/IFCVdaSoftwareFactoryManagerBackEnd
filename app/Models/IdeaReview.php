<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdeaReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_idea_id',
        'reviewed_by',
        'comments',
        'reviewed_at',
    ];

    public function projectIdea()
    {
        return $this->belongsTo(ProjectIdea::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

