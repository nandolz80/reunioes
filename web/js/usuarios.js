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
        url: rootUrl + "usuario/listAll" + filtro,
        dataType: "json",
        success: function (data) {
            $("#tableSalas").find("tbody tr").remove();
            if (data.length !== 0) {
                data.result.forEach(function (usuario) {
                    row = "<tr>"
                            + "<td><a id='edit' href='#' data-id='" + usuario.id + "'>" + usuario.login + "</a></td>"
                            + "<td class='hidden-phone'>" + usuario.lastLogin + "</td>"
                            + "<td class='text-center'><a href='#' data-toggle='tooltip' data-placement='top' title='Excluir'><span class='glyphicon glyphicon-trash' aria-hidden='true' data-id='" + usuario.id + "' data-nome='" + usuario.login + "'/></span></a></td>"
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

    var valido = true;

    if ($('#usuario').val().length == 0)
    {
        valido = false;
        $('#login').addClass("has-error");
    }
    if ($('#senha').val().length == 0)
    {
        valido = false;
        $('#senha').addClass("has-error");
    }
    if ($('#confirmaSenha').val().length == 0)
    {
        valido = false;
        $('#confirmaSenha').addClass("has-error");
    }

    if (valido) {
        var senha = $("#senha").val();
        var confirmasenha = $("#confirmaSenha").val();
        if (senha !== confirmasenha) {
            valido = false;
            ko.notification.info("Senha direfente da senha confirmada.");
        } else {
            $.ajax({
                type: "post",
                url: rootUrl + "usuario/save",
                dataType: "json",
                data: $("#novoModal form").serialize(),
                success: function (data) {
                    res = data.result;                    
                    if (res === "Login já cadastrado") {
                        ko.notification.error(res);
                    } else if (res === "Login já cadastrado") {
                        ko.notification.error(res);
                    } else {
                        $('#novoModal').modal('hide');
                        $("form")[0].reset();
                        ko.notification.success("Usuário cadastrado com sucesso.")
                        atualizaGrid();
                    }
                },
                error: function (data) {
                    ko.notification.error("Erro ao cadastrar usuário.");
                }

            });
        }
    } else {
        ko.notification.error("Preencha todos campos obrigatórios.");
    }

});

$("body").on("click", ".glyphicon-trash", function () {
    id = $(this).attr("data-id");
    nome = $(this).attr("data-nome");
    row = $(this);
    if (confirm("Excluir " + nome + "?")) {
        $.ajax({
            type: "post",
            url: rootUrl + "usuario/delete",
            dataType: "json",
            data: {id: id, nome: nome},
            success: function () {
                ko.notification.success("Usuário removido com sucesso.");
                row.parent().parent().parent().fadeTo(400, 0, function () {
                    row.parent().parent().parent().remove();
                });
            },
            error: function () {
                ko.notification.error("Falha ao remover usuário.");
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
        url: rootUrl + "usuario/list/" + id,
        dataType: "json",
        success: function (data) {
            usuario = data.result;

            $("input[name=id]").val(usuario.id);
            $("input[name=usuario]").val(usuario.login);
            $("input[name=senha]").val(usuario.senha);
            $("input[name=confirmasenha]").val(usuario.senha);

            $("#novoModal").modal("show");
        },
        error: function (result) {
            $("#errorLoad").html(getErrorMessage(result.responseText));
            $("#errorLoad").show();
        }
    });
});

function formataData(data) {
    var separaData = data.slice(0, 16).split(' ');
    var data = separaData[0].slice(0, 16).split('-');
    return data[2] + "-" + data[1] + "-" + data[0];
}