<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ContaMensal;

class ContaMensalRequest extends FormRequest
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
            'nome' => 'required|string|max:255',
            'valor_estimado' => 'nullable|numeric|min:0',
            'dia_vencimento' => 'required|integer|between:1,31',
            'tipo' => 'sometimes|in:fixa,variavel',
            'ativa' => 'sometimes|boolean',
            'observacao' => 'nullable|string',
        ];
    }
}
