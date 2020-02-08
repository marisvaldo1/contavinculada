$("#remuneracao_cargo").maskMoney({ allowNegative: false, thousands: '.', decimal: ',', affixesStay: true });

// Desativado para usuário visitante
if (USUARIO_VISITANTE) {
    $('.btn-novo-cargo').removeClass('btn-novo-cargo');
}

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    //    $('#sidebar-menu ul .tabelas-basicas').click();
    $('#sidebar-menu ul .cargos').addClass('active');

    /*
     * Definições tabela de cargos
     */
    var tab_cargos = $('#cargos').DataTable({
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
            { title: "AÇÃO", className: "text-center" },
            { title: "NOME", className: "text-left" },
            { title: "TURNO", className: "text-center" },
            { title: "REMUNERAÇÃO", className: "text-right" }
        ]
    });

    var carregaCargo = function () {
        carregando('on');
        // $("#divCargo").html("<div class='text-center'><br><br><br><br><br><i class='fa fa-refresh fa-spin fa-3x fa-fw'></i><span class='sr-only'>Carregando...</span></div>");

        var dados = {};
        var data, status, xhr;

        $.post('cargo.php', { acao: 'listar' }, function (data, status, xhr) {
            dados = toJson(data);

        }).success(function () {
            if (dados.erro == true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 5000;
                mensagem.exibe();
                return false;
            } else {
                if (dados.dados.length > 0) {
                    $.each(dados.dados, function (i, item) {
                        tab_cargos.row.add([
                            `<a href="#" class="excluir" id="${item.id_cargo}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                            <a href="#" class="alterar" id="${item.id_cargo}"><i class="fa fa-pencil fa-2x"></i></a>`,
                            item.nome_cargo,
                            item.turno,
                            'R$ ' + item.remuneracao_cargo.formatMoney()
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

    carregaCargo();
});

$(document).on('click', '.btn-novo-cargo', function () {
    //Limpa o formulário
    $('#formDadosCargo input').each(function () {
        $(this).val('');
    });

    //Abre a modal para cadastro
    $("#modalCargo").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-salvar', function () {

    //Capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_cargo').val() == '') ? 'novo' : 'alterar',
        id_cargo: $('#id_cargo').val(),
        id_turno: $("#seleciona-turno option:selected").val(),
        nome_cargo: $('#nome_cargo').val(),
        remuneracao_cargo: parseFloat($('#remuneracao_cargo').val().replace('.', '').replace(',', '.')),
    };
    var dados = {};

    $.post('cargo.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
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

    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    var dados = {};

    $.post('cargo.php', { acao: 'listar', id_cargo: this.id }, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#id_cargo').val(dados.dados[0].id_cargo);
            $('#nome_cargo').val(dados.dados[0].nome_cargo);
            $('#remuneracao_cargo').val(dados.dados[0].remuneracao_cargo.formatMoney());
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalCargo").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('cargo.php', { acao: 'excluir', id_cargo: $('#id_cargo').val() }, function (r, b, xhr) {
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
    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    $('#id_cargo').val(this.id);
    $("#modalExcluirCargo").modal({
        backdrop: 'static'
    });
});