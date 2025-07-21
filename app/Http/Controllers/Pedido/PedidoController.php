<?php
namespace App\Http\Controllers\Pedido;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoProduto;
use App\Models\Cupom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{

    
    public function index(){
         $pedidos = Pedido::with('Cupom')->latest()->get();
        return view('pedidos', compact('pedidos'));

    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome_cliente' => 'required|string',
            'total' => 'required|numeric',
            'frete' => 'required|json',
            'cupon_id' => 'nullable|integer|exists:cupons,id', // ✅ opcional
            'produtos' => 'required|array',
            'produtos.*.produto_id' => 'required|integer|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1'
        ]);

       

        DB::beginTransaction();
        try {
            $pedido = Pedido::create([
                'nome_cliente' => $data['nome_cliente'],
                'total' => $data['total'],
                'frete' => $data['frete'],
                'status' => 0,
                
                'cupon_id' => $data['cupon_id'] ?? null, // ✅ Se não vier, será NULL
            ]);

         

            foreach ($data['produtos'] as $produto) {
                PedidoProduto::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $produto['produto_id'],
                    'quantidade' => $produto['quantidade'],
                ]);
            }

            DB::commit();
            return response()->json($pedido);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

      public function show(Pedido $pedido)
    {
        $pedido->load(['pedido_produtos.Produto', 'Cupom']);

        return response()->json([
            'id' => $pedido->id,
            'nome_cliente' => $pedido->nome_cliente,
            'total' => $pedido->total,
            'status' => $pedido->status_texto, // Texto legível com accessor
   
            'cupom' => $pedido->Cupom,
            'produtos' => $pedido->pedido_produtos->map(function ($pp) {
                return [
                    'quantidade' => $pp->quantidade,
                    'produto' => [
                        'nome' => $pp->Produto->nome,
                        'preco' => $pp->Produto->preco
                    ]
                ];
            })
        ]);
    }

    public function update(Request $request, Pedido $pedido)
    {
        $data = $request->validate([
            'status' => 'required|integer|in:0,1,2,3'
        ]);

        $pedido->update(['status' => $data['status']]);

        // ✅ Recarrega os dados para garantir que o Accessor use o valor atualizado
        $pedido->refresh();

        return response()->json([
            'success' => true,
            'status' => $pedido->status_texto,
            'mensagem' => "Status atualizado com sucesso para {$pedido->status_texto}!"
        ], 200);
    }




}
