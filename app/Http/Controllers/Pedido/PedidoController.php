<?php
namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoProduto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome_cliente' => 'required|string',
            'total' => 'required|numeric',
            'frete' => 'required|json',
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
                'status' => 'pendente',
                'data_pedido' => now(),
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
}
