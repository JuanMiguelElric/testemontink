@extends("welcome")

@section('content')
<!-- Modal for creatting product -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Large modal</button>

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


@endsection