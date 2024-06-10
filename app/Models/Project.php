<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Models\Technology;
use App\Models\Type;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'image', 'slug', 'type_id'];

    public static function generateSlug($title){

        $slug = Str::slug($title, '-');
        $count = 1;
        //itera nel campo slug per verificare se ne esiste uno uguale, se esiste modifica iln titolo... 
        while(Project::where('slug', $slug)->first()){
            $slug = Str::of($title)->slug('-') . " - {$count}";
            $count++;
        }
        return $slug;
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class);
    }
}
