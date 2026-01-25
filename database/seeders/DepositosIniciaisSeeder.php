<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepositosIniciaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listaDeDepositos10000 = [
            500.00 => 4,
            200.00 => 13,
            100.00 => 35,
            20.00 => 60,
            10.00 => 52,
            5.00 => 36,
        ];

        $listaDeDepositos1000 = [
            50.00 => 4,
            20.00 => 15,
            10.00 => 30,
            5.00 => 30,
            2.00 => 25,
            ];

        $listaDeDepositos5000 = [
            200.00 => 5,
            100.00 => 15,
            50.00 => 30,
            20.00 => 30,
            10.00 => 30,
            5.00 => 20,
        ];

        $agora = Carbon::now();

        $dadosParaInserir = [
            ['nome' => 'Iniciante R$ 1000,00', 
            'meta_valor' => 1000.00, 
            'quantidade' => json_encode($listaDeDepositos10000)],
            ['nome' => 'Média R$ 5000,00',
             'meta_valor' => 5000.00, 
             'quantidade' => json_encode($listaDeDepositos5000)],
            ['nome' => 'Difícil R$ 10000,00',
             'meta_valor' => 10000.00, 
             'quantidade' => json_encode($listaDeDepositos1000)],
        ];
       

        DB::table('caixinhas')->insert($dadosParaInserir);

        echo "Sucesso! Caixinhas adicionadas.";
    }
}
