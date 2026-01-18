<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepositoIniciaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listaDeDepositos = [
            500.00 => 4,
            200.00 => 13,
            100.00 => 35,
            20.00 => 60,
            10.00 => 52,
            5.00 => 36,
        ];

        $dadosParaInserir = [];
        $agora = Carbon::now();

        foreach($listaDeDepositos as $valor => $quantidade){

            for($i=0; $i<$quantidade; $i++){
                $dadosParaInserir[] = [
                    'valor' => $valor,
                    'created_at' => $agora,
                    'updated_at' => $agora,
                ];
            }
        }

        DB::table('depositos')->insert($dadosParaInserir);

        echo "Sucesso! 200 depositos inseridos.";
    }
}
