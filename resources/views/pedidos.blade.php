@extends('welcome')

@section('content')
<div class="container">
    <h1 class="text-white">Lista de Pedidos</h1>

    <table class="table table-dark table-hover">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Status</th>
                <th>Data</th>
                <th>Cupom</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidos as $pedido)
            <tr>
                <td>{{ $pedido->id }}</td>
                <td>{{ $pedido->nome_cliente }}</td>
                <td>R$ {{ number_format($pedido->total, 2, ',', '.') }}</td>
                <td>{{ ucfirst($pedido->status_texto) }}</td>
                <td>{{ \Carbon\Carbon::parse($pedido->data_pedido)->format('d/m/Y H:i') }}</td>
                <td>{{ $pedido->Cupom?->codigo ?? 'Nenhum' }}</td>
                <td>
                    <button 
                        class="btn btn-primary btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modalDetalhes"
                        data-pedido="{{ $pedido->id }}"
                    >
                        Ver Detalhes
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal para Detalhes do Pedido -->
<div class="modal fade" id="modalDetalhes" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header">
        <h5 class="modal-title">Detalhes do Pedido</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detalhesPedidoBody">
        Carregando detalhes...
      </div>
      <div class="modal-footer">
        <select id="statusPedido" class="form-select w-auto">
          <option value="0">Pendente</option>
          <option value="1">Pago</option>
          <option value="2">Cancelado</option>
          <option value="3">Cupom Expirado</option>
        </select>
        <button class="btn btn-success" id="btnAtualizarStatus">Atualizar Status</button>
      </div>
    </div>
  </div>
</div>

      <div class="modal fade" id="modalMensagem" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" id="modalMensagemContent">
            <div class="modal-header">
              <h5 class="modal-title" id="modalMensagemTitulo">Mensagem</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalMensagemTexto">
              ...
            </div>
            <div class="modal-footer">
              <button class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>

<script src="{{asset('resources/js/carrinho.js')}}"></script>
<script>
let pedidoAtualId = null;

document.addEventListener('DOMContentLoaded', function () {
    const modalDetalhes = document.getElementById('modalDetalhes');

    modalDetalhes.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        pedidoAtualId = button.getAttribute('data-pedido');

        const body = document.getElementById('detalhesPedidoBody');
        const statusSelect = document.getElementById('statusPedido');
        body.innerHTML = "Carregando detalhes...";

        fetch(`/pedidos/${pedidoAtualId}`)
            .then(res => res.json())
            .then(pedido => {
                // Preenche os detalhes
                let produtosHtml = '';
                pedido.produtos.forEach(p => {
                    produtosHtml += `
                        <div class="border-bottom py-2">
                            <strong>${p.produto.nome}</strong><br>
                            Quantidade: ${p.quantidade}<br>
                            Preço Unitário: R$ ${parseFloat(p.produto.preco).toFixed(2)}
                        </div>
                    `;
                });

                body.innerHTML = `
                    <p><strong>Cliente:</strong> ${pedido.nome_cliente}</p>
                    <p><strong>Total:</strong> R$ ${parseFloat(pedido.total).toFixed(2)}</p>
                    <p><strong>Status Atual:</strong> ${pedido.status}</p>
                    <p><strong>Data:</strong> ${new Date(pedido.data_pedido).toLocaleString()}</p>
                    <p><strong>Cupom:</strong> ${pedido.cupom ? pedido.cupom.codigo : 'Nenhum'}</p>
                    <hr>
                    <h6>Produtos</h6>
                    ${produtosHtml}
                `;

                // Ajusta o select de status com o valor atual
                switch (pedido.status) {
                    case "Pago": statusSelect.value = 1; break;
                    case "Cancelado": statusSelect.value = 2; break;
                    case "Cupom Expirado": statusSelect.value = 3; break;
                    default: statusSelect.value = 0;
                }
            })
            .catch(() => {
                body.innerHTML = "<p class='text-danger'>Erro ao carregar detalhes do pedido.</p>";
            });
    });

    //  Atualizar Status ao clicar
    document.getElementById('btnAtualizarStatus').addEventListener('click', function () {
        const status = document.getElementById('statusPedido').value;

        fetch(`/pedidos/${pedidoAtualId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status })
        })
        .then(res => res.json())
        .then(resp => {
            abrirModalMensagem("sucesso", "Status Atualizado", resp.mensagem);
            window.location.reload();
 
        })
        .catch(() => abrirModalMensagem("erro", "Erro", "Erro ao atualizar status do pedido!"));
    });
});

</script>
@endsection
