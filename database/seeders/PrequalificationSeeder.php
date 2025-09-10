<?php

namespace Database\Seeders;

use App\Models\Prequalification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrequalificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prequalifications = [
            [
                'company_name' => 'ABC Construction Company',
                'contact_name' => 'John Smith',
                'email' => 'john.smith@abcconstruction.com',
                'phone' => '(555) 123-4567',
                'trade' => 'General Contractor',
                'license_number' => 'CA-123456',
                'years_in_business' => 25,
                'annual_revenue' => 15000000,
                'bonding_capacity' => 5000000,
                'emr' => 0.85,
                'trir' => 1.2,
                'safety_contact' => 'Jane Safety Manager',
                'insurance_carrier' => 'State Fund Insurance',
                'coverage' => '$2,000,000 General Liability',
                'address' => '123 Construction Ave',
                'city' => 'Builder City',
                'state' => 'CA',
                'zip' => '90210',
                'website' => 'https://www.abcconstruction.com',
                'notes' => 'Excellent track record with large commercial projects. OSHA certified.',
                'documents' => json_encode(['insurance_cert.pdf', 'license_copy.pdf', 'bonding_letter.pdf']),
            ],
            [
                'company_name' => 'Superior Electrical Services',
                'contact_name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@superiorelectrical.com',
                'phone' => '(555) 987-6543',
                'trade' => 'Electrical',
                'license_number' => 'TX-987654',
                'years_in_business' => 18,
                'annual_revenue' => 4500000,
                'bonding_capacity' => 2000000,
                'emr' => 0.92,
                'trir' => 1.8,
                'safety_contact' => 'Mike Safety Coordinator',
                'insurance_carrier' => 'Liberty Mutual',
                'coverage' => '$1,000,000 General Liability',
                'address' => '456 Electric Blvd',
                'city' => 'Spark Town',
                'state' => 'TX',
                'zip' => '75001',
                'website' => 'https://www.superiorelectrical.com',
                'notes' => 'Specialized in commercial electrical systems. Strong safety record.',
                'documents' => json_encode(['insurance_cert.pdf', 'electrical_license.pdf']),
            ],
            [
                'company_name' => 'Premium Plumbing Solutions',
                'contact_name' => 'Mike Rodriguez',
                'email' => 'mike@premiumplumbing.com',
                'phone' => '(555) 456-7890',
                'trade' => 'Plumbing',
                'license_number' => 'FL-456789',
                'years_in_business' => 13,
                'annual_revenue' => 2800000,
                'bonding_capacity' => 500000,
                'emr' => 1.05,
                'trir' => 2.1,
                'safety_contact' => 'Maria Safety Officer',
                'insurance_carrier' => 'Travelers Insurance',
                'coverage' => '$500,000 General Liability',
                'address' => '789 Pipe Street',
                'city' => 'Flow City',
                'state' => 'FL',
                'zip' => '33101',
                'website' => null,
                'notes' => 'Experienced in residential and light commercial plumbing.',
                'documents' => json_encode(['insurance_cert.pdf', 'plumbing_license.pdf', 'safety_training.pdf']),
            ],
            [
                'company_name' => 'Steel Frame Specialists',
                'contact_name' => 'Robert Steel',
                'email' => 'rob@steelframe.com',
                'phone' => '(555) 321-0987',
                'trade' => 'Structural Steel',
                'license_number' => 'NY-321098',
                'years_in_business' => 22,
                'annual_revenue' => 8500000,
                'bonding_capacity' => 3000000,
                'emr' => 0.78,
                'trir' => 0.9,
                'safety_contact' => 'Tom Safety Director',
                'insurance_carrier' => 'Zurich Insurance',
                'coverage' => '$5,000,000 General Liability',
                'address' => '321 Steel Way',
                'city' => 'Iron City',
                'state' => 'NY',
                'zip' => '10001',
                'website' => 'https://www.steelframespec.com',
                'notes' => 'Leading structural steel contractor with excellent safety record.',
                'documents' => json_encode(['insurance_cert.pdf', 'structural_license.pdf', 'bonding_letter.pdf', 'safety_awards.pdf']),
            ],
        ];

        foreach ($prequalifications as $prequalification) {
            Prequalification::create($prequalification);
        }
    }
}
