<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Método para listar todos os usuários
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Método para exibir o formulário de edição de um usuário
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = ['admin', 'bibliotecario', 'cliente']; // Defina os papéis possíveis
        return view('users.edit', compact('user', 'roles'));
    }

    // Método para atualizar o papel (role) de um usuário
    public function update(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:admin,bibliotecario,cliente', // Valida se o role é válido
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->input('role'); // Atualiza o papel do usuário
        $user->save();

        return redirect()->route('users.index')->with('success', 'Papel do usuário atualizado com sucesso!');
    }
}
