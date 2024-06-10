<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Technology;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $technology = [
            'Laravel',
            'PHP',
            'HTML',
            'CSS',
            'JavaScript',
            'VueJS',
            'React',
        ];
        foreach ($technology as $value) {
            $new_technology = new Technology();
            $new_technology->name = $value;
            $new_technology->slug = Technology::generateSlug($new_technology->name);
            $new_technology->save();
        }
    }
}
