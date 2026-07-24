<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaturaCartao extends Model
{
    use HasFactory;

    protected $table = 'faturas_cartao';

    protected $fillable = [
        'cartao_id',
        'mes_referencia',
        'ano_referencia',
        'valor_total',
        'data_fechamento',
        'data_vencimento',
        'status',
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'data_fechamento' => 'date',
        'data_vencimento' => 'date',
    ];

    public function cartao(): BelongsTo
    {
        return $this->belongsTo(Cartao::class, 'cartao_id');
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class, 'fatura_cartao_id');
    }
}
