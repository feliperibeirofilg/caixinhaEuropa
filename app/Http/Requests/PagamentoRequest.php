<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PagamentoRequest extends FormRequest
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
            'fatura_cartao_id' => [
                'nullable',
                Rule::exists('faturas_cartao', 'id')->where(function ($query) {
                    $query->whereIn('cartao_id', auth()->user()->cartoes()->pluck('id'));
                }),
            ],
            'valor' => 'required|numeric|min:0',
            'data_pagamento' => 'required|date',
            'forma_pagamento' => 'nullable|in:pix,debito,boleto,dinheiro',
            'observacao' => 'nullable|string',
        ];
    }
}
