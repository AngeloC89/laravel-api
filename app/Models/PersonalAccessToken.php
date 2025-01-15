<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory;

    protected $table = 'personal_access_tokens';

    protected $fillable = ['tokenable_type', 'tokenable_id', 'name', 'token', 'expires_at', 'abilities'];

    // Definisci la relazione polimorfica
    public function tokenable()
    {
        return $this->morphTo();
    }
}
