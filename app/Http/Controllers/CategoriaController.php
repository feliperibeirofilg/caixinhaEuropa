<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $categorias = Categoria::when($request->query('tipo'), function ($query, $tipo) {
            $query->where('tipo', $tipo);
        })->orderBy('nome')->get();

        return response()->json($categorias);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:receita,despesa',
            'cor' => 'nullable|string|max:7',
            'icone' => 'nullable|string|max:255',
        ]);

        $categoria = Categoria::create($dados);

        return response()->json($categoria, 201);
    }

    public function update(Request $request, Categoria $categoria)
    {
        $dados = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|in:receita,despesa',
            'cor' => 'nullable|string|max:7',
            'icone' => 'nullable|string|max:255',
        ]);

        $categoria->update($dados);

        return response()->json($categoria);
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();

        return response()->json(null, 204);
    }
}
