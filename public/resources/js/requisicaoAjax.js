function requisicaoAjax(idForm, type, url, data, idTable) {
    // console.log(
    //     "idForm: "+ idForm,
    //     "type: "+ type,
    //     "url: "+ url,
    //     "data: "+ data,
    //     "idTable: "+ idTable,
    //     )
    if (idForm != false) {
        var form = $(idForm);
    }
    var Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
    });

    $.ajax({
        type: type,
        url: url,
        data: data,
        success: function (response) {
            // Lógica de sucesso
            Toast.fire({
                icon: response.type,
                title: response.message,
            });
            $(idTable).DataTable().ajax.reload();
            if (idForm != false) {
                form[0].reset();
            }
            return true;
        },
        error: function (xhr, status, error) {
            // Lógica de erro
            console.log(xhr.responseJSON);
            if (xhr.responseJSON.type == "error") {
                $.each(xhr.responseJSON.errors, function (key, value) {
                    toastr.error(value);
                });
                return false;
            }
            $.each(xhr.responseJSON.errors, function (key, value) {
                toastr.warning(value);
            });
            return false;
        },
    });
    return true;
}
