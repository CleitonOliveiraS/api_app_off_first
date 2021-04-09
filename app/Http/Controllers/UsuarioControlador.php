<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsuarioControlador extends ApiControlador
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $usuario = User::all();
            if (count($usuario) <= 0){
                return $this->errorResponse(null, 404);
            }
            return $usuario;
        }catch (\Exception $e){
            return $this->errorResponse('Falha na conexão', 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuario = $this->validarUsuario();

        if ($usuario->fails()){
            return $this->errorResponse($usuario->messages(), 422);
        }

        $usuario = User::create([
            'nome' => $request->get('nome'),
            'data_nascimento' => Carbon::createFromFormat('d/m/Y', $request->get('data_nascimento')),
            'cpf' => $request->get('cpf'),
            'id_setor' => $request->get('id_setor'),
        ]);

        return $this->successResponse($usuario, 'Usuario Criado', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return User::find($id);
        }catch (\Exception $e){
            return $this->errorResponse('Falha na conexão', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $usuario = $this->validarUsuarioUpdate($id);

        if ($usuario->fails()){
            return $this->errorResponse($usuario->messages(), 422);
        }

        try {
            $usuario = User::findOrFail($id);
            $usuario->update([
                'nome' => $request->get('nome'),
                'data_nascimento' => Carbon::createFromFormat('d/m/Y', $request->get('data_nascimento')),
                'cpf' => $request->get('cpf'),
                'id_setor' => $request->get('id_setor'),
            ]);
            return $this->successResponse($usuario, 'Usuario atualizado com sucesso', 200);
        }catch (\Exception $e){
            return $this->errorResponse('Falha na conexão', 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $usuario = User::findOrFail($id);
            $usuario->delete();
            return $this->successResponse($usuario, 'Usuario deletado com sucesso', 200);
        }catch (\Exception $e){
            return $this->errorResponse('Falha na conexão', 500);
        }
    }

    public function validarUsuario(){
        return Validator::make(request()->all(), [
            'nome' => 'required',
            'data_nascimento' => 'required',
            'cpf' => 'required|unique:users',
            'id_setor' => 'required',
        ], [
            'nome.required' => 'Por favor envie o nome',
            'data_nascimento.required' => 'Por favor envie a data de nascimento',
            'cpf.required' => 'Por favor envie o CPF',
            'cpf.unique' => 'O CPF já existe',
            'id_setor' => 'Por favor envie o setor'
        ]);
    }

    public function validarUsuarioUpdate($id){
        return Validator::make(request()->all(), [
            'nome' => 'required',
            'data_nascimento' => 'required',
            'cpf' => ['required', Rule::unique('users', 'cpf')->ignore($id)],
            'id_setor' => 'required',
        ], [
            'nome.required' => 'Por favor envie o nome',
            'data_nascimento.required' => 'Por favor envie a data de nascimento',
            'cpf.required' => 'Por favor envie o CPF',
            'cpf.unique' => 'O CPF já existe',
            'id_setor' => 'Por favor envie o setor'
        ]);
    }
}
