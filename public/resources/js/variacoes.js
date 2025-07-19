var idContador = 0;  

function excluir(id) {  
    $('#' + id).remove(); // Remove diretamente o campo pelo ID  
    idContador--; // Decrementa contador após a exclusão  
    $('.quantidade').val(idContador); // Atualiza campo quantidade  
}  

$(document).ready(function(){  
    $("#Adicionar_campo").click(function(e){  
        e.preventDefault();  
        var tipoCampo = "text";  
        AdicionarCampo(tipoCampo);  
    });  

   function AdicionarCampo(tipo) {
        idContador++;
        const idCampo = "CampoExtra" + idContador;
        const html = `
            <div class="w-100 d-flex mt-3" id="${idCampo}">
            <input
                class="w-50 flex-grow-1 form-control mr-2"
                placeholder="Variação"
                name="variacoes[${idContador}][nome]"
            />
            <input
                type="number"
                class="w-25 flex-grow-1 form-control mr-2"
                placeholder="Quantidade"
                name="variacoes[${idContador}][quantidade]"
            />
            <button
                class="btn btn-danger py-2"
                type="button"
                onclick="excluir('${idCampo}')"
            >Apagar</button>
            </div>
        `;
        $('.quantidade').val(idContador); // hidden, p.ex.
        $("#imendaHtml" + tipo).append(html);
        }

});