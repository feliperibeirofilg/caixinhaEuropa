<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContaMensal extends Model
{
    use HasFactory;

    protected $table = 'contas_mensais';

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'nome',
        'valor_estimado',
        'dia_vencimento',
        'tipo',
        'ativa',
        'observacao',
    ];

    protected $casts = [
        'valor_estimado' => 'decimal:2',
        'ativa' => 'boolean',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(ContaMensalPagamento::class, 'conta_mensal_id');
    }
}
