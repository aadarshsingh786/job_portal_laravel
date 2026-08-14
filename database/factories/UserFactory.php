<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Default password for testing
            'role' => $this->faker->randomElement(['job_seeker', 'employer', 'admin']),
            'phone' => $this->faker->phoneNumber(),
            'profile_image' => null,
            'bio' => $this->faker->paragraph(2),
            'company_name' => $this->faker->company(),
            'company_website' => $this->faker->url(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    // Custom states for different roles
    public function employer()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'employer',
                'company_name' => $this->faker->company(),
                'company_website' => $this->faker->url(),
            ];
        });
    }

    public function jobSeeker()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'job_seeker',
                'company_name' => null,
                'company_website' => null,
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'admin',
                'company_name' => null,
                'company_website' => null,
            ];
        });
    }
}