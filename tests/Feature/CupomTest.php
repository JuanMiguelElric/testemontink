<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Cupom;

class CupomTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    /** @test */
    public function pode_listar_cupons()
    {
        Cupom::factory()->count(3)->create();

        $this->get(route('cupom.json'))
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'codigo', 'desconto', 'validade']
            ]);
    }


    /** @test */
        public function pode_criar_cupom()
        {
            $data = [
                'codigo' => 'TESTE10',
                'desconto' => 10,
                'valor_minimo' => 50,
                'validade' => now()->addDays(7)->toDateString(),
            ];

            $this->post(route('cupons.store'), $data)
                ->assertStatus(201)
                ->assertJson(['success' => true]);

            $this->assertDatabaseHas('cupons', ['codigo' => 'TESTE10']);
        }



    /** @test */
    public function pode_verificar_cupom_valido()
    {
        $cupom = Cupom::factory()->create([
            'codigo' => 'DESCONTO20',
            'desconto' => 20,
            'validade' => now()->addDays(10),
        ]);

        $this->post('/cupons/verificar', [
            'codigo' => $cupom->codigo,
            'subtotal' => 100
        ])
            ->assertStatus(200)
            ->assertJson(['valido' => true]);
    }
}
