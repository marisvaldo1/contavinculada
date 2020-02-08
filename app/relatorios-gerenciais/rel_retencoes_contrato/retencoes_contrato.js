var cargos = [];

$(document).ready(function () {
    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .relatorios-gerenciais').click();
    $('#sidebar-menu ul .retencoes-contrato').addClass('active');

    carregaSelectEmpresa();

});

$(document).on('change', '#select-empresa', function () {
    $('#select-contrato').children('option:not(:first)').remove();
    $('#select-empregado').children('option:not(:first)').remove();
    if ($('#select-empresa').val() !== "") {
        carregaSelectContrato($('#select-empresa').val());
        carregaEmpregado($('#select-empresa').val());
    }
});

$('#dataInicio').Monthpicker({
    onSelect: function () {
        $('#dataFim').Monthpicker('option', {minValue: $('#dataInicio').val()});
    }
});
$('#dataFim').Monthpicker({
    onSelect: function () {
        $('#dataInicio').Monthpicker('option', {maxValue: $('#dataFim').val()});
    }
});

/*
 * Imprimir Retenções por contrato.
 */
$(document).on('click', '#botao-imprimir', function () {

    var parametros = {
        id_empresa: $('#select-empresa').val(),
        nome_empresa: $('#select-empresa option:selected').text(),
        id_contrato: $('#select-contrato').val(),
        id_empregado: $('#select-empregado option:selected').val(),
        nu_contrato: $('#select-contrato option:selected').text(),
        dataInicio: $('#dataInicio').val() === "" ? null : $('#dataInicio').val().split('/')[1].toString() + parseInt($('#dataInicio').val().split('/')[0]).toString(),
        dataFim: $('#dataFim').val() === "" ? null : $('#dataFim').val().split('/')[1].toString() + parseInt($('#dataFim').val().split('/')[0]).toString(),
        tipo_relatorio: 'Reten&ccedil;&otilde;es por Contrato',
        observacao_liberacao: $('#observacao_liberacao').val() === 0 ? null : $('#observacao_liberacao').val(),
        observacao_retencao: $('#observacao_retencao').val() === 0 ? null : $('#observacao_retencao').val()
    };

    document.getElementById("formRetencoesContrato").action = "retencoes_contrato_html.php";
    document.getElementById("formRetencoesContrato").method = "POST";
    document.getElementById("formRetencoesContrato").target = "_blank";
    document.getElementById("parametros").value = JSON.stringify(parametros);
    document.getElementById("formRetencoesContrato").submit();
    return;
});

var carregaSelectEmpresa = function () {

    var data, status, xhr;

    var parametros = {
        acao: 'listar',
        id_empresa: USUARIO_VISITANTE ? EMPRESA_VISITANTE : '' //id_empresa
    };

    $.post(APP_HTTP + 'cadastro/empresas/empresa.php', parametros, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
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
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
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
        if (dados.erro === true) {
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