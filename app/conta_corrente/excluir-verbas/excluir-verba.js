var cargos = [];

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .conta-corrente').click();
    $('#sidebar-menu ul .excluir-verbas').addClass('active');

    carregaExtrato();
    carregaSelectEmpresa();

});

/*
 * Definições para a tabela de extrato para exclusões
 */
var tab_extrato = $('#tab_extrato').DataTable({
    paging: true,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: false,
    searching: false,
    info: false,
    ordering: false,
    "order": [0, 1, 'desc'],
    "oLanguage": {
        "sEmptyTable": "Nenhum registro encontrado",
        "sProcessing": "Processando...",
        "sZeroRecords": "Não foram encontrados resultados",
        "sInfoFiltered": "",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "oPaginate": {
            "sFirst": "Primeiro",
            "sPrevious": "Anterior",
            "sNext": "Seguinte",
            "sLast": "Último"
        }
    },
    columns: [
        {title: "Ano", className: "text-center"},
        {title: "Mês", className: "text-center"},
        {title: "Empregado", className: "text-left"},
        {title: "Ação", className: "text-center"},
        {title: "Décimo<br>Terceiro", className: "text-right"},
        {title: "Abono<br>de Férias", className: "text-right"},
        {title: "Multa<br>FGTS", className: "text-right"},
        {title: "Impacto<br>sobre 13º", className: "text-right"},
        {title: "Impacto<br>férias / Abono", className: "text-right"},
        {title: "Total<br>Retido/Liberado", className: "text-right"}
    ]
});

var nomeMeses = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

function carregaExtrato(id_empresa = null, id_contrato = null, nu_mes = null, nu_ano = null, id_empregado = null) {

    var dados = {};
    var data, status, xhr;

    var parametros = {
        acao: 'listar',
        id_empresa: id_empresa,
        id_contrato: id_contrato,
        mes: nu_mes,
        ano: nu_ano,
        id_empregado: id_empregado
    };

    tab_extrato.clear();
    tab_extrato.draw();

    $.post('excluir-verba.php', parametros, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {

            //Carrega os encargos do contrato
            if (dados.dados.length > 0) {
                total_r_linha = 0;
                total_l_linha = 0;
                totalDecimoTerceiro = 0;
                totalFeriasAbono = 0;
                totalMultaFGTS = 0;
                totalImpactoEncargos = 0;
                totalImpactoFeriasAbono = 0;
                totalRetidoLiberado = 0;

                /*
                 * Divide o array de retorno em lançamentos separados por retenção e liberação
                 */
                $.each(dados.dados, function (i, item) {
                    //Total de renções da linha
                    total_r_linha = parseFloat(item.r_decimo_terceiro) +
                            parseFloat(item.r_ferias_abono) +
                            parseFloat(item.r_multa_FGTS) +
                            parseFloat(item.r_impacto_encargos_13) +
                            parseFloat(item.r_impacto_ferias_abono);

                    tab_extrato.row.add([
                        item.ano,
                        nomeMeses[parseInt(item.mes)],
                        item.nome,
                        'Retenção',
                        'R$ ' + Numeros.formata(item.r_decimo_terceiro, 2, ','),
                        'R$ ' + Numeros.formata(item.r_ferias_abono, 2, ','),
                        'R$ ' + Numeros.formata(item.r_multa_FGTS, 2, ','),
                        'R$ ' + Numeros.formata(item.r_impacto_encargos_13, 2, ','),
                        'R$ ' + Numeros.formata(item.r_impacto_ferias_abono, 2, ','),
                        //Totalizador da linha
                        'R$ ' + Numeros.formata(total_r_linha, 2, ',')
                    ]).draw(false);

                    /*
                     * Verifica se existe algum lançamento de liberação para criar a linha
                     */
                    //Total de liberações da linha
                    total_l_linha = parseFloat(item.l_decimo_terceiro) +
                            parseFloat(item.l_ferias_abono) +
                            parseFloat(item.l_multa_FGTS) +
                            parseFloat(item.l_impacto_encargos_13) +
                            parseFloat(item.l_impacto_ferias_abono)

                    if (total_l_linha > 0) {
                        tab_extrato.row.add([
                            '<span style="color: red">' + item.ano + '</span>',
                            '<span style="color: red">' + nomeMeses[parseInt(item.mes)] + '</span>',
                            '<span style="color: red">' + item.nome + '</span>',
                            '<span style="color: red">' + 'Liberação' + '</span>',
                            parseFloat(item.l_decimo_terceiro) === 0 ? '' : '<span style="color: red">-R$ ' + Numeros.formata(item.l_decimo_terceiro, 2, ',') + '</span>',
                            parseFloat(item.l_ferias_abono) === 0 ? '' : '<span style="color: red">-R$ ' + Numeros.formata(item.l_ferias_abono, 2, ',') + '</span>',
                            parseFloat(item.l_multa_FGTS) === 0 ? '' : '<span style="color: red">-R$ ' + Numeros.formata(item.l_multa_FGTS, 2, ',') + '</span>',
                            parseFloat(item.l_impacto_encargos_13) === 0 ? '' : '<span style="color: red">-R$ ' + Numeros.formata(item.l_impacto_encargos_13, 2, ',') + '</span>',
                            parseFloat(item.l_impacto_ferias_abono) === 0 ? '' : '<span style="color: red">-R$ ' + Numeros.formata(item.l_impacto_ferias_abono, 2, ',') + '</span>',
                            (total_l_linha === 0) ? '' : '<span style="color: red">R$ ' + Numeros.formata(total_l_linha, 2, ',')
                        ]).draw(false);
                    }
                    totalDecimoTerceiro += parseFloat(item.r_decimo_terceiro) - parseFloat(item.l_decimo_terceiro);
                    totalFeriasAbono += parseFloat(item.r_ferias_abono) - parseFloat(item.l_ferias_abono);
                    totalMultaFGTS += parseFloat(item.r_multa_FGTS) - parseFloat(item.l_multa_FGTS);
                    totalImpactoEncargos += parseFloat(item.r_impacto_encargos_13) - parseFloat(item.l_impacto_encargos_13);
                    totalImpactoFeriasAbono += parseFloat(item.r_impacto_ferias_abono) - parseFloat(item.l_impacto_ferias_abono);
                    totalRetidoLiberado += parseFloat(total_r_linha) - parseFloat(total_l_linha);
                });

                $('#totalDecimoTerceiro').text('R$ ' + Numeros.formata(totalDecimoTerceiro, 2, ','));
                $('#totalFeriasAbono').text('R$ ' + Numeros.formata(totalFeriasAbono, 2, ','));
                $('#totalMultaFGTS').text('R$ ' + Numeros.formata(totalMultaFGTS, 2, ','));
                $('#totalImpactoEncargos').text('R$ ' + Numeros.formata(totalImpactoEncargos, 2, ','));
                $('#totalImpactoFeriasAbono').text('R$ ' + Numeros.formata(totalImpactoFeriasAbono, 2, ','));
                $('#totalRetidoLiberado').text('R$ ' + Numeros.formata(totalRetidoLiberado, 2, ','));
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
}

$(document).on('change', '#select-empresa', function () {
    $('#select-contrato').children('option:not(:first)').remove();
    $('#select-empregado').children('option:not(:first)').remove();
    if ($('#select-empresa').val() != "") {
        carregaSelectContrato($('#select-empresa').val());
        carregaEmpregado($('#select-empresa').val());
    }
});

$(document).on('click', '#botao-filtrar', function () {
    $('#totalRemuneracao').text(Numeros.formata(0, 2, ','));
    $('#totalDecimoTerceiro').text(Numeros.formata(0, 2, ','));
    $('#totalFeriasAbono').text(Numeros.formata(0, 2, ','));
    $('#totalMultaFGTS').text(Numeros.formata(0, 2, ','));
    $('#totalImpactoEncargos').text(Numeros.formata(0, 2, ','));
    $('#totalImpactoFeriasAbono').text(Numeros.formata(0, 2, ','));

    carregaExtrato(
            $('#select-empresa').val() == "" ? null : $('#select-empresa').val(),
            $('#select-contrato').val() == "" ? null : $('#select-contrato').val(),
            $('#select-mes').val() == 0 ? null : $('#select-mes').val(),
            $('#select-ano').val() == 0 ? null : $('#select-ano').val(),
            $('#select-empregado').val() == 0 ? null : $('#select-empregado').val(),
            );
});

var carregaSelectEmpresa = function () {

    var data, status, xhr;

    $.post(APP_HTTP + 'cadastro/empresas/empresa.php', {acao: 'listar'}, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                $('.cnpj').html(dados.dados[0].cnpj);

                //Evita duplicidade na carga da select
                if ($("#select-empresa").html().trim().length < 50) {

                    $.each(dados.dados, function (i, item) {
                        $("#select-empresa").append('<option value="' + item.id_empresa + '">' + item.razao + '</option>');
                    });
                }
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
};

var carregaSelectContrato = function (id_empresa) {

    var data, status, xhr;

    var parametros = {
        acao: 'listarContratosEmpresa',
        id_empresa: id_empresa
    };

    $.post(APP_HTTP + 'cadastro/contratos/contrato.php', parametros, function (data, b, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                //Evita duplicidade na carga da select
                if ($("#select-contrato").html().trim().length < 50) {

                    $.each(dados.dados, function (i, item) {
                        $("#select-contrato").append('<option value="' + item.id_contrato + '">' + item.nu_contrato + '</option>');
                    });
                }
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
};

var carregaEmpregado = function (id_empresa) {

    var data, status, xhr;

    var parametros = {
        acao: 'listarEmpregadosEmpresa',
        id_empresa: id_empresa
    };

    $.post(APP_HTTP + 'cadastro/empregados/empregado.php', parametros, function (data, b, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                //Evita duplicidade na carga da select
                if ($("#select-empregado").html().trim().length < 50) {

                    $.each(dados.dados, function (i, item) {
                        $("#select-empregado").append('<option value="' + item.id_empregado + '">' + item.nome + '</option>');
                    });
                }
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
};