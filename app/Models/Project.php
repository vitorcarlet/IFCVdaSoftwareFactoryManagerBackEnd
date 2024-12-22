<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project  
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
        return $this->belongsToMany(User::class, 'project_participants');
    }
}