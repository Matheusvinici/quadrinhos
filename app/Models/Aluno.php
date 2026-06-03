<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    protected $fillable = [
        'nome',
        'escola',
    ];

    public function historias(): HasMany
    {
        return $this->hasMany(Historia::class);
    }
}
