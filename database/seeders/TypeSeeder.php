<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Type;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $types = ['Frontend', 'Backend', 'Fullstack'];
      Foreach($types as $type){
        $new_type = new Type();
        $new_type->name = $type;
        $new_type->slug = Type::generateSlug($new_type->name);
        $new_type->save();
      }
    }
};
