<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker; 
use App\Models\Project;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $path = __DIR__ . "/projects.csv";//qua prendi il percorso del file
        $projects_csv = fopen($path, "r");//qua leggi il file
        if ($projects_csv === false) {
            exit("Cannot open $path");
        }


        $data = [];
        while ($row = fgetcsv($projects_csv)) {
            $data[] = $row;
        }

        foreach ($data as $index => $project) {
            if($index !== 0) {

                $new_project = new Project();
                $new_project->title = $project[0];
                $new_project->content = $project[1];
                $new_project->image = $project[2];
                $new_project->slug = Project::generateSlug($new_project->title);
                $new_project->save();
            }
        }
    }
}
