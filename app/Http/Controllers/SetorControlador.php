<?php

namespace App\Http\Controllers;

use App\Models\Setor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SetorControlador extends ApiControlador
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $setor = Setor::all();
            if (count($setor) <= 0){
                return $this->errorResponse('Setores não encontrado', 404);
            }
            return $setor;
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
        $setor = $this->validarSetor();

        if ($setor->fails()){
            return $this->errorResponse($setor->messages(), 422);
        }

        $setor = Setor::create([
            'nome' => $request->get('nome'),
        ]);

        return $this->successResponse($setor, 'Setor Criado', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $setor = $this->validarSetorUpdate($id);

        if ($setor->fails()){
            return $this->errorResponse($setor->messages(), 422);
        }

        try {
            $setor = Setor::findOrFail($id);
            $setor->update([
                'nome' => $request->get('nome'),
            ]);
            return $this->successResponse($setor, 'Setor atualizado com sucesso', 200);
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
            $setor = Setor::findOrFail($id);
            $setor->delete();
            return $this->successResponse($setor, 'Setor deletado com sucesso', 200);
        }catch (\Exception $e){
            return $this->errorResponse('Falha na conexão', 500);
        }
    }
    public function validarSetor(){
        return Validator::make(request()->all(), [
            'nome' => 'required',
        ], [
            'nome.required' => 'Por favor envie o nome',
        ]);
    }

    public function validarSetorUpdate($id){
        request()['nome'] = strtolower(request()['nome']);
        return Validator::make(request()->all(), [
            'nome' => ['required', Rule::unique('setores', 'nome')->ignore($id), ],
        ], [
            'nome.required' => 'Por favor envie o nome',
            'nome.unique' => 'O nome já existe'
        ]);
    }

}
