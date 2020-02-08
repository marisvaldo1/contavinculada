var cargos = [];
var confirma = false;

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .conta-corrente').click();
    $('#sidebar-menu ul .verbas').click();
    $('#sidebar-menu ul .reter-verbas').addClass('active');

    carregaSelectEmpresa();
});

var carregaSelectEmpresa = function () {

    var data, status, xhr;

    $.post(APP_HTTP + 'cadastro/empresas/empresa.php', { acao: 'listar' }, function (data, status, xhr) {
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
                        $("#select-empresa").append('<option value="' + item.id_empresa + '-' + item.cnpj + '">' + item.razao + '</option>');
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

$(document).on('change', '#select-empresa', function () {

    //Limpa todos os dados do select
    $('#select-contrato').html('<option value="">Selecione</option>');

    if ($('#select-empresa').val() !== "")
        carregaSelectContrato($('#select-empresa').val().split('-')[0]);
});

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

$(document).on('click', '#botao-captura', function () {

    mensagem.icone = msg.ICO_EXCLAMATION;
    mensagem.tipo = msg.DANGER;
    mensagem.espera = 2000;

    //Verifica se a empresa foi selecionada
    if ($('#select-empresa option:selected').val() === "") {
        mensagem.titulo = 'Selecione uma empresa';
        mensagem.exibe();
        return false;
    }

    //Verifica se um contrato selecionado
    if ($('#select-contrato option:selected').val() === "") {
        mensagem.titulo = 'Selecione um contrato';
        mensagem.exibe();
        return false;
    }

    //Verifica se o arquivo foi selecionado
    if ($('#fileUpload').val() === "") {
        mensagem.titulo = 'Selecione um arquivo para capturar os dados';
        mensagem.exibe();
        return false;
    }

    //Verifica se um contrato selecionado
    //if ($('#observacao').val() === "") {
    //    mensagem.titulo = 'Informe uma observação sobre a captura';
    //    mensagem.exibe();
    //    return false;
    //}

    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    if (!confirma) {
        $("#modalCaptura").modal({
            backdrop: 'static'
        });
    }

});

$('#formUpload').validator().on('submit', function (e) {

    $('#formUpload').attr({action: 
            location.href = APP_HTTP +
            'conta_corrente/historico-captura/index.php' +
            '?id_empresa=' + $('#select-empresa').val().split('-')[0] +
            '&id_contrato=' + $('#select-contrato').val() +
            '&mes=' + $("#select-mes :selected").val() +
            '&ano=' + $("#select-ano :selected").val()
    });

    //Insere os parametros dentro do form
    form = new FormData(this);
    form.append('id_empresa', $('#select-empresa').val().split('-')[0]);
    form.append('empresa', $("#select-empresa :selected").text());
    form.append('nome_empresa', $("#select-empresa :selected").text());
    form.append('id_contrato', $('#select-contrato').val());
    form.append('mes', $("#select-mes :selected").val());
    form.append('ano', $("#select-ano :selected").val());
    form.append('observacao_retencao', $("#observacao").val());
    form.append('nome_arquivo', $('#fileUpload').val().replace("C:\\fakepath\\", ""));
    form.append('arquivo_captura', 'planilhas/' + $("#select-empresa :selected").text() + '/' + $('#fileUpload').val().replace("C:\\fakepath\\", ""));

    $.ajax({
        url: "captura.php",
        type: "POST",
        data: form, // Parametros para a captura
        contentType: false,
        cache: false, // Desabilita o cacheamento das requisições de página
        processData: false,
        success: function (data) {
            resultadoUpload = toJson(data);
            if (resultadoUpload.erro) {
                mensagem = {
                    titulo: 'ERRO!',
                    texto: resultadoUpload.retorno,
                    permitirFechar: true,
                    tipo: 'danger',
                    icone: 'fa fa-exclamation-triangle fa-2x',
                    url: ''
                };
                exibeMensagem(mensagem);
                return false;
            } else {

                $(this).submit();
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    }).complete(function () {
        mensagem.titulo = 'Problemas na captura. Verifique com o administrador do sistema.';
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
});