<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixinha extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'meta_valor',
        'quantidade',
    ];

    public function usuarios(){
        return $this->hasMany(Usuario::class, 'caixinha_id');
    }
}
