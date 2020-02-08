$("#valor_contrato").maskMoney({allowNegative: false, thousands: '.', decimal: ',', affixesStay: true});

//Controla a inserção de parcelas
insere_parcelas = false;

/*
 * Definições tabela de contratos
 */
var tab_contratos = $('#contratos').DataTable({
    fixedHeader: true,
    paging: true,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: true,
    searching: true,
    info: true,
    ordering: true,
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
        {title: "Ação", className: "text-center"},
        {title: "Cnpj", className: "text-center"},
        {title: "Razão", className: "text-center"},
        {title: "Contrato", className: "text-center"},
        {title: "Dt Início", className: "text-center"},
        {title: "Dt Final", className: "text-center"},
        {title: "Tipo", className: "text-center"},
        {title: "Valor", className: "text-right"}
        //{title: "Status", className: "text-center"}
    ]
});

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .controle-contratos').click();
    $('#sidebar-menu ul .contrato_sistema').click();
    $('#sidebar-menu ul .contrato_sistema').addClass('active');

    carregaContrato();
});

var carregaContrato = function () {

    $("#divContrato").html("<div class='text-center'><br><br><br><br><br><i class='fa fa-refresh fa-spin fa-3x fa-fw'></i><span class='sr-only'>Carregando...</span></div>");

    var dados = {};
    var data, status, xhr;

    $.post('contrato_sistema.php', {acao: 'listar'}, function (data, status, xhr) {
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
                    tab_contratos.row.add([
                        `<a href="#" class="excluir" id="${item.id_contrato}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                             <a href="#" class="alterarContrato" id="${item.id_contrato}"><i class="fa fa-pencil fa-2x"></i></a>`,
                        item.cnpj,
                        item.razao,
                        item.nu_contrato_sistema,
                        item.dt_inicio.formataData(),
                        item.dt_final.formataData(),
                        item.tipo_pagamento,
                        parseFloat(item.valor_contrato).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})
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
};

var tab_parcelas = $('#tab_parcelas').DataTable({
    scrollY: '43vh', //Define a quantidade de linhas na tabela e acrescenta scroll automático
    clear: false,
    fixedHeader: true,
    paging: false,
    responsive: true,
    searchable: false,
    searching: false,
    info: false,
    ordering: true,
    "order": [0, 'asc'],
    "oLanguage": {
        "sEmptyTable": "Nenhum registro encontrado",
        "sZeroRecords": "Não foram encontrados resultados"
    },
    columns: [
        //{title: "ACAO", "rderable": "false", className: "text-center"},
        {title: "PARCELA", className: "text-center"},
        {title: "DT VENCIMENTO", className: "text-center"},
        {title: "DT PAGAMENTO", className: "text-center"},
        {title: "VALOR", className: "text-center"}
    ]
});

var carregaParcelas = function (id_contrato, id_cliente) {

    var dados = {};
    var data, status, xhr;

    var parametros = {
        acao: 'listar',
        id_contrato: id_contrato,
        id_cliente: id_cliente
    };

    tab_parcelas.clear();

    $.post(APP_HTTP + 'controle_contrato/pagamento/pagamento.php', parametros, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = 'Nenhuma parcela gerada para este contrato. Verifique.'//dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();

            $("button[id*='botao-gerar-parcelas']").show();

            //Sinaliza a inserção de parcelas no banco
            insere_parcelas = true;

            return false;
        } else {
            if (dados.dados.length > 0) {
                $.each(dados.dados, function (i, item) {
                    tab_parcelas.row.add([
                        item.id_parcela,
                        item.data_vencimento.formataData(),
                        (item.data_pagamento === null) ? '' : item.data_pagamento.formataData(),
                        parseFloat(item.valor_parcela).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})
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
};

/*
 * Função executada ao mostrar a janela modal
 * 
 * Utilizada para refazer o cabeçalho da dataTable que se perde ao usar o limite de scrollY
 */
$('#modalContratoSistema').on('shown.bs.modal', function (e) {
    $.fn.dataTable.tables({visible: true, api: true}).columns.adjust();
    $("#select-cliente").val($('#id_cliente').val());
});

$(document).on('click', '.btn-novo-contrato', function () {
    //Limpa o formulário
    $('#formDadosContrato input').each(function () {
        $(this).val('');
    });

    carregaSelectClientesSemContrato();

});

$(document).on('click', '#select-cliente', function () {
    $('.cnpj').text($(this).val().split('|')[1]);
});

var carregaSelectClientes = function () {

    var data, status, xhr;

    $.post(APP_HTTP + 'cadastro/clientes/cliente.php', {acao: 'listar'}, function (data, status, xhr) {
        //$.post('contrato_sistema.php', {acao: 'listarClientesSemContrato'}, function (data, status, xhr) {
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
                if ($("#select-cliente").html().trim().length < 50) {

                    $.each(dados.dados, function (i, item) {
                        //$("#select-cliente").append('<option value="' + item.id_cliente + '|' + item.cnpj + '">' + item.razao + '</option>');
                        $("#select-cliente").append('<option value="' + item.id_cliente + '">' + item.razao + '</option>');
                    });
                    $("#select-cliente").attr('disabled', false);
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
var carregaSelectClientesSemContrato = function () {

    var data, status, xhr;

    $.post('contrato_sistema.php', {acao: 'listarClientesSemContrato'}, function (data, status, xhr) {
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
                if ($("#select-cliente").html().trim().length < 50) {

                    $.each(dados.dados, function (i, item) {
                        //$("#select-cliente").append('<option value="' + item.id_cliente + '-' + item.cnpj + '">' + item.razao + '</option>');
                        $("#select-cliente").append('<option value="' + item.id_cliente + '">' + item.razao + '</option>');
                    });
                    $("#select-cliente").attr('disabled', false);
                }
            }
            //Abre a modal para cadastro
            $("#modalContratoSistema").modal({
                backdrop: 'static'
            });

            //Mostra o botão para gerar parcelas
            $("button[id*='botao-gerar-parcelas']").show();
            $('#tab_parcelas').dataTable().Clear();

        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 2000;
        mensagem.exibe();
        return false;
    });
};

$(document).on('click', '#botao-salvar', function () {

    if( $('#valor_contrato').val() === '' )
        $('#valor_contrato').val('0');
        
    valorContrato = parseFloat($('#valor_contrato').val().replace(/[.R$]+/g, "").replace(',', '.'))

    if ( valorContrato < 1 ) {
        field = {id: '#valor_contrato', erro: true, message: 'Valor inválido.'};
        tratarCampoLupa(field);
        return false;
    }

    var parcelas = [];

    //Captura os dados da datatable e coloca dentro de um array
    $("#tab_parcelas tbody tr").each(function () {
        var tableData = $(this).find('td span');
        if (tableData.length > 0) {
            parcelas.push({
                "id_parcela": tableData.text().split('-')[0].trim(),
                "dt_vencimento": tableData.text().split('-')[1].trim(),
                "valor": parseFloat(tableData.text().split('-')[2].trim().replace('R$', '').replace('.', '').replace(',', '.').trim())
            });
        }
    });

    //Capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_contrato').val() === '') ? 'novo' : 'alterar',
        id_contrato: $('#id_contrato').val(),
        nu_contrato_sistema: $('#nu_contrato_sistema').val(),
        id_cliente: $('#select-cliente option:selected').val(),
        dt_inicio: $('#dt_inicio').val(),
        dt_final: $('#dt_final').val(),
        tipo_pagamento: $('#tipo_pagamento').val(),
        valor_contrato: parseFloat($('#valor_contrato').val().replace(/[.R$]+/g, "").replace(',', '.')),
        parcelas: parcelas,
        insere_parcelas: insere_parcelas
    };
    var dados = {};

    $.post('contrato_sistema.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalContratoSistema').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        //console.error(xhr.status);
        return false;
    });
});

$(document).on('click', '.alterarContrato', function () {
    var dados = {};

    $.post('contrato_sistema.php', {acao: 'listar', id_contrato: this.id}, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            $('#id_contrato').val(dados.dados[0].id_contrato);
            $('#id_cliente').val(dados.dados[0].id_cliente);
            $('.cnpj').html(dados.dados[0].cnpj);
            $('#nome_contrato').val(dados.dados[0].nome_contrato);
            $('#nu_contrato_sistema').val(dados.dados[0].nu_contrato_sistema);
            $('#dt_inicio').val(dados.dados[0].dt_inicio.formataData());
            $('#dt_final').val(dados.dados[0].dt_final.formataData());
            $('#tipo_pagamento').val(dados.dados[0].tipo_pagamento);
            $('#valor_contrato').val(parseFloat(dados.dados[0].valor_contrato).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'}));

            carregaParcelas(dados.dados[0].id_contrato, dados.dados[0].id_cliente);

            $("#select-cliente").attr('disabled', true);

        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });

    carregaSelectClientes();

    //Esconde o botão após o primeiro click
    $("button[id*='botao-gerar-parcelas']").hide();

    //$("#select-cliente").val($('#id_cliente').val());

    //Mostra o modal com os dados para a alteração
    $("#modalContratoSistema").modal({
        backdrop: 'static'
    });
    $("#modalContratoSistema").modal('show');

});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('contrato_sistema.php', {acao: 'excluir', id_contrato: $('#id_contrato').val()}, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            mensagem.titulo = 'Registro excluído com sucesso';
            mensagem.espera = 1000;
            mensagem.exibe();
            $('#modalContratoSistema').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });
});

$(document).on('click', '.excluir', function () {
    $('#id_contrato').val(this.id);
    $("#modalExcluirContrato").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-gerar-parcelas', function () {
    var parcelas = 0;

    var dataSet = [];
    var dataInicio = moment($('#dt_inicio').val(), 'DD/MM/YYYY');
    var dataFinal = moment($('#dt_final').val(), 'DD/MM/YYYY');

    if ($('#tipo_pagamento').val() === 'M') {

        var parcelas = parseInt(calculaDias(dataInicio, dataFinal) / 30);
        var valorParcelas = parseFloat($('#valor_contrato').val().toLocaleString('pt-br').replace('.', '').replace(',', '.')) / parcelas;

        for (var i = 1; i <= parcelas; i++) {
            dataSet.push([
                i
                        + '<span hidden>' + i
                        + '-' + moment(dataInicio).add(30 * i, 'days').format('DD/MM/YYYY')
                        + '-' + parseFloat(valorParcelas).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})
                        + '</span>',
                moment(dataInicio).add(30 * i, 'days').format('DD/MM/YYYY'),
                '',
                parseFloat(valorParcelas).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})
            ]);

        }
    } else {
        dataSet.push([
            1
                    + '<span hidden>' + 1
                    + '-' + dataInicio.format('DD/MM/YYYY')
                    + '-' + $('#valor_contrato').val()
                    + '</span>',
            moment(dataInicio).format('DD/MM/YYYY'),
            '',
            //$('#valor_contrato').val()
            $('#valor_contrato').val().toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})

        ]);
    }
    ;

    $('#tab_parcelas').DataTable({
        columns: [
            {title: "PARCELA", className: "text-center"},
            {title: "DT VENCIMENTO", className: "text-center"},
            {title: "DT PAGAMENTO", className: "text-center"},
            {title: "VALOR", className: "text-center"}
        ],
        data: dataSet
    });

    //Esconde o botão após o primeiro click
    $("button[id*='botao-gerar-parcelas']").hide();

});