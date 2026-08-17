<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplicationFactory extends Factory
{
    protected $model = JobApplication::class;

    public function definition()
    {
        $statuses = ['pending', 'reviewed', 'shortlisted', 'interview', 'hired', 'rejected'];
        
        return [
            'job_id' => Job::factory(),
            'user_id' => User::factory()->jobSeeker(),
            'cover_letter' => $this->faker->paragraphs(2, true),
            'resume_path' => 'resumes/resume_' . $this->faker->uuid() . '.pdf',
            'status' => $this->faker->randomElement($statuses),
            'employer_notes' => $this->faker->optional()->paragraph(),
            'applied_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}