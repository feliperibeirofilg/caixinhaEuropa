<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depositos extends Model
{
    use HasFactory;

    protected $fillable = [
        'valor',
        'pago'
    ];

    public function depositosFeitos(){

        $sql = "SELECT 
                a.valor, 
                SUM(CASE WHEN a.pago = 0 THEN 1 ELSE 0 END) as pendentes,
                SUM(CASE WHEN a.pago = 1 THEN 1 ELSE 0 END) as feitos
            FROM depositos a
            GROUP BY a.valor
            ORDER BY a.valor DESC";

        return \DB::select($sql);
    }
}
