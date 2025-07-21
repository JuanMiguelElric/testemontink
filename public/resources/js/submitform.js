
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