@extends("welcome")

@section('content')
<!-- Modal for creatting product -->
<div class="row">
    <div class="col-12 p-3 w-100">

         <div class="d-flex justify-content-end py-4">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button>

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
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                </div>
                <div class="col-12">
                     <div class="form-group">
                        <label for="exampleInputEmail1">Preço:</label>
                        <input type="text" class="form-control" name="preco"  placeholder="00,00">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
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



        <script src="{{asset('resources/js/requisicaoAjax.js')}}"></script>
        <script src="{{ asset('resources/js/variacoes.js') }}"></script>


        <script>
  // guarda instância do Bootstrap Modal
        let editModal = new bootstrap.Modal(document.getElementById('editModal'));

        // carrega a tabela
        function loadProdutos() {
            fetch('/produtos')
            .then(res => res.json())
            .then(lista => {
                const tbody = document.getElementById('produtos-body');
                tbody.innerHTML = '';
                lista.forEach(p => {
                tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                    <td>${p.nome}</td>
                    <td>R$ ${parseFloat(p.preco).toFixed(2)}</td>
                    <td>${p.variacoestype}</td>
                    <td>${p.quantidade}</td>
                    <td>
                        <a href="#"
                        class="btn btn-sm btn-warning me-1"
                        onclick="openEditModal(${p.id}); return false;">
                        Editar
                        </a>
                        <a href="/produtos/${p.id}/delete"
                        class="btn btn-sm btn-danger">
                        Excluir
                        </a>
                    </td>
                    </tr>
                `);
                });
            })
            .catch(console.error);
        }

        // abre o modal e popula campos
        // assume que editModal já foi instanciado como:


         // CONTADOR e funções de variações (do seu variacoes.js)
        var idContadorEdit = 0;

        function excluirEdit(id) {
            $('#' + id).remove();
            idContadorEdit--;
            $('.quantidade_edit').val(idContadorEdit);
        }
       function AdicionarCampoEdit(nome = '', quantidade = '') {
        idContadorEdit++;
        const idCampo = "CampoExtraEdit" + idContadorEdit;
        const html = `
        <div class="w-100 d-flex mt-3" id="${idCampo}">
            <input
                class="form-control me-2"
                placeholder="Variação"
                name="variacoes[${idContadorEdit}][nome]"
                value="${nome}"
            />
            <input
                type="number"
                class="form-control me-2"
                placeholder="Quantidade"
                name="variacoes[${idContadorEdit}][quantidade]"
                value="${quantidade}"
            />
            <button
                class="btn btn-danger"
                type="button"
                onclick="excluirEdit('${idCampo}')"
            >Apagar</button>
        </div>
        `;
        $('.quantidade_edit').val(idContadorEdit);
        $('#imendaHtmltext_edit').append(html);
    }


        // handler de “Adicionar variação”
        $(document).on('click', '#edit_Adicionar_campo', function(e) {
            e.preventDefault();
            AdicionarCampoEdit();
        });

        async function openEditModal(id) {
            try {
                const res = await fetch(`/produtos/${id}`);
                if (!res.ok) throw new Error(res.statusText);
                const p = await res.json();

                console.log('Produto carregado:', p); // Aqui vemos o conteúdo do produto

                // 1) Preencher campos básicos
                document.getElementById('produto_id').value = p.id;
                document.getElementById('edit_nome').value = p.nome;
                document.getElementById('edit_preco').value = p.preco;
                document.getElementById('edit_variacoestype').value = p.variacoestype;

                // 2) Limpar variações anteriores e resetar contador
                $('#imendaHtmltext_edit').empty();
                idContadorEdit = 0;
                $('.quantidade_edit').val(0);

                // 3) Verifique se p.variacoes é uma string ou outro formato
                  let variacoesArr = [];

                if (p.variacoes) {
                    try {
                        if (Array.isArray(p.variacoes)) {
                            variacoesArr = p.variacoes;
                        } else if (typeof p.variacoes === 'string') {
                            // Faz parse duas vezes se necessário (para strings encodadas 2x)
                            let temp = JSON.parse(p.variacoes);
                            variacoesArr = Array.isArray(temp) ? temp : JSON.parse(temp);
                        }
                        console.log("Variações carregadas:", variacoesArr);
                    } catch (e) {
                        console.warn('Erro ao parsear variacoes:', e);
                        variacoesArr = [];
                    }
                }


                  // Limpa antes de adicionar
                  $('#imendaHtmltext_edit').empty();
                  idContadorEdit = 0;
                  $('.quantidade_edit').val(0);

                  // Adiciona só as variações existentes
                  variacoesArr.forEach(v => {
                      AdicionarCampoEdit(v.nome, v.quantidade);
                  });


                // 4) Adicionar as variações no modal
             

                // 5) Abre o modal após preencher todas as variações
                editModal.show();
            } catch (err) {
                alert('Erro ao carregar produto: ' + err.message);
            }
            }





        // captura submissão do form e envia via PUT
            document.getElementById('editForm').addEventListener('submit', function(e) {
              e.preventDefault();

              const id = document.getElementById('produto_id').value;

              // Coleta das variações como array
              const variacoesArr = $('#imendaHtmltext_edit > div').map(function() {
                  return {
                      nome: $(this).find('input[name*="[nome]"]').val(),
                      quantidade: $(this).find('input[name*="[quantidade]"]').val()
                  };
              }).get();

              const formData = new FormData();
              formData.append('_method', 'PUT'); // ← O Laravel usa isso para aceitar PUT
              formData.append('nome', document.getElementById('edit_nome').value);
              formData.append('preco', document.getElementById('edit_preco').value);
              formData.append('variacoestype', document.getElementById('edit_variacoestype').value);
              formData.append('variacoes', JSON.stringify(variacoesArr));

              fetch(`/produtos/${id}`, {
                  method: 'POST', // ← Importante! Sempre POST quando usa _method
                  headers: {
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                  },
                  body: formData
              })
              .then(res => {
                  if (!res.ok) throw new Error('Status ' + res.status);
                  return res.json();
              })
              .then(() => {
                  editModal.hide();
                  // Mostra o alerta
                  const alertBox = document.getElementById('alertSucesso');
                  alertBox.classList.remove('d-none');

                  // Esconde o alerta depois de 3s
                  setTimeout(() => {
                      alertBox.classList.add('d-none');
                  }, 3000);

                  loadProdutos();

              })
              .catch(err => {
                  console.error('Erro ao salvar:', err);
              
              });
          });

        // inicializa
        document.addEventListener('DOMContentLoaded', loadProdutos);
        </script>




        
    

    </div>

</div>



 
       


@endsection