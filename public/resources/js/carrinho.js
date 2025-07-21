let carrinho = JSON.parse(localStorage.getItem('carrinho') || '[]');
let descontoPercentual = 0;
let freteAtual = 0;

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
        alert("Digite um cupom.");
        return;
    }

    fetch('/api/cupons/verificar', {
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
            alert(resp.mensagem);
            return;
        }
        descontoPercentual = resp.desconto;
        alert(`Cupom aplicado! Desconto de ${descontoPercentual}%`);
        atualizarCarrinho();
    })
    .catch(() => alert("Erro ao validar cupom!"));
}

function finalizarCompra() {
    const nome_cliente = document.getElementById('nome_cliente').value;
    if (!nome_cliente) {
        alert("Digite o nome do cliente.");
        return;
    }
    if (carrinho.length === 0) {
        alert("Carrinho vazio!");
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
    .then(res => {
        if (!res.ok) throw new Error('Erro ao finalizar pedido');
        return res.json();
    })
    .then(resp => {
        alert("Pedido finalizado com sucesso! Nº do pedido: " + resp.id);
        carrinho = [];
        localStorage.removeItem('carrinho');
        atualizarCarrinho();
    })
    .catch(err => alert(err.message));
}

document.addEventListener('DOMContentLoaded', atualizarCarrinho);
