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

    carrinho.forEach(item => {
        const itemTotal = item.preco * item.quantidade;
        subtotal += itemTotal;
        container.innerHTML += `
            <div class="mb-2 p-2 border rounded">
                <strong>${item.nome}</strong><br>
                Variação: ${item.variacao}<br>
                Qtd: ${item.quantidade} | R$ ${itemTotal.toFixed(2)}
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

// --- Calcular frete seguindo sua regra ---
function calcularFrete() {
    const cep = document.getElementById('cep').value;
    if (!cep) {
        alert("Digite um CEP válido.");
        return;
    }

    // ViaCEP - preencher logradouro e cidade
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {
            if (data.erro) {
                alert("CEP não encontrado!");
                return;
            }
            document.getElementById('logradouro').value = data.logradouro;
            document.getElementById('cidade').value = data.localidade;

            // Calcula frete
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

// --- Aplicar Cupom (simulação de API) ---
function aplicarCupom() {
    const cupom = document.getElementById('cupom').value;
    if (!cupom) {
        alert("Digite um cupom.");
        return;
    }

    // Simulação: troque pela sua API real
    fetch(`/api/cupom/${cupom}`)
        .then(res => res.json())
        .then(data => {
            if (data.valido) {
                descontoPercentual = data.desconto; // ex: 10 (10%)
                alert(`Cupom aplicado! Desconto de ${descontoPercentual}%`);
            } else {
                descontoPercentual = 0;
                alert("Cupom inválido!");
            }
            atualizarCarrinho();
        })
        .catch(() => {
            descontoPercentual = 0;
            alert("Erro ao validar o cupom!");
        });
}

// Inicializa
document.addEventListener('DOMContentLoaded', atualizarCarrinho);
