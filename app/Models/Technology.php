<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;

class Technology extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
    ];

    public static function generateSlug($name){

        $slug = Str::slug($name, '-');
        $count = 1;
        //itera nel campo slug per verificare se ne esiste uno uguale, se esiste modifica iln titolo... 
        while(Technology::where('slug', $slug)->first()){
            $slug = Str::of($name)->slug('-') . " - {$count}";
            $count++;
        }
        return $slug;
    }

    public function projects(){
        return $this->belongsToMany(Project::class);
    }
}
