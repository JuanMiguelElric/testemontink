<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Estoque;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("home");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //recebimento do fronteend e tratamento dos dados recebidos
        $data = $request->validate([
            "nome"=>"required|string",
            "preco"=>"required|integer",
            "variacoestype"=>"required|string",
       
        ],[
            "nome.required"=>"Insira um nome.",
            "preco.required"=>"Insira um preço."
        ]);
        $variacoes = $request->input('variacoes.*');
        
        $variajson= json_encode($variacoes);

        $data["variacoes"]= $variajson;

        $produto = new Produto($data);

        if($produto->save()){

            $this->SalvarEstoque($variacoes, $produto->id);
          return redirect()->route('home')->with('success', 'Produto adicionado com sucesso');

        }
     


    
    }

    //para registar o Estoque
    protected function SalvarEstoque($array, $produto){

        $total = 0;
  
        $total = collect($array)->sum('quantidade');
      
        
        Estoque::create([
            "produto_id"=>$produto,
            "quantidade"=>$total

        ]);

  
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Produto $produto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produto $produto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        //
    }
}
