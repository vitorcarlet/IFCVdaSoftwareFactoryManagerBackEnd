<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'business_environment',
        'business_need',
        'objective',
        'technologies',
        'stakeholders',
        'status',
        'is_public',
        'start_date',
        'end_date',
        'manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function participants()
    {
        return $this->hasMany(ProjectParticipant::class);
    }

    public function documents()
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ProjectStatusHistory::class);
    }
}

