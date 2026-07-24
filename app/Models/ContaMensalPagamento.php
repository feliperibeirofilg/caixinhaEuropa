<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContaMensalPagamento extends Model
{
    use HasFactory;

    protected $table = 'contas_mensais_pagamentos';

    protected $fillable = [
        'conta_mensal_id',
        'competencia_mes',
        'competencia_ano',
        'valor_pago',
        'data_pagamento',
        'status',
    ];

    protected $casts = [
        'valor_pago' => 'decimal:2',
        'data_pagamento' => 'date',
    ];

    public function contaMensal(): BelongsTo
    {
        return $this->belongsTo(ContaMensal::class, 'conta_mensal_id');
    }
}
