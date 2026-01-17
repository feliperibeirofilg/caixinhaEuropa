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
        'telefone',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // public function ():HasMany{
    //     return $this->hasMany()
    //     }
        
}
