<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id', 
        'user_id', 
        'cover_letter', 
        'resume_path',
        'status', 
        'employer_notes', 
        'applied_date'
    ];

    protected $casts = [
        'applied_date' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'reviewed' => 'blue',
            'shortlisted' => 'purple',
            'interview' => 'indigo',
            'hired' => 'green',
            'rejected' => 'red',
        ];
        return $colors[$this->status] ?? 'gray';
    }
}