<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Depositos;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TelegramController extends Controller
{
    public function handle(Request $request){

    $dados = $request->all();

        if(!isset($dados['message']['text'])){
            return response('OK', 200);
        }
        
        $chatId = $dados['message']['chat']['id'];
        $texto = $dados['message']['text'];

        preg_match('/(\d+)/', $texto, $matches);
        $valorEncontrado = $matches[1] ?? null;

        if($valorEncontrado){
            $deposito = Depositos::where('valor', $valorEncontrado)->where('pago', 0)->first();

            if($deposito){
                $deposito->update(['pago' => 1]);

                $Faltam = Depositos::where('pago', 0)->sum('valor');

                $mensagem = "Depósito de R$" . $valorEncontrado . " pago com sucesso! Faltam R$" . $Faltam . " para completar a meta.";
            } else {
                $mensagem = "Depósito de R$" . $valorEncontrado . " não encontrado ou já foi pago.";
            }
        } else {
            $mensagem = "Por favor, envie uma mensagem com o valor do depósito que deseja pagar.";
        }

        $this->sendMessage($chatId, $mensagem);
        return response('OK', 200);
    
    }
    private function sendMessage($chatId, $mensagem)
    {
        $botToken = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        Http::post($url, [
            'chat_id' => $chatId,
            'text' => $mensagem
        ]);
    }
}
