<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FinancaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'categoria_id' => 'nullable|exists:categorias,id',
            'cartao_id' => [
                'nullable',
                Rule::exists('cartoes', 'id')->where('usuario_id', auth()->id()),
            ],
            'descricao' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
            'tipo' => 'required|in:receita,despesa',
            'forma_pagamento' => 'required|in:debito,credito,pix,dinheiro,boleto,transferencia',
            'data_compra' => 'required|date',
            'parcelas' => 'sometimes|integer|min:1',
            'parcela_atual' => 'sometimes|integer|min:1',
            'recorrente' => 'sometimes|boolean',
            'status' => 'sometimes|in:pendente,pago',
            'observacao' => 'nullable|string',
        ];
    }
}
