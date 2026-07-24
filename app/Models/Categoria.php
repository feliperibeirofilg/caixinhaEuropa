<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo',
        'cor',
        'icone',
    ];

    public function financas(): HasMany
    {
        return $this->hasMany(Financa::class, 'categoria_id');
    }

    public function contasMensais(): HasMany
    {
        return $this->hasMany(ContaMensal::class, 'categoria_id');
    }
}
