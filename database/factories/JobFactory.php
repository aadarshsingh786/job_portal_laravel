<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition()
    {
        $jobTypes = ['full-time', 'part-time', 'contract', 'temporary', 'internship'];
        $categories = ['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing', 'Construction', 'Retail'];
        $experienceLevels = ['entry', 'mid', 'senior', 'executive'];
        $locations = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio'];
        
        return [
            'employer_id' => User::factory()->employer(), // Will create an employer user
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraphs(3, true),
            'requirements' => $this->faker->paragraphs(2, true),
            'benefits' => $this->faker->paragraphs(2, true),
            'location' => $this->faker->randomElement($locations),
            'salary_min' => $this->faker->numberBetween(30000, 60000),
            'salary_max' => $this->faker->numberBetween(60000, 120000),
            'job_type' => $this->faker->randomElement($jobTypes),
            'category' => $this->faker->randomElement($categories),
            'experience_level' => $this->faker->randomElement($experienceLevels),
            'application_deadline' => $this->faker->dateTimeBetween('+1 week', '+3 months'),
            'is_active' => true,
            'is_featured' => $this->faker->boolean(20),
            'views' => $this->faker->numberBetween(0, 1000),
            'applications_count' => $this->faker->numberBetween(0, 50),
        ];
    }
}