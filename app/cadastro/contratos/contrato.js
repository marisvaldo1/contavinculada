// Desativado para usuário visitante
if (USUARIO_VISITANTE) {
    $('.btn-novo-contrato').removeClass('btn-novo-contrato');
}

acao_contrato = '';
$('#cnpj').mask('99.999.999/9999-99');
$('#dt_inicio').mask('99/99/9999');
$('#dt_final').mask('99/99/9999');
$("#valor").maskMoney({allowNegative: false, thousands: '.', decimal: ',', affixesStay: true});
$("#percentual-encargo").maskMoney({allowNegative: false, thousands: '.', decimal: ',', affixesStay: true});

/*
 * Permite mudar o tamanho e o posicionamento da modal
 */
// $('.modal-content').resizable({
//     alsoResize: ".modal-dialog",
//     minHeight: 300,
//     minWidth: 300
// });
// $('.modal-dialog').draggable();

$('#modalContrato').on('show.bs.modal', function () {
    $(this).find('.modal-body').css({
        'max-height': '100%'
    });
});

$(document).ready(function () {

    /*
     * Definições tabela de contratos
     */
    var tab_contratos = $('#tab_contratos').DataTable({
        fixedHeader: true,
        paging: true,
        responsive: true,
        destroy: false,
        clear: false,
        searchable: true,
        searching: true,
        info: true,
        ordering: true,
        "order": [1, 'asc'],
        "oLanguage": {
            "sEmptyTable": "Nenhum registro encontrado",
            "sProcessing": "Processando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "Não foram encontrados resultados",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
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
            {title: "AÇÃO", className: "text-center"},
            {title: "CONTRATO", className: "text-center"},
            {title: "RAZÃO SOCIAL", className: "text-center"},
            {title: "DT INICIO", className: "text-center"},
            {title: "DT FIM", className: "text-center"},
            {title: "OBJETO", className: "text-left"},
            {title: "VALOR", className: "text-right"}
        ],
        'columnDefs': [{
                'targets': [0],
                'searchable': false,
                'orderable': false
            }]
    });

    var carregaContrato = function () {

        carregando('on');

        var dados = {};
        var data, status, xhr;

        empresa = ls.load().empresa;

        $.post('contrato.php', {acao: 'listar', empresa: empresa.id}, function (data, status, xhr) {
            dados = toJson(data);

        }).success(function () {
            if (dados.erro == true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 2000;
                mensagem.exibe();
                return false;
            } else {
                if (dados.dados.length > 0) {
                    var acao = '';
                    var dataSet = [];

                    //<a href="#" class="alterarContrato" id="${item.id_contrato}"><i class="fa fa-pencil fa-2x"></i></a>`,

                    $.each(dados.dados, function (i, item) {
                        tab_contratos.row.add([
                            `<a href="#" class="excluirContrato" id="${item.id_contrato}|${item.id_empresa}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                            <a href="#" class="alterarContrato" id="${item.id_contrato}"><i class="fa fa-pencil fa-2x"></i></a>`,
                            `<a href="#" class="selecionaContrato" data-id="${item.id_empresa}|${item.razao}" id="${item.id_contrato}" name="${item.nu_contrato}">${item.nu_contrato}</a>`,
                            item.razao,
                            item.dt_inicio.formataData(),
                            item.dt_final === null || item.dt_final === '0000-00-00' ? '' : item.dt_final.formataData(),
                            item.objeto_contrato,
                            parseFloat(item.valor).toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})
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
    };

    carregaContrato();

});

$(document).on('click', '.selecionaContrato', function () {
    /*
     * Ao selecionar um contrato, automaticamente seleciona a empresa deste contrato
     */
    $('#quantidade-empresas').html($(this).attr('data-id').split('|')[1].trim().substr(0, 30));
    $('#quantidade-empresas').css({"font-size": "20px"});

    $('#quantidade-contratos').html(this.name.trim().substr(0, 30));
    $('#quantidade-contratos').css({"font-size": "20px"});

    /*
     * Salva a storage com os dados do contrato selecionado
     */
    contaVinculada = ls.load()
    contaVinculada.contrato.id = this.id;
    contaVinculada.contrato.nu_contrato = this.name;
    contaVinculada.empresa.id = $(this).attr('data-id').split('|')[0];
    contaVinculada.empresa.nome = $(this).attr('data-id').split('|')[1];
    contaVinculada.empregado.id = '';
    contaVinculada.empregado.nome = '';
    ls.save(contaVinculada);
    location.href = APP_HTTP + 'index.php';
    return;
});

//$(document).on('click', '.btn-novo-contrato Demo.three()', function () {
$(document).on('click', '.btn-novo-contrato', function () {
    acao_contrato = 'novo';
    //Limpa o formulário
    $('#formDadosContrato')[0].reset();
    $('#dt_final').val('');


    /*
     * Esconde a opção de inserir encargos
     * caso nenhum encargo seja selecionado
     * 
     */
    $('.adicionar-encargo-contrato').hide();

    tab_encargos.clear();
    tab_encargos.draw();
    tab_empregados.clear();
    tab_empregados.draw();

    carregaSelectEmpresas();
    //carregaSelectEmpregados();
    carregaSelectEncargos();

    //Habilita a inserção do número do contrato
    $('#nu_contrato').prop('readonly', false);
    $('#nu_contrato').focus();
    $('#nu_contrato').click();
    $('#select-empresa').attr('disabled', false);

    $('#modalContrato').draggable();

    //Abre a modal para cadastro
    $("#modalContrato").modal({
        backdrop: 'static'
    });

    //$('#dialog').show();

    //Abre a nova janela de diálogo
//    $("#dialog").dialog({
//        title: 'Teste',
//        modal: true,
//        overlay: {background: '#000000', opacity: 0.4},
//        //height: 617,
//        //width: 787,
//        buttons: {
//            "Cancelar": function () {
//                $(this).dialog("close");
//            },
//            Salvar: function () {
//                $(this).dialog("close");
//            }
//        }
//    });
//
//    $("#dialog").dialog("open");

//    $("#dialog").dialog({
//        autoOpen: false,
//        show: {
//            effect: "blind",
//            duration: 1000
//        },
//        hide: {
//            effect: "explode",
//            duration: 1000
//        }
//    });

});

//var carregaSelectEmpregados = function () {
//
//    var data, status, xhr;
//
//    var parametros = {
//        acao: 'listar',
//        id_empresa: $('#select-empresa').val()
//    };
//
//
//    $.post(APP_HTTP + 'cadastro/empregados/empregado.php', parametros, function (data, status, xhr) {
//        dados = toJson(data);
//
//    }).success(function () {
//        if (dados.erro === true) {
//            mensagem.titulo = dados.mensagem;
//            mensagem.espera = 5000;
//            mensagem.exibe();
//            return false;
//        } else {
//            if (dados.dados.length > 0) {
//                //Evita duplicidade na carga da select
//                if ($("#select-empregado").html().trim().length < 50) {
//                    $.each(dados.dados, function (i, item) {
//                        $("#select-empregado").append('<option value="' + item.id_empregado + '|' + item.cpf + "|" + item.nome_cargo + "|" + item.turno + '">' + item.nome + '</option>');
//                    });
//                }
//            }
//        }
//    }).error(function (xhr) {
//        mensagem.titulo = dados.mensagem;
//        mensagem.espera = 5000;
//        mensagem.exibe();
//        return false;
//    });
//};

// Remove a linha de uma tabela
$('body').confirmation({
    selector: '[data-toggle=confirmation]',
    title: "Deseja Excluir?",
    placement: "left",
    btnOkLabel: "&nbsp;Sim",
    btnCancelLabel: "&nbsp;Não",
    onConfirm: function () {
        idItem = $(this).attr('data-id');
        itemValor = $(this).attr('data-valor');

        //Atualiza o percentual total do cabeçalho da tabela de encargos
        total = parseFloat(document.querySelectorAll("table th:nth-child(3)")[1].innerHTML, 2) - parseFloat(itemValor, 2);
        document.querySelectorAll("table th:nth-child(3)")[1].innerHTML = total.toFixed(2) + '%';

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
    },
    onCancel: function () {
    }

});

/*
 * Definições para a tabela de encargos
 */
var tab_encargos = $('#tab_encargos').DataTable({
    //scrollY: '46vh', //Define a quantidade de linhas na tabela e acrescenta scroll automático
    scrollCollapse: true,
    fixedColumns: {
        heightMatch: 'none'
    },
    fixedHeader: false,
    paging: false,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: false,
    searching: false,
    info: false,
    ordering: false,
    "oLanguage": {
        "sEmptyTable": "Nenhum registro encontrado"
    },
    columns: [
        {title: "AÇÃO", className: "text-center"},
        {title: "ENCARGOS", className: "text-center"},
        {title: "PERCENTUAL", className: "text-center"}
    ]
});

$('.adicionar-encargo-contrato').on('click', function () {
    if ($('#select-encargo option:selected').val() !== "0") {
        tab_encargos.row.add([
            '<a class="mao-link excluirEncargo" data-toggle="confirmation"><i class="fa fa-trash fa-lg text-primary"></i></a>'
                    + '<span hidden>' + $('#select-encargo option:selected').val().split('|')[0] + '|' + $('#percentual-encargo').val() + '</span>',
            $('#select-encargo option:selected').text(),
            $('#percentual-encargo').val()
        ]).draw(false);
    } else {
        mensagem.titulo = 'Selecione um encargo para ser inserido no contrato.\n Caso não exista, cadastre o novo encargo para depois inseri-lo neste contrato.';
        mensagem.icone = msg.ICO_EXCLAMATION;
        mensagem.tipo = msg.INFO
        mensagem.espera = msg.COM_ESPERA;
        mensagem.atualiza();
        return false;
    }

    //Altera o cabeçalho colocando o total da coluna
    total = parseFloat(document.querySelectorAll("table th:nth-child(3)")[1].innerHTML, 2) + parseFloat($('#percentual-encargo').val(), 2);
    document.querySelectorAll("table th:nth-child(3)")[1].innerHTML = total.toFixed(2) + '%';

    $('#select-encargo').val(0);
    $('#percentual-encargo').val('');

});

/*
 * Definições tabela de empregados
 */
var tab_empregados = $('#tab_empregados').DataTable({
    //scrollY: '43vh', //Define a quantidade de linhas na tabela e acrescenta scroll automático
    fixedHeader: true,
    paging: true,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: true,
    searching: true,
    info: true,
    ordering: false,
    "oLanguage": {
        "sEmptyTable": "Nenhum registro encontrado",
        "sProcessing": "Processando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "Não foram encontrados resultados",
        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
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
        //{title: "AÇÃO", className: "text-center"},
        {title: "CPF", className: "text-center"},
        {title: "NOME", className: "text-center"},
        {title: "CARGO", className: "text-center"},
        {title: "TURNO", className: "text-center"}
    ]
});

//Adiciona empregados ao contrato
//$('.adicionar-empregado-contrato').on('click', function () {
//    if ($('#select-empregado option:selected').val() !== 0) {
//        tab_empregados.row.add([
//            //'<a class="mao-link excluirEmpregado" data-toggle="confirmation"><i class="fa fa-trash fa-2x text-primary"></i></a>'
//            '<span hidden>' + $('#select-empregado option:selected').val().split('|')[0] + '|</span>' + 
//            $('#cpf').val(),
//            $("#select-empregado option:selected").text(),
//            $('#select-empregado option:selected').val().split('|')[2],
//            $('#select-empregado option:selected').val().split('|')[3]
//        ]).draw(true);
//
//    }
//
//    $('#cpf').val('');
//    $('#select-empregado').val('');
//    $('#remuneracao').val('');
//
//});

function carregaEmpregadosContrato(id_contrato, id_empresa) {
    var parametros = {
        acao: 'listarEmpregadosContrato',
        id_contrato: id_contrato,
        id_empresa: id_empresa
    };

    $.post('contrato.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            //Carrega os empregados do contrato
            if (dados.dados.length > 0) {
                $.each(dados.dados, function (i, item) {
                    tab_empregados.row.add([
                        //'<a class="mao-link excluirEmpregado" data-toggle="confirmation"><i class="fa fa-trash fa-2x text-primary"></i></a>'
                        '<span hidden>' + item.id_empregado + '</span>' +
                                item.cpf,
                        item.nome,
                        item.cargo,
                        item.turno
                    ]).draw(false);
                });
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
}

$(document).on('blur', '.percentual', function () {
    console.log($(this).val());
});

$(document).on('click', '.encargo', function () {
    var id = $(this).attr('data-encargo').split('|')[1];
    $('input[data-percentual-encargo="percentual-encargo-' + id).attr('disabled', !this.checked);
    $('input[data-percentual-encargo="percentual-encargo-' + id).focus();
});

$(document).on('click', '.adicionar-empregado-contrato', function () {
});

$(document).on('click', '#botao-salvar', function () {
    //Verifica os encargos selecionados
    var encargos = [];

    //Captura os dados da datatable e coloca dentro de um array
    $("#tab_encargos tbody tr").each(function () {
        var tableData = $(this).find('td span');
        if (tableData.length > 0) {
            encargos.push({
                "id": tableData.text().split('|')[0].trim(),
                "valor": parseFloat(tableData.text().split('|')[1].trim().replace(',', '.'))
            });
        }
    });

    //Verifica os empregados selecionados
    //var empregados = [];

    //Captura os dados da datatable e coloca dentro de um array
//    $("#tab_empregados tbody tr").each(function () {
//        var tableData = $(this).find('td span');
//        if (tableData.length > 0) {
//            empregados.push({
//                "id_empregado": tableData.text().split('|')[0].trim()
//            });
//        }
//    });

    var parametros = {
        acao: ($('#id_contrato').val() === '') ? 'novo' : 'alterar',
        id_contrato: $('#id_contrato').val(),
        id_empresa: $('#select-empresa option:selected').val().split('|')[0],
        nu_contrato: $('#nu_contrato').val(),
        dt_inicio: $('#dt_inicio').val(),
        dt_final: $('#dt_final').val(),
        valor: parseFloat($('#valor').val().replace(/[.]/g, "").replace(',', '.')),
        objeto: $('#objeto').val(),
        encargos: encargos
                //empregados: empregados
    };

    var dados = {};

    $.post('contrato.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalContrato').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        //console.error(xhr.status);
        return false;
    });
});

$(document).on('click', '.alterarContrato', function () {

    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    var dados = {};
    acao_contrato = 'alterar';

    /*
     * Na alteração, permite inserção de encargos no contrato
     */
    $('.adicionar-encargo-contrato').show();

    //Limpa as informações do formulário
    $('#formDadosContrato')[0].reset();
    tab_encargos.clear();
    tab_encargos.draw();
    tab_empregados.clear();
    tab_empregados.draw();

    $.post('contrato.php', {acao: 'listar', id_contrato: this.id}, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#id_contrato').val(dados.dados[0].id_contrato);
            $('#nu_contrato').val(dados.dados[0].nu_contrato);
            $('#dt_inicio').val(dados.dados[0].dt_inicio.formataData());
            $('#dt_final').val(dados.dados[0].dt_final === null || dados.dados[0].dt_final === '0000-00-00' ? '' : dados.dados[0].dt_final.formataData());
            $('#valor').val(dados.dados[0].valor.formatMoney());
            $('#objeto').val(dados.dados[0].objeto_contrato);
            $("#select-empresa").append('<option value="' + dados.dados[0].id_empresa + '">' + dados.dados[0].razao + '</option>');
            $('.cnpj').html(dados.dados[0].cnpj);

            //Monta a tabela de encargos existentes neste contrato
            var acao = '';

            carregaEncargosContrato(dados.dados[0].id_contrato, dados.dados[0].id_empresa);
            carregaEmpregadosContrato(dados.dados[0].id_contrato, dados.dados[0].id_empresa);

            carregaSelectEmpresas();
            //carregaSelectEmpregados();
            carregaSelectEncargos();

        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });

    //Não permite a alteração do contrato
    $('#nu_contrato').prop('readonly', true);
    $('#select-empresa').attr('disabled', true);
    $('#dt_final').val('');

    //Mostra o modal com os dados para a alteração
    $("#modalContrato").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    
    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    var dados = {};

    $.post('contrato.php', {acao: 'excluir', id_contrato: $('#id_contrato').val()}, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            mensagem.titulo = 'Registro excluído com sucesso';
            mensagem.espera = 1000;
            mensagem.exibe();
            $('#modalContrato').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });
});

$(document).on('click', '.excluirContrato', function () {

    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    var parametros = {
        acao: 'listarLancamentosContrato',
        id_contrato: this.id.split('|')[0],
        id_empresa: this.id.split('|')[1]
    };

    $('#id_contrato').val(this.id.split('|')[0]);
    
    var dados = {};

    /*
     * Verifica se o contrato possui lançamentos de reteções e liberações
     * Se sim, não permite sua exclusão.
     * 
     */
    $.post('contrato.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            if (parseInt(dados.quantidade) > 0) {
                mensagem.titulo = 'Contrato não pode ser excluído pois possui Retenções/Liberações';
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            }

            $("#modalExcluirContrato").modal({
                backdrop: 'static'
            });

        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
});

var carregaSelectEmpresas = function () {

    var data, status, xhr;

    $.post(APP_HTTP + 'cadastro/empresas/empresa.php', {acao: 'listar'}, function (data, status, xhr) {
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
                        $("#select-empresa").append('<option value="' + item.id_empresa + '|' + item.cnpj + '">' + item.razao + '</option>');
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
var carregaSelectEncargos = function () {

    var dados = {};
    var data, status, xhr;

    $.post(APP_HTTP + 'cadastro/encargos/encargo.php', {acao: 'listar'}, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {

            var totalEncargos = 0;

            /*
             * Limpa os dados do select para evitar duplicidade
             */
            $("#select-encargo").empty();

            if (dados.dados.length > 0) {

                $("#select-encargo").append('<option value="0">Selecione</option>');
                $.each(dados.dados, function (i, item) {
                    /*
                     * Carrega a select com todos os encargos cadastrados e 
                     */
                    //if ($("#select-encargo").html().trim().length < 50) {
                    $("#select-encargo").append('<option value="' + item.id_encargo + '|' + item.percentual_encargo + '">' + item.nome_encargo + '</option>');
                    //}

                    /*
                     * Carrega o datatable de encargos com todos 
                     * os encargos marcados para inserção automática
                     */
                    if (acao_contrato === 'novo') {
                        if (item.insere_automatico_contrato === '1') {
                            tab_encargos.row.add([
                                '<a class="mao-link excluirEncargo" data-id=' + item.id_encargo + ' data-toggle="confirmation"><i class="fa fa-trash fa-lg text-primary"></i></a>'
                                        + '<span hidden>' + item.id_encargo + '|' + item.percentual_encargo + '</span>',
                                item.nome_encargo,
                                item.percentual_encargo
                            ]).draw(false);
                            totalEncargos += parseFloat(item.percentual_encargo, 2);
                        }
                    }
                });
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
};
$(document).on('change', '#select-encargo', function () {
    $('#percentual-encargo').val(parseFloat($('#select-encargo').val().split('|')[1]));
    $('#percentual-encargo').focus();
});

function carregaEncargosContrato(id_contrato, id_empresa) {
    var parametros = {
        acao: 'listarEncargosContrato',
        id_contrato: id_contrato,
        id_empresa: id_empresa
    };

    $.post('contrato.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            //Carrega os encargos do contrato
            var totalEncargos = 0;
            if (dados.dados.length > 0) {
                var acao = '';

                $.each(dados.dados, function (i, item) {
                    tab_encargos.row.add([
                        '<a class="mao-link excluirEncargo" data-id=' + item.id_encargo + ' data-valor=' + item.percentual_encargo +
                                ' data-toggle="confirmation"><i class="fa fa-trash fa-lg text-primary"></i></a>'
                                + '<span hidden>' + item.id_encargo + '|' + item.percentual_encargo + '</span>',
                        item.nome_encargo,
                        item.percentual_encargo
                    ]).draw(false);
                    totalEncargos += parseFloat(item.percentual_encargo, 2);

                });

                //Altera o cabeçalho colocando o total da coluna
                document.querySelectorAll("table th:nth-child(3)")[1].innerHTML = totalEncargos.toFixed(2) + '%';
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
}

function carregaEncargosNovoContrato(id_empresa) {
    var parametros = {
        acao: 'listarEncargosNovoContrato',
        id_contrato: id_contrato,
        id_empresa: id_empresa
    };

    $.post('contrato.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            //Carrega os encargos do contrato
            var totalEncargos = 0;
            if (dados.dados.length > 0) {
                var acao = '';

                $.each(dados.dados, function (i, item) {
                    tab_encargos.row.add([
                        '<a class="mao-link excluirEncargo" data-toggle="confirmation"><i class="fa fa-trash fa-lg text-primary"></i></a>'
                                + '<span hidden>' + item.id_encargo + '|' + item.percentual_encargo + '</span>',
                        item.nome_encargo,
                        item.percentual_encargo
                    ]).draw(false);
                    totalEncargos += parseFloat(item.percentual_encargo, 2);

                });

                //Altera o cabeçalho colocando o total da coluna
                document.querySelectorAll("table th:nth-child(3)")[1].innerHTML = totalEncargos.toFixed(2) + '%';
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
    console.log(this.value);
    $('.cnpj').html($('#select-empresa').val().split('|')[1]);
});

$(document).on('change', '#select-empregado', function () {
    $('#cpf').val(this.value.split('|')[1]);
});