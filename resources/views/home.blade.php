@extends("welcome")

@section('content')
<!-- Modal for creatting product -->
<div class="row">
    <div class="col-12 p-3 w-100">

         <div class="d-flex justify-content-end py-4">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Adicionar produto</button>

        </div>

        <div id="alertSucesso" class="alert alert-success d-none" role="alert" style="z-index: 2000">
          ✅ Produto atualizado com sucesso!
        </div>



        @if ($errors->any())  
    <ul id="error-list" class="p-3 w-25 d-flex flex-column btn-danger">
        @foreach ($errors->all() as $error)  
            <li>{{$error}}</li>
        @endforeach  
    </ul>

    <script>
        // Aguarda 5 segundos (5000 ms) e esconde a lista de erros
        setTimeout(() => {
            const errorList = document.getElementById('error-list');
            if (errorList) {  
                        errorList.style.transition = "opacity 0.5s ease"; // Para uma transição suave  
                        errorList.style.opacity = 0; // Fades out the alert  
                        setTimeout(function() {  
                            alert.remove(); // Remove o alert do DOM após a animação  
                        }, 500); // Tempo para terminar a animação  
                    }  
        }, 2000);
    </script>
@endif


        @if (session('success'))  
            <div id="alert" class="w-25" >  
                <div class="p-3 bg-success border-rounded">
                    {{ session('success') }}  
                    
                </div>
            </div>  
        
            <script>  
                // Espera 5 segundos (5000 milissegundos) antes de remover o alerta  
                setTimeout(function() {  
                    var alert = document.getElementById('alert');  
                    if (alert) {  
                        alert.style.transition = "opacity 0.5s ease"; // Para uma transição suave  
                        alert.style.opacity = 0; // Fades out the alert  
                        setTimeout(function() {  
                            alert.remove(); // Remove o alert do DOM após a animação  
                        }, 500); // Tempo para terminar a animação  
                    }  
                }, 2000); // Tempo em milissegundos para manter o alerta visível  
            </script>  
        @endif  


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-4">
     <div class="text-center">
        <h1 class="text-success">Cadastrar Produto</h1>
     </div>

     <div>
        <form action="{{route("produtos.store")}}" method="POST">
            @csrf
      
            <div class="row">

                <div class="col-12">
                     <div class="form-group">
                        <label for="exampleInputEmail1">Nome do produto:</label>
                        <input type="text" class="form-control" name="nome"  placeholder="Insira aqui o nome do produto:">
                   
                    </div>
                </div>
                <div class="col-12">
                     <div class="form-group">
                        <label for="exampleInputEmail1">Preço:</label>
                        <input type="text" class="form-control" name="preco"  placeholder="00,00">
                   
                    </div>
                </div>
    
                <div class="col-12">
                    <label for="exampleInputEmail1">Variações:</label>
                    <select class="form-control" name="variacoestype" >
                        <option selected>Escolha aqui um tipo de variação</option>
                        <option value="Tamanho">Tamanho</option>
                        <option value="Cor">Cor</option>
                        <option value="Modelo">Modelo</option>
                    </select>
                </div>

                <input type="text"  class="quantidade d-none">
                <div class="col-12 my-3 registarper" >
                    
                    <div class=" w-100  align-items-center">  
            
                        <a href="#" id="Adicionar_campo" class="ml-2">Adicionar variações</i></a>  
                    </div>
                    <div  id="imendaHtmltext"></div>
                </div>
            </div>

            <button class="btn-success" type="submit">Salvar produto</button>
        </form>
     </div>


     <script src="{{asset("resources/js/variacoes.js")}}"></script>
    </div>
  </div>
</div>
    
    </div>

    <div class="col-12">
        <div class="container mt-4">
            <table class="table table-dark table-striped">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Variações</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
                </thead>
                <tbody id="produtos-body">
                {{-- será preenchido por jQuery --}}
                </tbody>
            </table>
            </div>


<!-- Modal (pode esconder por padrão e mostrar via JS) -->
        <!-- Modal -->
       <!-- Modal de Edição -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm" class="modal-content">
                @csrf
                @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="produto_id">

                <div class="mb-3">
                <label for="edit_nome" class="form-label">Nome</label>
                <input type="text" id="edit_nome" name="nome" class="form-control" required>
                </div>

                <div class="mb-3">
                <label for="edit_preco" class="form-label">Preço</label>
                <input type="number" id="edit_preco" name="preco" class="form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                <label for="edit_variacoestype" class="form-label">Variações</label>
                <select id="edit_variacoestype" class="form-control" required>
                    <option value="">-- selecione --</option>
                    <option value="Tamanho">Tamanho</option>
                    <option value="Cor">Cor</option>
                    <option value="Modelo">Modelo</option>
                </select>
                </div>

                <!-- Aqui entram as variações detalhadas -->
                <div class="mb-3">
                <label class="form-label">Variações Detalhadas</label>
                <a href="#" id="edit_Adicionar_campo" class="btn btn-sm btn-secondary mb-2">
                    Adicionar variação
                </a>
                <!-- campo oculto para contar quantos foram criados -->
                <input type="hidden" class="quantidade_edit d-none">
                <div id="imendaHtmltext_edit"></div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            </form>
        </div>
        </div>







    </div>

</div>

<div class="modal fade" id="modalCarrinho" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Adicionar ao Carrinho</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="carrinho_produto_id">

        <div class="mb-3">
          <label class="form-label">Produto</label>
          <input type="text" id="carrinho_produto_nome" class="form-control" readonly>
        </div>

        <div class="mb-3">
          <label class="form-label">Variação</label>
          <select id="carrinho_variacao" class="form-select"></select>
        </div>

        <div class="mb-3">
          <label class="form-label">Quantidade</label>
          <input type="number" id="carrinho_quantidade" class="form-control" value="1" min="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="adicionarAoCarrinho()">Adicionar</button>
      </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="carrinhoOffcanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Meu Carrinho</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <div id="carrinho_itens"></div>
    <hr>

    <h6>Resumo do Pedido</h6>
    <p>Subtotal: R$ <span id="carrinho_subtotal">0,00</span></p>
    <p>Frete: R$ <span id="carrinho_frete">0,00</span></p>
    <p>Desconto: R$ <span id="carrinho_desconto">0,00</span></p>
    <h5>Total: R$ <span id="carrinho_total">0,00</span></h5>

    <hr>
    <div class="mb-3">
      <label class="form-label">CEP</label>
      <input type="text" id="cep" class="form-control" placeholder="Digite o CEP">
    </div>
    <div class="mb-3">
      <label class="form-label">Logradouro</label>
      <input type="text" id="logradouro" class="form-control" readonly>
    </div>
    <div class="mb-3">
      <label class="form-label">Cidade</label>
      <input type="text" id="cidade" class="form-control" readonly>
    </div>
    <div class="mb-3">
      <label class="form-label">Número</label>
      <input type="text" id="numero" class="form-control" placeholder="Digite o número">
    </div>
    <button class="btn btn-success w-100 mb-3" onclick="calcularFrete()">Calcular Frete</button>

    <hr>
    <div class="mb-3">
      <label class="form-label">Cupom de Desconto</label>
      <input type="text" id="cupom" class="form-control" placeholder="Digite seu cupom">
    </div>
    <button class="btn btn-primary w-100" onclick="aplicarCupom()">Aplicar Cupom</button>
  </div>
</div>

<!-- Botão fixo para abrir o carrinho -->
<button class="btn btn-primary position-fixed" style="bottom:20px; right:20px; z-index:2000" 
        data-bs-toggle="offcanvas" data-bs-target="#carrinhoOffcanvas">
  🛒 Carrinho
</button>



        <script src="{{asset('resources/js/requisicaoAjax.js')}}"></script>
        <script src="{{ asset('resources/js/variacoes.js') }}"></script>
        <script src="{{asset('resources/js/modalEdicao.js')}}"></script>
        <script src="{{asset('resources/js/submitform.js')}}"></script>
        <script src="{{asset('resources/js/carrinho.js')}}"></script>


 
       


@endsection