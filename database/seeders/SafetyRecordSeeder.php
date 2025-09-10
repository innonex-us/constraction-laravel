<?php

namespace Database\Seeders;

use App\Models\SafetyRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SafetyRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $safetyRecords = [
            [
                'year' => 2023,
                'emr' => 0.85,
                'trir' => 1.2,
                'ltir' => 0.3,
                'total_hours' => 156000,
                'osha_recordables' => 2,
                'description' => 'Excellent safety performance with continued improvement in incident prevention. Implemented new training programs and safety protocols.',
                'created_at' => now()->subYear(),
                'updated_at' => now()->subYear(),
            ],
            [
                'year' => 2022,
                'emr' => 0.92,
                'trir' => 1.8,
                'ltir' => 0.5,
                'total_hours' => 142000,
                'osha_recordables' => 3,
                'description' => 'Good safety performance with room for improvement in near-miss reporting. Increased safety meeting frequency.',
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subYears(2),
            ],
            [
                'year' => 2021,
                'emr' => 1.05,
                'trir' => 2.4,
                'ltir' => 0.8,
                'total_hours' => 128000,
                'osha_recordables' => 4,
                'description' => 'Implemented new safety protocols mid-year, showing improvement in Q4. Enhanced training programs.',
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subYears(3),
            ],
            [
                'year' => 2020,
                'emr' => 1.15,
                'trir' => 3.1,
                'ltir' => 1.2,
                'total_hours' => 98000,
                'osha_recordables' => 5,
                'description' => 'COVID-19 impacted operations. Focused on health and safety protocols. Adapted safety procedures for pandemic.',
                'created_at' => now()->subYears(4),
                'updated_at' => now()->subYears(4),
            ],
            [
                'year' => 2019,
                'emr' => 1.25,
                'trir' => 3.8,
                'ltir' => 1.5,
                'total_hours' => 135000,
                'osha_recordables' => 7,
                'description' => 'Baseline year for current safety program implementation. Established new safety management system.',
                'created_at' => now()->subYears(5),
                'updated_at' => now()->subYears(5),
            ],
        ];

        foreach ($safetyRecords as $record) {
            SafetyRecord::create($record);
        }
    }
}
