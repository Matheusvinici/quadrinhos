<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespostaAluno extends Model
{
    protected $table = 'respostas_aluno';

    protected $fillable = [
        'historia_id',
        'etapa',
        'pergunta',
        'resposta',
    ];

    public function historia(): BelongsTo
    {
        return $this->belongsTo(Historia::class);
    }
}
