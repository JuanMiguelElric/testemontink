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
                            class="btn btn-sm btn-danger me-1">
                            Excluir
                        </a>
                        <button class="btn btn-sm btn-success"
                            onclick="abrirModalCarrinho(${p.id}, '${p.nome}', '${p.preco}', '${JSON.stringify(p.variacoes).replace(/"/g, '&quot;')}')">
                            Carrinho
                        </button>
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



