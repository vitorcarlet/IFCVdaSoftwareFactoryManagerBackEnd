<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'size',
        'version',
    ];

    /**
     * Define the relationship to the Project model.
     */

    public function projects()
    {
        return $this->belongsTo(Project::class, 'projects_documents')
            ->withTimestamps();
    }

    public function meetings()
    {
        return $this->belongsTo(Meeting::class, 'meetings_documents')
            ->withTimestamps();
    }
}
