<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'VPN & Privacy Tools',
            'Security Software License',
            'Cybersecurity Online Course',
            'Penetration Testing Toolkit',
        ];

        foreach ($categories as $name) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'status' => true]
            );
        }
    }
}
