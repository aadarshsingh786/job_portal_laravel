<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id', 
        'title', 
        'description', 
        'requirements', 
        'about_company',
        'benefits',
        'location', 
        'salary_min', 
        'salary_max', 
        'job_type', 
        'category',
        'experience_level', 
        'application_deadline', 
        'is_active', 
        'is_featured',
        'views', 
        'applications_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'application_deadline' => 'date',
        'salary_min' => 'integer',
        'salary_max' => 'integer',
        'views' => 'integer',
        'applications_count' => 'integer',
    ];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedBy()
    {
        return $this->belongsToMany(User::class, 'saved_jobs');
    }

    public function getSalaryRangeAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return "$" . number_format($this->salary_min) . " - $" . number_format($this->salary_max);
        } elseif ($this->salary_min) {
            return "From $" . number_format($this->salary_min);
        } elseif ($this->salary_max) {
            return "Up to $" . number_format($this->salary_max);
        }
        return "Not specified";
    }

    public function getDisplayCompanyAttribute()
    {
        $employer = $this->employer;
        if (!$employer) {
            return null;
        }

        if ($employer->role === 'admin') {
            return $employer->company_name ?: null;
        }

        return $employer->company_name ?? $employer->name;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function($q) {
                         $q->whereNull('application_deadline')
                           ->orWhere('application_deadline', '>=', now());
                     });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}