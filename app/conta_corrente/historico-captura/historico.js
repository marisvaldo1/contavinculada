var cargos = [];

$(function () {
    if ($('#filtro').val()) {
        $('#select-empresa').val(JSON.parse($('#filtro').val()).id_empresa);
        $('#select-contrato').val(JSON.parse($('#filtro').val()).id_contrato);
        $('#select-mes').val(JSON.parse($('#filtro').val()).mes);
        $('#select-ano').val(JSON.parse($('#filtro').val()).ano);
    }
});

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .conta-corrente').click();
    $('#sidebar-menu ul .verbas').click();
    $('#sidebar-menu ul .historico-captura').addClass('active');

    if ($('#filtro').val())
        carregaHistoricoCaptura(JSON.parse($('#filtro').val()).id_empresa, JSON.parse($('#filtro').val()).id_contrato, JSON.parse($('#filtro').val()).mes, JSON.parse($('#filtro').val()).ano);
    else
        carregaHistoricoCaptura();

    carregaSelectEmpresa();

});

/*
 * Definições para a tabela de encargos
 */
var tab_captura = $('#tab_captura').DataTable({
    paging: true,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: true,
    searching: true,
    info: false,
    ordering: false,
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
        { title: "Ação", className: "text-center" },
        { title: "Ano", className: "text-center" },
        { title: "Mês", className: "text-center" },
        { title: "Contrato", className: "text-center" },
        { title: "Planilha Capturada", className: "text-left" },
        { title: "Status", className: "text-center" },
        { title: "Data", className: "text-center" }
    ],
    'columnDefs': [{
        'targets': [0],
        'searchable': false,
        'orderable': false
    }]
});

function carregaHistoricoCaptura(id_empresa = null, id_contrato = null, nu_mes = null, nu_ano = null) {

    carregando('on');

    var dados = {};
    var data, status, xhr;

    var parametros = {
        acao: 'listar',
        id_empresa: id_empresa,
        id_contrato: id_contrato,
        nu_mes: nu_mes,
        nu_ano: nu_ano
    };

    tab_captura.clear();
    tab_captura.draw();

    $.post('historico.php', parametros, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {

            var nomeMeses = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

            //Controla para que somente a captura mais recente possa ser ecluída 
            let excluirUltimaCaptura = true;

            //Carrega os encargos do contrato
            if (dados.dados.length > 0) {

                $.each(dados.dados, function (i, item) {
                    if (item.status_captura === 'Sucesso') {
                        if (excluirUltimaCaptura) {
                            var linha = '  <i class="fa fa-trash fa-2x mao-link excluir cursorExcluir" ';
                            linha += ' title="Remover Captura" ';
                            excluirUltimaCaptura = false;
                        } else {
                            var linha = '  <i  ';
                            linha += ' title="Remover Captura" ';
                        }
                    } else {
                        var linha = '  <i class="fa fa-trash fa-2x mao-link" ';
                        linha += ' data-toggle="confirmation" ';
                        linha += ' data-tt="tooltip" ';
                        linha += ' title="Remover Item" ';
                    }

                    linha += ' data-id=' + item.id_captura;
                    linha += ' data-status="' + item.status_captura + '"> ';
                    linha += '</i>&nbsp;';

                    tab_captura.row.add([
                        linha,
                        item.ano,
                        nomeMeses[parseInt(item.mes)],
                        item.nu_contrato,
                        item.historico,
                        item.status_captura,
                        moment(item.datahora).format("DD/MM/YYYY HH:mm:ss")
                    ]).draw(false);
                });
            }
        }
    }).complete(function () {
        carregando('off');        
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
}

// Remove a linha de uma tabela
$('body').confirmation({
    selector: '[data-toggle=confirmation]',
    placement: "left",
    btnOkLabel: "&nbsp;Sim",
    btnCancelLabel: "&nbsp;Não",
    onConfirm: function () {

        if ($(this).closest('tr')[0] !== undefined) {
            //Remove itens de uma tr
            var elemento = $(this).closest('tr');
        } else {
            //Remove itens de uma li
            var elemento = $(this).closest('li');
        }

        elemento.fadeOut(400, function () {
            elemento.remove();
        });

        var parametros = {
            acao: 'excluirHistorico',
            id_captura: $(this).attr('data-id')
        };

        var dados = {};

        $.post('historico.php', parametros, function (r, b, xhr) {
            dados = toJson(r);
        }).success(function () {
            if (dados.erro === true) {
                mensagem.titulo = dados.mensagem;
                console.log(dados.mensagem);
                mensagem.espera = 2000;
                mensagem.exibe();
                return false;
            } else {
                return true;
            }
        }).error(function (xhr) {
            if (xhr.status === 401) {
            }
            return false;
        });

    },
    onCancel: function () {
    }

});

$(document).on('change', '#select-empresa', function () {
    $('#select-contrato').children('option:not(:first)').remove();
    $('#select-empregado').children('option:not(:first)').remove();
    if ($('#select-empresa').val() !== "") {
        carregaSelectContrato($('#select-empresa').val());
        carregaEmpregado($('#select-empresa').val());
    }
});

$(document).on('click', '#botao-filtrar', function () {
    carregaHistoricoCaptura(
        $('#select-empresa').val() === "" ? null : $('#select-empresa').val(),
        $('#select-contrato').val() === "" ? null : $('#select-contrato').val(),
        $('#select-mes').val() === 0 ? null : $('#select-mes').val(),
        $('#select-ano').val() === 0 ? null : $('#select-ano').val(),
        $('#select-empregado').val() === 0 ? null : $('#select-empregado').val(),
    );
});

$(document).on('click', '#botao-excluir', function () {
    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    var parametros = {
        acao: 'excluirCaptura',
        id_captura: $('#id_captura').val()
    };

    var dados = {};

    $.post('historico.php', parametros, function (r, b, xhr) {
        dados = toJson(r);
    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalExcluirHistoricoCaptura').modal('hide')
            mensagem.titulo = 'Captura excluída com sucesso';
            mensagem.espera = 2000;
            mensagem.exibe();
            $('#botao-filtrar').click();
            return true;
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });

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
                        $("#select-empresa").append('<option value="' + item.id_empresa + '">' + item.razao + '</option>');
                    });
                }

                if ($('#filtro').val()) {
                    $('#select-empresa').val(JSON.parse($('#filtro').val()).id_empresa);
                    $('#select-empresa').change();
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

                if ($('#filtro').val()) {
                    $('#select-contrato').val(JSON.parse($('#filtro').val()).id_contrato);
                }

            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 2000;
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
                        $("#select-empregado").append('<option value="' + item.id_empregado + '">' + item.id_empregado + ' - ' + item.nome + '</option>');
                    });
                }
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 2000;
        mensagem.exibe();
        return false;
    });
};

$(document).on('click', '.excluir', function () {
    $('#id_captura').val(this.dataset['id']);
    $("#modalExcluirHistoricoCaptura").modal({
        backdrop: 'static'
    });
});