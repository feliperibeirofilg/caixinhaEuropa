<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Usuario extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'login',
        'admin',
        'email',
        'email_verified_at',
        'email_verification_token',
        'telefone',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function depositos(): HasMany
    {
        return $this->hasMany(Depositos::class, 'usuario_id');
    }

    public function saques(): HasMany
    {
        return $this->hasMany(Saque::class, 'usuario_id');
    }

    public function caixinha()
    {
        return $this->belongsTo(Caixinha::class, 'caixinha_id');
    }

    public function cartoes(): HasMany
    {
        return $this->hasMany(Cartao::class, 'usuario_id');
    }

    public function financas(): HasMany
    {
        return $this->hasMany(Financa::class, 'usuario_id');
    }

    public function contasMensais(): HasMany
    {
        return $this->hasMany(ContaMensal::class, 'usuario_id');
    }

    public function transferencias(): HasMany
    {
        return $this->hasMany(Transferencia::class, 'usuario_id');
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class, 'usuario_id');
    }
}
