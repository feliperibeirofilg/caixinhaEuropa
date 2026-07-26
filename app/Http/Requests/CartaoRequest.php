<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Cartao;

class CartaoRequest extends FormRequest
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
            'nome'              => 'required|string|max:255',
            'tipo'              => 'required|in:credito,debito',
            'bandeira'          => 'nullable|string|max:255',
            'limite'            => 'nullable|numeric|min:0',
            'dia_fechamento'    => 'nullable|integer|between:1,31',
            'dia_vencimento'    => 'nullable|integer|between:1,31',
            'ativo'             => 'sometimes|boolean'
        ];
    }
}
