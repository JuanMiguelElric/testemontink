let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
let descontoPercentual = 0;
let freteAtual = 0;
const modalCarrinho = new bootstrap.Modal(document.getElementById('modalCarrinho'));

// --- Abrir modal e carregar variações ---
function abrirModalCarrinho(id, nome, preco, variacoesJson) {
    document.getElementById('carrinho_produto_id').value = id;
    document.getElementById('carrinho_produto_nome').value = nome;

    const select = document.getElementById('carrinho_variacao');
    select.innerHTML = '';

    try {
        const variacoes = JSON.parse(variacoesJson);
        variacoes.forEach(v => {
            const opt = document.createElement('option');
            opt.value = JSON.stringify({ nome: v.nome, preco: preco });
            opt.textContent = `${v.nome} (Disponível: ${v.quantidade})`;
            select.appendChild(opt);
        });
    } catch (e) {
        console.error("Erro ao carregar variações", e);
    }

    modalCarrinho.show();
}

// --- Adicionar produto ao carrinho ---
function adicionarAoCarrinho() {
    const id = document.getElementById('carrinho_produto_id').value;
    const nome = document.getElementById('carrinho_produto_nome').value;
    const variacao = JSON.parse(document.getElementById('carrinho_variacao').value);
    const quantidade = parseInt(document.getElementById('carrinho_quantidade').value);

    carrinho.push({
        id, nome,
        variacao: variacao.nome,
        preco: parseFloat(variacao.preco),
        quantidade
    });

    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    atualizarCarrinho();
    modalCarrinho.hide();
}

// --- Atualizar listagem do carrinho ---

// --- Atualizar listagem do carrinho e valores ---

function atualizarCarrinho() {
    const container = document.getElementById('carrinho_itens');
    container.innerHTML = '';

    if (carrinho.length === 0) {
        container.innerHTML = "<p>Carrinho vazio.</p>";
        document.getElementById('carrinho_subtotal').innerText = "0,00";
        document.getElementById('carrinho_frete').innerText = "0,00";
        document.getElementById('carrinho_desconto').innerText = "0,00";
        document.getElementById('carrinho_total').innerText = "0,00";
        return;
    }

    let subtotal = 0;
    carrinho.forEach((item, index) => {
        const itemTotal = item.preco * item.quantidade;
        subtotal += itemTotal;

        container.innerHTML += `
            <div class="mb-2 p-2 border rounded">
                <strong>${item.nome}</strong><br>
                Variação: ${item.variacao}<br>
                Qtd: ${item.quantidade} | R$ ${itemTotal.toFixed(2)}
                <button class="btn btn-sm btn-danger mt-1" onclick="removerDoCarrinho(${index})">Remover</button>
            </div>
        `;
    });

    const desconto = subtotal * (descontoPercentual / 100);
    const total = subtotal + freteAtual - desconto;

    document.getElementById('carrinho_subtotal').innerText = subtotal.toFixed(2);
    document.getElementById('carrinho_frete').innerText = freteAtual.toFixed(2);
    document.getElementById('carrinho_desconto').innerText = desconto.toFixed(2);
    document.getElementById('carrinho_total').innerText = total.toFixed(2);
}

function removerDoCarrinho(index) {
    carrinho.splice(index, 1);
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    atualizarCarrinho();
}

function calcularFrete() {
    const cep = document.getElementById('cep').value;
    if (!cep) {
        alert("Digite um CEP válido.");
        return;
    }

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {
            if (data.erro) {
                alert("CEP não encontrado!");
                return;
            }
            document.getElementById('logradouro').value = data.logradouro;
            document.getElementById('cidade').value = data.localidade;

            const subtotal = parseFloat(document.getElementById('carrinho_subtotal').innerText);

            if (subtotal > 200) {
                freteAtual = 0;
            } else if (subtotal >= 52 && subtotal <= 166.59) {
                freteAtual = 15;
            } else {
                freteAtual = 20;
            }
            atualizarCarrinho();
        })
        .catch(() => alert("Erro ao consultar o CEP!"));
}

function aplicarCupom() {
    const cupom = document.getElementById('cupom').value;
    const subtotal = parseFloat(document.getElementById('carrinho_subtotal').innerText);

    if (!cupom) {
        abrirModalMensagem("alerta", "Aviso", "Digite um cupom antes de continuar.");
        return;
    }

    fetch('/cupons/verificar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ codigo: cupom, subtotal })
    })
    .then(res => res.json())
    .then(resp => {
        if (!resp.valido) {
            // Diferencia mensagens
            if (resp.motivo === 'vencido') {
                abrirModalMensagem("erro", "Cupom Vencido", resp.mensagem);
            } else if (resp.motivo === 'valor_minimo') {
                abrirModalMensagem("alerta", "Valor Mínimo", resp.mensagem);
            } else {
                abrirModalMensagem("erro", "Erro", resp.mensagem);
            }
            return;
        }

        descontoPercentual = resp.desconto;
        abrirModalMensagem("sucesso", "Cupom Aplicado", `Desconto de ${descontoPercentual}% aplicado com sucesso!`);
        atualizarCarrinho();
    })
    .catch(() => abrirModalMensagem("erro", "Erro", "Erro ao validar cupom!"));
}


function abrirModalMensagem(tipo, titulo, texto) {
    const modalContent = document.getElementById('modalMensagemContent');
    const modalTitulo = document.getElementById('modalMensagemTitulo');
    const modalTexto = document.getElementById('modalMensagemTexto');

    modalContent.className = "modal-content text-white"; // reset base

    if (tipo === "sucesso") {
        modalContent.classList.add("bg-success");
    } else if (tipo === "erro") {
        modalContent.classList.add("bg-danger");
    } else if (tipo === "alerta") {
        modalContent.classList.add("bg-warning", "text-dark");
    } else {
        modalContent.classList.add("bg-secondary");
    }

    modalTitulo.innerText = titulo;
    modalTexto.innerText = texto;

    const modal = new bootstrap.Modal(document.getElementById('modalMensagem'));
    modal.show();
}


function finalizarCompra() {
    const nome_cliente = document.getElementById('nome_cliente').value;
    if (!nome_cliente) {
        abrirModalMensagem("alerta", "Aviso", "Digite o nome do cliente.");
        return;
    }

    if (carrinho.length === 0) {
        abrirModalMensagem("alerta", "Aviso", "Carrinho vazio!");
        return;
    }

    const payload = {
        nome_cliente,
        total: parseFloat(document.getElementById('carrinho_total').innerText),
        frete: JSON.stringify({
            valor: freteAtual,
            cep: document.getElementById('cep').value,
            logradouro: document.getElementById('logradouro').value,
            cidade: document.getElementById('cidade').value,
            numero: document.getElementById('numero').value
        }),
        cupon_id: cupon_id, // ✅ Enviamos o ID do cupom, se existir
        produtos: carrinho.map(item => ({
            produto_id: item.id,
            quantidade: item.quantidade
        }))
    };

    fetch('/pedidos', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(resp => {
        abrirModalMensagem("sucesso", "Pedido Finalizado", "Pedido Nº " + resp.id + " finalizado com sucesso!");
        carrinho = [];
        localStorage.removeItem('carrinho');
        atualizarCarrinho();


        setTimeout(() => {
            window.location.href = "/pedidos";
        }, 2000);
    })
    .catch(() => abrirModalMensagem("erro", "Erro", "Erro ao finalizar o pedido!"));
}

// Inicializa
document.addEventListener('DOMContentLoaded', atualizarCarrinho);


