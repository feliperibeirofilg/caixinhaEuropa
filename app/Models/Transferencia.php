<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transferencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'caixinha_id',
        'descricao',
        'valor',
        'data',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'date',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function caixinha(): BelongsTo
    {
        return $this->belongsTo(Caixinha::class, 'caixinha_id');
    }
}
