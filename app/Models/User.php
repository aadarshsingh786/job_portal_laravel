<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'phone', 
        'profile_image',
        'bio', 
        'company_name', 
        'company_website',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Notification Relationship
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Unread Notifications
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    // Read Notifications
    public function readNotifications()
    {
        return $this->hasMany(Notification::class)->where('is_read', true);
    }

    // Jobs (as employer)
    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id');
    }

    // Job Applications (as job seeker)
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Saved Jobs
    public function savedJobs()
    {
        return $this->belongsToMany(Job::class, 'saved_jobs');
    }

    // Sent Messages
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Received Messages
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Unread Messages
    public function unreadMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id')->where('is_read', false);
    }

    // Role Checks
    public function isEmployer()
    {
        return $this->role === 'employer';
    }

    public function isJobSeeker()
    {
        return $this->role === 'job_seeker';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}