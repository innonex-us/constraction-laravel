<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            ['HealthCorp', 'https://dummyimage.com/140x48/0b1220/94a3b8&text=HealthCorp', 'https://example.com'],
            ['TechSolutions', 'https://dummyimage.com/140x48/0b1220/94a3b8&text=TechSolutions', 'https://example.com'],
            ['City Works', 'https://dummyimage.com/140x48/0b1220/94a3b8&text=City+Works', 'https://example.com'],
            ['Port Authority', 'https://dummyimage.com/140x48/0b1220/94a3b8&text=Port+Authority', 'https://example.com'],
            ['EduTrust', 'https://dummyimage.com/140x48/0b1220/94a3b8&text=EduTrust', 'https://example.com'],
            ['BioLabs Inc', 'https://dummyimage.com/140x48/0b1220/94a3b8&text=BioLabs', 'https://example.com'],
            ['Metro Transit', 'https://dummyimage.com/140x48/0b1220/94a3b8&text=Metro+Transit', 'https://example.com'],
            ['Industrial Corp', 'https://dummyimage.com/140x48/0b1220/94a3b8&text=Industrial', 'https://example.com'],
        ];

        foreach ($clients as $i => [$name, $logo, $url]) {
            Client::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'logo' => $logo,
                    'website_url' => $url,
                    'order' => $i,
                    'is_active' => true,
                ]
            );
        }
    }
}
