<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Financa extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'cartao_id',
        'descricao',
        'valor',
        'tipo',
        'forma_pagamento',
        'data_compra',
        'parcelas',
        'parcela_atual',
        'recorrente',
        'status',
        'observacao',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_compra' => 'date',
        'recorrente' => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function cartao(): BelongsTo
    {
        return $this->belongsTo(Cartao::class, 'cartao_id');
    }
}
