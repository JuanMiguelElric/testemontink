<?php

namespace App\Http\Controllers\Cupom;

use App\Http\Controllers\Controller;
use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("cupons");
    }


    public function CuponsJson(){
        return \App\Models\Cupom::all();
    }

    // agora preciso da sua ajuda para criar uma página com um botão que abra um modal para criar um cupom,onde tenha campos codigo desconto e validade. quero que nessa pagina também crie uma tabela onde irá receber por uma api todos os cupons criados. Lembrando que estou usando o blade.php

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           $data = $request->validate([
                'codigo' => 'required|string|unique:cupons,codigo',
                'desconto' => 'required|numeric|min:1|max:100',
                'valor_minimo' => 'required|numeric|min:0',
                'validade' => 'required|date',
             ]);

   

        $cupom = new Cupom($data);
        if($cupom->save()){
            return response()->json(["success"=>true]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function verificarCupom(Request $request){
         $data = $request->validate([
        'codigo' => 'required|string',
        'subtotal' => 'required|numeric'
    ]);

    $cupom = Cupom::where('codigo', $data['codigo'])->first();

    if (!$cupom) {
        return response()->json([
            'valido' => false,
            'motivo' => 'nao_encontrado',
            'mensagem' => 'Cupom não encontrado.'
        ], 404);
    }

    if (now()->gt($cupom->validade)) {
        return response()->json([
            'valido' => false,
            'motivo' => 'vencido',
            'mensagem' => 'Este cupom está vencido.'
        ], 400);
    }

    if ($data['subtotal'] < $cupom->valor_minimo) {
        return response()->json([
            'valido' => false,
            'motivo' => 'valor_minimo',
            'mensagem' => 'Este cupom exige um valor mínimo de R$ ' . number_format($cupom->valor_minimo, 2, ',', '.')
        ], 400);
    }

    return response()->json([
        'valido' => true,
        'desconto' => $cupom->desconto,
        'mensagem' => 'Cupom aplicado com sucesso!'
    ]);

    }
}
