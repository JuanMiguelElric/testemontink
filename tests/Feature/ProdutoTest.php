<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Produto;

class ProdutoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    /** @test */
    public function pode_listar_produtos()
    {
        Produto::factory()->count(3)->create();

        $this->get(route('produtos.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'nome', 'preco', 'variacoestype', 'variacoes', 'quantidade']
            ]);
    }


    /** @test */
    public function pode_criar_produto()
    {
        $data = [
            'nome' => 'Produto Teste',
            'preco' => 99,
            'variacoestype' => 'Tamanho',
            'variacoes' => [
                ['nome' => 'P', 'quantidade' => 10]
            ]
        ];

        $this->post(route('produtos.store'), $data)
            ->assertStatus(302) //  redirect, pois usa Blade
            ->assertRedirect(route('home')); // opcional, se quiser validar o redirecionamento

        $this->assertDatabaseHas('produtos', ['nome' => 'Produto Teste']);
    }


    /** @test */
    public function pode_atualizar_produto()
    {
        $produto = Produto::factory()->create();

      $response =  $this->put("/produtos/{$produto->id}", [
            'nome' => 'Produto Atualizado',
            'preco' => 120.00,
            'variacoestype' => 'Cor',
            'variacoes' => json_encode([
                ['nome' => 'Azul', 'quantidade' => 5]
            ])
        ])
            ->assertStatus(200)
            ->assertJson(['success' => "editado com sucesso"]);

     


        $this->assertDatabaseHas('produtos', [
            'id' => $produto->id,
            'nome' => 'Produto Atualizado'
        ]);
 
     

    }

}
