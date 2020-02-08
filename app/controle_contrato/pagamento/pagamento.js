var pagamentos = [];
$("#valor_pagamento").maskMoney({prefix: 'R$ ', allowNegative: false, thousands: '.', decimal: ',', affixesStay: true});

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .controle-contratos').click();
    $('#sidebar-menu ul .pagamento').click();
    $('#sidebar-menu ul .pagamento').addClass('active');

    carregaContratoSistema();

});

/*
 * Definições tabela de cargos
 */
var tab_contratoSistema = $('#contratoSistema').DataTable({
    fixedHeader: true,
    paging: false,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: false,
    searching: false,
    info: false,
    ordering: true,
    "oLanguage": {
        "sEmptyTable": "Nenhum registro encontrado",
        "oPaginate": {
            "sFirst": "Primeiro",
            "sPrevious": "Anterior",
            "sNext": "Seguinte",
            "sLast": "Último"
        }
    },
    columns: [
        {title: "Ação", className: "text-center"},
        {title: "Contrato", className: "text-center"},
        {title: "Cliente", className: "text-left"}
    ]
});

var carregaContratoSistema = function () {

    $("#divContrato").html("<div class='text-center'><br><br><br><br><br><i class='fa fa-refresh fa-spin fa-3x fa-fw'></i><span class='sr-only'>Carregando...</span></div>");

    var dados = {};
    var data, status, xhr;

    $.post(APP_HTTP + 'controle_contrato/contrato_sistema/contrato_sistema.php', {acao: 'listar'}, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                $.each(dados.dados, function (i, item) {
                    tab_contratoSistema.row.add([
                        `<a href="#" class="pagamentosContrato" data-contrato="${item.nu_contrato_sistema}" id="${item.id_contrato}-${item.id_cliente}"><i class="fa fa-check fa-2x"></i></a>&nbsp`,
                        item.nu_contrato_sistema,
                        item.razao
                    ]).draw(false);
                });

            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 2000;
        mensagem.exibe();
        return false;
    });
};

/*
 * Definições tabela de empregados
 */
var tab_pagamentos = $('#tab_pagamentos').DataTable({
    //scrollY: '31vh', //Define a quantidade de linhas na tabela e acrescenta scroll automático
    fixedHeader: true,
    paging: false,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: false,
    searching: false,
    info: false,
    ordering: true,
    "order": [1, 'asc'],
    "oLanguage": {
        "sEmptyTable": "Nenhum registro encontrado",
        "oPaginate": {
            "sFirst": "Primeiro",
            "sPrevious": "Anterior",
            "sNext": "Seguinte",
            "sLast": "Último"
        }
    },
    columns: [
        {title: "#", className: "text-center"},
        {title: "Nº", className: "text-center"},
        {title: "VENCTO", className: "text-center"},
        {title: "A PAGAR", className: "text-right"},
        {title: "PAGTO", className: "text-center"},
        {title: "PAGO", className: "text-right"}
    ]
});

$(document).on('click', '.pagamentosContrato', function () {

    tab_pagamentos.clear();
    tab_pagamentos.draw();

    $('#contratoPagamento').html(' - Contrato: ' + $(this).attr('data-contrato'));

    var parametros = {
        acao: 'listar',
        id_contrato: this.id.split('-')[0]
    };

    $.post('pagamento.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            //Carrega as parcelas do contrato
            if (dados.dados.length > 0) {
                totalParcela = 0;
                totalPagamento = 0;

                $.each(dados.dados, function (i, item) {
                    tab_pagamentos.row.add([
                        `<a href="#" class="pagarParcela" 
                            id="${item.id_parcela}"
                            data-id_cliente="${item.id_cliente}" 
                            data-id_contrato="${item.id_contrato}" 
                            data-id_parcela="${item.id_parcela}"
                            data-dt_vencimento="${item.data_vencimento}"
                            data-dt_pagamento="${item.data_pagamento}"
                            data-valor_parcela="${item.valor_parcela}"
                            data-observacao_parcela="${item.observacao_parcela}"
                            data-valor_pagamento="${item.valor_pagamento}"><i class="fa fa-edit fa-2x text-primary"></i></a>`,
                        item.id_parcela,
                        item.data_vencimento.formataData(),
                        (item.valor_parcela === null) ? 'R$ 0,00' : parseFloat(item.valor_parcela).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}),
                        (item.data_pagamento === null) ? '' : item.data_pagamento.formataData(),
                        (item.valor_pagamento === null) ? 'R$ 0,00' : parseFloat(item.valor_pagamento).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}),
                        item.data_pagamento
                    ]).draw(false);
                    totalParcela += parseFloat(item.valor_parcela);
                    totalPagamento += (item.valor_pagamento === null) ? 0 : parseFloat(item.valor_pagamento);
                });

                $('#totalParcela').text('R$ ' + Numeros.formata(totalParcela, 2, ','));
                $('#totalPagamento').text('R$ ' + Numeros.formata(totalPagamento, 2, ','));
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 2000;
        mensagem.exibe();
        return false;
    });
});

$('#modalPagamento').on('shown.bs.modal', function (e) {
    $('#valor_pagamento').focus();
});

$('#data_vencimento').on('change', function (e) {
    $('#data_pagamento').val('');
    $('#valor_pagamento').val('');
});


$(document).on('click', '.pagarParcela', function () {

    $('#id_cliente').val($(this).attr("data-id_cliente"));
    $('#id_contrato').val($(this).attr("data-id_contrato"));
    $('#id_parcela').val($(this).attr("data-id_parcela"));
    $('#data_vencimento').val($(this).attr("data-dt_vencimento") === 'null' ? '' : $(this).attr("data-dt_vencimento").formataData());
    $('#data_pagamento').val($(this).attr("data-dt_vencimento") === 'null' ? '' : $(this).attr("data-dt_vencimento").formataData());
    $('#observacao_parcela').html($(this).attr("data-observacao_parcela") === 'null' ? '' : $(this).attr("data-observacao_parcela"));

    if ($(this).attr("data-valor_pagamento") > 0)
        $('#valor_pagamento').val(parseFloat($(this).attr("data-valor_pagamento")).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}));
    else
        $('#valor_pagamento').val(parseFloat($(this).attr("data-valor_parcela")).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}));


    //Abre a modal para cadastro dos pagamentos;;;
    $("#modalPagamento").modal({
        backdrop: 'static'
    });

});

$(document).on('click', '#botao-salvar', function () {

    mensagem.titulo = '';

    if ($('#data_vencimento').val() === '') {
        mensagem.titulo = 'Data de vencimento inválida.';
    } else if ($('#data_pagamento').val() !== '') {
        if ($('#data_pagamento').val() < $('#data_vencimento').val()) {
            mensagem.titulo = 'Data de pagamento inválida.';
        }
    }

    if (mensagem.titulo !== '') {
        mensagem.espera = 2000;
        mensagem.exibe();
        return false;
    }

    //Capturando todos os campos input do formulario
    var parametros = {
        acao: 'alterar',
        id_contrato: $('#id_contrato').val(),
        id_cliente: $('#id_cliente').val(),
        id_parcela: $('#id_parcela').val(),
        data_vencimento: $('#data_vencimento').val(),
        data_pagamento: $('#data_pagamento').val(),
        observacao_parcela: $('#observacao_parcela').val(),
        valor_pagamento: parseFloat($('#valor_pagamento').val().trim().replace('.', '').replace(',', '.').replace('R$', '').trim())
    };
    var dados = {};

    $.post('pagamento.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalCargo').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        //console.error(xhr.status);
        return false;
    });
});

$(document).on('click', '.alterar', function () {
    var dados = {};

    $.post('pagamento.php', {acao: 'listar', id_contrato: this.id}, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            $('#data_pagamento').val(dados.dados[0].data_pagamento);
            $('#valor_pagamento').val(dados.dados[0].valor_pagamento);
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalPagamento").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    var parametros = {
        acao: 'excluir',
        id_contrato: $('#id_contrato').val(),
        id_cliente: $('#id_cliente').val(),
        data_pagamento: $('#data_pagamento').val(),
        valor_pagamento: $('#valor_pagamento').val()
    };

    $.post('pagamento.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            mensagem.titulo = 'Registro excluído com sucesso';
            mensagem.espera = 1000;
            mensagem.exibe();
            $('#modalCargo').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });
});

$(document).on('click', '.excluir', function () {
    $('#id_cargo').val(this.id);
    $("#modalExcluirPagamento").modal({
        backdrop: 'static'
    });
});