<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Add your columns here if needed
    ];

    /**
     * Relationships
     */

    public function participants()
    {
        return $this->belongsToMany(User::class, 'meetings_participants')
                    ->withTimestamps();
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'meetings_documents')
                    ->withTimestamps();
    }
}