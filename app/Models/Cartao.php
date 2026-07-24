<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cartao extends Model
{
    use HasFactory;

    protected $table = 'cartoes';

    protected $fillable = [
        'usuario_id',
        'nome',
        'tipo',
        'bandeira',
        'limite',
        'dia_fechamento',
        'dia_vencimento',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'limite' => 'decimal:2',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function financas(): HasMany
    {
        return $this->hasMany(Financa::class, 'cartao_id');
    }

    public function faturas(): HasMany
    {
        return $this->hasMany(FaturaCartao::class, 'cartao_id');
    }
}
