<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Models\Technology;
use App\Models\Type;
use App\Models\Image;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['type_id', 'title', 'link', 'content', 'slug' ];

    public static function generateSlug($title)
    {

        $slug = Str::slug($title, '-');
        $count = 1;
        //itera nel campo slug per verificare se ne esiste uno uguale, se esiste modifica iln titolo... 
        while (Project::where('slug', $slug)->first()) {
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

    //questa funzione serve a gestire la relazione tra la tabella dei progetti e quella delle immagini (1 a molti)
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
