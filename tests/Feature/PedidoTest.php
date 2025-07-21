<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Pedido;
use App\Models\Produto;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    /** @test */
    public function pode_listar_pedidos()
    {
        Pedido::factory()->count(2)->create();

        $this->get('/pedidos')
            ->assertStatus(200)
            ->assertViewHas('pedidos');
    }

    /** @test */
    public function pode_criar_pedido()
    {
        $produto = Produto::factory()->create();

        $data = [
            'nome_cliente' => 'Maria Teste',
            'total' => 200,
            'frete' => json_encode(['valor' => 15, 'cep' => '12345-678']),
            'produtos' => [
                [
                    'produto_id' => $produto->id,
                    'quantidade' => 3
                ]
            ]
        ];

        $this->post('/pedidos', $data)
            ->assertStatus(200);

        $this->assertDatabaseHas('pedidos', ['nome_cliente' => 'Maria Teste']);
    }

    /** @test */
    public function pode_atualizar_status_do_pedido()
    {
        $pedido = Pedido::factory()->create(['status' => 0]);

        $this->put(route('pedidos.update', $pedido->id), [
            'status' => 1
        ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'status' => 'Pago', //Agora verificando o texto legível
                'mensagem' => "Status atualizado com sucesso para Pago!"
            ]);

        $this->assertDatabaseHas('pedidos', [
            'id' => $pedido->id,
            'status' => 1
        ]);
    }

}
