<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'date',
        'description',
        'attachment_path',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

