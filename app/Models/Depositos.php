<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depositos extends Model
{
    use HasFactory;

    public function quantidadeDeposito(){

        $depositos = "SELECT valor, count(*) as quantidade 
                        FROM depositos 
                        GROUP BY valor;";

        return \DB::select($depositos);
    }
}
