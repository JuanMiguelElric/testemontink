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
    $produtos = Produto::with('estoques')->get();

    $produtoList = [];

    foreach($produtos as $produto){
        $quantidadeTotal = $produto->estoques->sum('quantidade'); // soma o campo 'quantidade' de cada estoque relacionado

        $produtoList[] = [
            "id"=>$produto->id,
            "nome" => $produto->nome,
            "preco" => $produto->preco,
            'variacoes'     => json_decode($produto->variacoes, true),
            "variacoestype" => $produto->variacoestype,
            "quantidade" => $quantidadeTotal,
        ];
    }

    return response()->json($produtoList);
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


        return response()->json([
            'id'            => $produto->id,
            'nome'          => $produto->nome,
            'preco'         => $produto->preco,
            'variacoes' => $produto->variacoes,
            "variacoestype" => $produto->variacoestype,
          
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, Produto $produto)
        {
            $data = $request->validate([
                "nome" => "required|string",
                "preco" => "required|numeric",
                "variacoestype" => "required|string",
                "variacoes" => "nullable|json",
            ]);

            if ($produto->update($data)) {
                $this->editarEstoque($data["variacoes"] ?? null, $produto->id);

                return response()->json([
                    'success' => "editado com sucesso"
                ], 200); // ✅ Corrigido para 200
            }

            return response()->json(['success' => false], 500);
        }

         protected function editarEstoque($variacoes, $produtoId)
        {
            if (!$variacoes) {
                return;
            }

            //  Decodifica para array
            $variacoes_array = json_decode($variacoes, true);

            if (!is_array($variacoes_array)) {
                return;
            }

            //  Calcula o total corretamente
            $total = collect($variacoes_array)->sum('quantidade');

            //  Atualiza ou cria estoque
            $estoque = Estoque::firstOrNew(['produto_id' => $produtoId]);
            $estoque->quantidade = $total;
            $estoque->save();
        }



   


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produto $produto)
    {
        //
    }
}
