$(function () {
    verifyLogin();
    atualizaGrid();
});

function atualizaGrid() {
    $("#tableProdutos").find("tbody tr").remove();
    $("#tableProdutos").find("tbody").append('<tr><td colspan=10><div class="alert alert-success"><img src="img/ajax-loader.gif">Carregando...</div></td></tr>');

    filtro = "";
    if ($("#filtrar").val()) {
        filtro = "/" + $("#filtrar").val();
    }

    $.ajax({
        type: "get",
        url: rootUrl + "agendamentos/listAll" + filtro,
        dataType: "json",
        success: function (data) {
            $("#tableSalas").find("tbody tr").remove();
            if (data.length !== 0) {
                data.result.forEach(function (salas) {
                    row = "<tr>"
                            + "<td class='text-center'>" + salas.nome + "</td>"
                            + "<td class='text-center'>" + salas.description + "</td>"
                            + "<td class='text-center'>" + formataData(salas.date) + "</td>"
                            + "<td class='text-center'><a href='#' data-toggle='tooltip' data-placement='top' title='Excluir'><span class='glyphicon glyphicon-trash' aria-hidden='true' data-id='" + salas.id + "' data-nome='" + salas.nome + "'/></span></a></td>"
                            + "</tr>";
                    $("#tableSalas > tbody:last").append(row);
                });
            } else {
                $("#tableSalas > tbody").append("<td class='text-center informacao' colspan='5'>Nenhuma informação encontrada.</td>");
            }
        },
        error: function (result) {
            $("#tableSalas").find("tbody tr").remove();
        }
    });
}

$("body").on("click", ".glyphicon-trash", function () {
    id = $(this).attr("data-id");
    nome = $(this).attr("data-nome");
    row = $(this);
    if (confirm("Excluir " + nome + "?")) {
        $.ajax({
            type: "post",
            url: rootUrl + "agendamentos/delete",
            dataType: "json",
            data: {id: id, nome: nome},
            success: function () {
                ko.notification.success("Agendamento removido com sucesso.");
                row.parent().parent().parent().fadeTo(400, 0, function () {
                    row.parent().parent().parent().remove();
                });
            },
            error: function () {
                ko.notification.error("Falha ao remover sala.");
            }
        });
    }
});

function formataData(data) {
    if(data != null){
        var separaData = data.slice(0, 19).split(' ');
        var data = separaData[0].slice(0, 16).split('-');
        return data[2] + "/" + data[1] + "/" + data[0] + " " + separaData[1];
    }else{
        return true;
    }
}
