<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data (optional)
        // User::truncate();
        // Job::truncate();
        // JobApplication::truncate();

        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@jobportal.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'phone' => '1234567890',
                'bio' => 'System Administrator',
            ]
        );

        // Create Employer Users
        $employers = [
            [
                'name' => 'Tech Corp',
                'email' => 'employer1@example.com',
                'password' => Hash::make('password'),
                'role' => 'employer',
                'company_name' => 'Tech Corp',
                'company_website' => 'https://techcorp.com',
                'phone' => '1234567891',
                'bio' => 'Leading technology company',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Health Plus',
                'email' => 'employer2@example.com',
                'password' => Hash::make('password'),
                'role' => 'employer',
                'company_name' => 'Health Plus',
                'company_website' => 'https://healthplus.com',
                'phone' => '1234567892',
                'bio' => 'Healthcare solutions provider',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Finance Hub',
                'email' => 'employer3@example.com',
                'password' => Hash::make('password'),
                'role' => 'employer',
                'company_name' => 'Finance Hub',
                'company_website' => 'https://financehub.com',
                'phone' => '1234567893',
                'bio' => 'Financial services company',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($employers as $employerData) {
            User::updateOrCreate(
                ['email' => $employerData['email']],
                $employerData
            );
        }

        // Create Job Seekers
        $jobSeekers = [
            [
                'name' => 'John Smith',
                'email' => 'jobseeker1@example.com',
                'password' => Hash::make('password'),
                'role' => 'job_seeker',
                'phone' => '1234567894',
                'bio' => 'Experienced software developer',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'jobseeker2@example.com',
                'password' => Hash::make('password'),
                'role' => 'job_seeker',
                'phone' => '1234567895',
                'bio' => 'Marketing professional with 5 years experience',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'jobseeker3@example.com',
                'password' => Hash::make('password'),
                'role' => 'job_seeker',
                'phone' => '1234567896',
                'bio' => 'Recent graduate in Computer Science',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($jobSeekers as $seekerData) {
            User::updateOrCreate(
                ['email' => $seekerData['email']],
                $seekerData
            );
        }

        // Get employer users
        $employerUsers = User::where('role', 'employer')->get();

        // Skip job/application creation if jobs already exist
        if (Job::count() > 0) {
            $this->command->info('✅ Jobs already exist — skipping job seeding.');
            $this->printCredentials();
            return;
        }

        // Create Jobs
        $jobTypes = ['full-time', 'part-time', 'contract', 'temporary', 'internship'];
        $categories = ['Technology', 'Healthcare', 'Finance', 'Education', 'Marketing', 'Construction', 'Retail'];
        $experienceLevels = ['entry', 'mid', 'senior', 'executive'];
        $locations = ['New York, NY', 'Los Angeles, CA', 'Chicago, IL', 'Houston, TX', 'Phoenix, AZ', 'Philadelphia, PA'];

        foreach ($employerUsers as $employer) {
            // Each employer creates 3 jobs
            for ($i = 0; $i < 3; $i++) {
                Job::create([
                    'employer_id' => $employer->id,
                    'title' => $this->getRandomJobTitle(),
                    'description' => $this->getRandomDescription(),
                    'requirements' => $this->getRandomRequirements(),
                    'benefits' => $this->getRandomBenefits(),
                    'location' => $locations[array_rand($locations)],
                    'salary_min' => rand(30000, 60000),
                    'salary_max' => rand(60000, 120000),
                    'job_type' => $jobTypes[array_rand($jobTypes)],
                    'category' => $categories[array_rand($categories)],
                    'experience_level' => $experienceLevels[array_rand($experienceLevels)],
                    'application_deadline' => now()->addDays(rand(10, 60)),
                    'is_active' => true,
                    'is_featured' => rand(0, 1) === 1,
                    'views' => rand(0, 500),
                    'applications_count' => rand(0, 30),
                ]);
            }
        }

        // Create some featured jobs
        $featuredJobs = Job::take(3)->get();
        foreach ($featuredJobs as $job) {
            $job->update(['is_featured' => true]);
        }

        // Get job seekers
        $seekerUsers = User::where('role', 'job_seeker')->get();
        $allJobs = Job::all();

        // Create Job Applications
        $statuses = ['pending', 'reviewed', 'shortlisted', 'interview', 'hired', 'rejected'];
        foreach ($seekerUsers as $seeker) {
            // Each job seeker applies to 2-4 jobs
            $jobsToApply = $allJobs->random(rand(2, 4));
            foreach ($jobsToApply as $job) {
                JobApplication::create([
                    'job_id' => $job->id,
                    'user_id' => $seeker->id,
                    'cover_letter' => "I am very interested in this position and believe my skills would be a great fit for your company.",
                    'resume_path' => 'resumes/resume_' . $seeker->id . '.pdf',
                    'status' => $statuses[array_rand($statuses)],
                    'employer_notes' => rand(0, 1) === 1 ? "Strong candidate, moving to next round." : null,
                    'applied_date' => now()->subDays(rand(1, 30)),
                ]);
            }
        }

        $this->printCredentials();
    }

    private function printCredentials()
    {
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Admin: admin@jobportal.com');
        $this->command->info('📧 Employer: employer1@example.com');
        $this->command->info('📧 Job Seeker: jobseeker1@example.com');
        $this->command->info('🔑 Password for all: password');
    }

    // Helper methods for random data
    private function getRandomJobTitle()
    {
        $titles = [
            'Software Developer', 'Data Analyst', 'Project Manager', 
            'Marketing Specialist', 'Sales Manager', 'Product Designer',
            'UX Designer', 'DevOps Engineer', 'Quality Assurance Engineer',
            'Systems Administrator', 'Database Administrator', 'Network Engineer',
            'Content Writer', 'Social Media Manager', 'Business Analyst'
        ];
        return $titles[array_rand($titles)];
    }

    private function getRandomDescription()
    {
        $descriptions = [
            "We are looking for a talented professional to join our growing team. The ideal candidate will have strong problem-solving skills and a passion for innovation.",
            "Join our dynamic team where you will have the opportunity to work on exciting projects and make a real impact. We offer a collaborative work environment and excellent growth opportunities.",
            "We're seeking a dedicated individual who can contribute to our mission of excellence. The role requires strong communication skills and the ability to work in a fast-paced environment."
        ];
        return $descriptions[array_rand($descriptions)];
    }

    private function getRandomRequirements()
    {
        $requirements = [
            "Bachelor's degree in Computer Science or related field. 3+ years of experience in software development.",
            "Master's degree preferred. 5+ years of experience in project management. PMP certification is a plus.",
            "Bachelor's degree in Business or Marketing. Strong communication and analytical skills required."
        ];
        return $requirements[array_rand($requirements)];
    }

    private function getRandomBenefits()
    {
        $benefits = [
            "Competitive salary, Health insurance, 401(k) matching, Flexible hours, Work from home options",
            "Performance bonuses, Professional development opportunities, Paid time off, Wellness programs",
            "Stock options, Company events, Gym membership, Educational assistance"
        ];
        return $benefits[array_rand($benefits)];
    }
}