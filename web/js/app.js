
var rootUrl = "/app/";
var clientUrl = "/web/";

function getErrorMessage(jsonError) {
    return (JSON.parse(jsonError)).error.text;
}

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

function goPage(page) {
    location.href = clientUrl.concat(page);
}

function verifyLogin() {
    $.cookie.json = true;
    if ($.cookie('usuario') === undefined) {
        goPage("login");
    }
}

function preparaData(data) {
    data.datepicker();
    data.datepicker("option", "dateFormat", "dd/mm/yy");
    data.keypress(function (event) {
        event.preventDefault();
    });
}

$(document).ready(function () {
    $("#linkSair").click(function () {
        $.ajax({
            type: "get",
            url: rootUrl.concat("usuario/logout"),
            success: function () {
                $.removeCookie('usuario');
                goPage("login");
            }
        });
    });
    
    $("label:has(+ div > input:required)").addClass("required");

    $("body").on("click", ".btn-close", function () {
        $(this).closest(".window-overlay").addClass("hide");
    });

    $("body").on("click", ".window-overlay", function (e) {
        if ($(e.target).is('.window-overlay')) {
            $(this).addClass("hide");
        }
    });


    $(".data").datepicker({
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        nextText: 'Próximo',
        prevText: 'Anterior'
    });

});

ko.mapping = function (objeto, data) {
    for (var key in data) {
        var value = data[key];
        if (value instanceof Array) {
            objeto[key] = ko.observableArray(value);
        } else {
            objeto[key] = ko.observable(value);
        }
    }
    return objeto;
};

ko.ajax = {
    ajax: function (options) {
        options.dataType = "json";
        return $
                .ajax(options)
                .error(function (result) {
                    $("#errorLoad").html(getErrorMessage(result.responseText));
                    $("#errorLoad").show();
                });
    },
    list: function (name, filter) {
        filter = filter ? "/".concat(filter) : "";
        return this.ajax({
            url: rootUrl.concat(name).concat("/listAll").concat(filter)
        });
    },
    find: function (model) {
        return this.ajax({
            url: rootUrl.concat(model.constructor.name).concat("/list/").concat(model.id())
        });
    },
    detalhe: function (model) {
        return this.ajax({
            url: rootUrl.concat(model.constructor.name).concat("/detalhes/").concat(model.id())
        });
    },
    save: function (model) {
        return this.ajax({
            type: "post",
            url: rootUrl.concat(model.constructor.name).concat("/save"),
            data: ko.toJSON(model)
        });
    },
    delete: function (model) {
        return this.ajax({
            type: "post",
            url: rootUrl.concat(model.constructor.name).concat("/delete"),
            data: ko.toJSON(model)
        });
    }
};

ko.date = function () {
    var date = new Date();
    return date.toJSON().slice(0, 10) + " " + date.toTimeString().slice(0, 8);
};

ko.formataMoeda = {
    formatarValor: function (valor) {
        valor = valor.replace(".", "");
        valor = valor.replace(",", ".");
        return valor;
    },
    numeroParaMoeda: function (n, c, d, t) {
        c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
        return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
    }
};

ko.notification = {
    status: function (message, type) {
        $(".status").remove();
        return $("<div>")
                .addClass("status " + type)
                .html($("<div>").html(message))
                .appendTo("body")
                .hide()
                .fadeIn();
    },
    info: function (message) {
        this
                .status(message, "info")
                .delay(3000)
                .fadeOut(function () {
                    $(this).remove();
                });
    },
    error: function (message) {
        this
                .status(message, "error")
                .delay(7000)
                .fadeOut(function () {
                    $(this).remove();
                });
    },
    success: function (message) {
        this
                .status(message, "success")
                .delay(7000)
                .fadeOut(function () {
                    $(this).remove();
                });
    }
};

function formataDataSemHora(data) {
    var data1 = data.slice(0, 16).split('-');
    return data1[2] + "/" + data1[1] + "/" + data1[0];
}