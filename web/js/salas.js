$(function () {
    verifyLogin();
    atualizaGrid();
});

$('#btnNovo').click(function () {
    $("input[name=id]").val("");
    $("#novoModal form")[0].reset();
    $("#form input").map(function () {
        $(this).parents("div").removeClass("has-error");
    });
});

$('#btnBuscar').click(function () {
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
        url: rootUrl + "salas/listAll" + filtro,
        dataType: "json",
        success: function (data) {
            $("#tableSalas").find("tbody tr").remove();
            if (data.length !== 0) {
                data.result.forEach(function (salas) {
                    row = "<tr>"
                            + "<td class='text-center'><a id='edit' href='#' data-id='" + salas.id + "'>" + salas.nome + "</a></td>"
                            + "<td class='text-center'>" + salas.descricao + "</td>"
                            + "<td class='text-center'>" + salas.capacidade + "</td>"
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

$("#novoModal form").on("submit", function (e) {
    e.preventDefault();

    $.ajax({
        type: "post",
        url: rootUrl + "salas/save",
        dataType: "json",
        data: $("#novoModal form").serialize(),
        success: function (data) {
            res = data.result;
            $('#novoModal').modal('hide');
            $("form")[0].reset();
            ko.notification.success("Sala cadastrada com sucesso.");
            atualizaGrid();
        },
        error: function (data) {
            ko.notification.error("Erro ao cadastrar sala.");
        }

    });
});

$("body").on("click", ".glyphicon-trash", function () {
    id = $(this).attr("data-id");
    nome = $(this).attr("data-nome");
    row = $(this);
    if (confirm("Excluir " + nome + "?")) {
        $.ajax({
            type: "post",
            url: rootUrl + "salas/delete",
            dataType: "json",
            data: {id: id, nome: nome},
            success: function () {
                ko.notification.success("Sala removida com sucesso.");
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

$("body").on("click", "#edit", function () {
    id = $(this).attr("data-id");
    $("#form input").map(function () {
        $(this).parents("div").removeClass("has-error");
    });
    $.ajax({
        type: "get",
        url: rootUrl + "salas/list/" + id,
        dataType: "json",
        success: function (data) {
            usuario = data.result;

            $("input[name=id]").val(usuario.id);
            $("input[name=nome]").val(usuario.nome);
            $("input[name=descricao]").val(usuario.descricao);
            $("input[name=capacidade]").val(usuario.capacidade);

            $("#novoModal").modal("show");
        },
        error: function (result) {
            $("#errorLoad").html(getErrorMessage(result.responseText));
            $("#errorLoad").show();
        }
    });
});