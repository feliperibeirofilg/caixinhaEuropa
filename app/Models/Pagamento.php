<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'fatura_cartao_id',
        'valor',
        'data_pagamento',
        'forma_pagamento',
        'observacao',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_pagamento' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function faturaCartao(): BelongsTo
    {
        return $this->belongsTo(FaturaCartao::class, 'fatura_cartao_id');
    }
}
