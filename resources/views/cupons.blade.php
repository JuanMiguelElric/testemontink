@extends("welcome")

@section('content')
    <div class="container">
        <h1>Gerenciar Cupons</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCupom">Adicionar Cupom</button>

        <table class="table table-dark table-striped table-hover">

            <thead>
                <tr>
                    <th>Código</th>
                    <th>Desconto (%)</th>
                    <th>Validade</th>
                </tr>
            </thead>
            <tbody id="tabela-cupons">
                <tr><td colspan="3">Carregando...</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Modal Criar Cupom -->
    <div class="modal fade" id="modalCupom" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Adicionar Cupom</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="formCupom">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Código</label>
                    <input type="text" class="form-control" id="codigo" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Desconto (%)</label>
                    <input type="number" class="form-control" id="desconto" min="1" max="100" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Valor mínimo (R$)</label>
                    <input type="number" class="form-control" id="valor_minimo" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Validade</label>
                    <input type="date" class="form-control" id="validade" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="salvarCupom()">Salvar</button>
        </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', carregarCupons);

        function carregarCupons() {
            fetch('/cupon/json')
            .then(res => res.json())
            .then(cupons => {
                const tbody = document.getElementById('tabela-cupons');
                tbody.innerHTML = '';
                cupons.forEach(c => {
                    tbody.insertAdjacentHTML('beforeend', `
                        <tr>
                            <td>${c.codigo}</td>
                            <td>${c.desconto}%</td>
                            <td>${new Date(c.validade).toLocaleDateString()}</td>
                        </tr>
                    `);
                });
            })
            .catch(() => {
                document.getElementById('tabela-cupons').innerHTML = '<tr><td colspan="3">Erro ao carregar cupons</td></tr>';
            });
        }

        function salvarCupom() {
            const data = {
                codigo: document.getElementById('codigo').value,
                desconto: document.getElementById('desconto').value,
                valor_minimo: document.getElementById('valor_minimo').value,
                validade: document.getElementById('validade').value
            };

            fetch('/cupons', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(res => {
                if (!res.ok) throw new Error('Erro ao salvar cupom');
                return res.json();
            })
            .then(() => {
                document.getElementById('formCupom').reset();
                $('#modalCupom').modal('hide');
                carregarCupons();
            })
            .catch(err => alert(err.message));
        }


    </script>
@endsection
