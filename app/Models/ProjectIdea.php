<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectIdea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'proponent_id',
    ];

    public function proponent()
    {
        return $this->belongsTo(User::class, 'proponent_id');
    }

    public function reviews()
    {
        return $this->hasMany(IdeaReview::class);
    }
}