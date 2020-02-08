$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    $('#sidebar-menu ul .tabelas-basicas').click();
    $('#sidebar-menu ul .indices-retencao').addClass('active');
    
/*
     * Definições tabela de indices
     */
    var tab_indices = $('#tab_indices').DataTable({
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
            {title: "AÇÃO", className: "text-center"},
            {title: "NOME", className: "text-left"},
            {title: "PERCENTUAL", className: "text-right"}
        ]
    });
    
    var carregaIndice = function () {

        $("#divIndice").html("<div class='text-center'><br><br><br><br><br><i class='fa fa-refresh fa-spin fa-3x fa-fw'></i><span class='sr-only'>Carregando...</span></div>");

        var dados = {};
        var data, status, xhr;

        $.post('indice.php', {acao: 'listar'}, function (data, status, xhr) {
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
                        tab_indices.row.add([
                        `   <a href="#" class="excluir" id="${item.id_indice}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                            <a href="#" class="alterar" id="${item.id_indice}"><i class="fa fa-pencil fa-2x"></i></a>`,
                            item.nome_indice,
                            item.percentual_indice + ' %'
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

    carregaIndice();
});

$(document).on('click', '.btn-novo-indice', function () {
    //Limpa o formulário
    $('#formDadosIndice input').each(function () {
        $('#nome_indice').val('');
        $('#percentual_indice').val('');
        $(this).val('');
    });

    //Abre a modal para cadastro
    $("#modalIndice").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-salvar', function () {

    //capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_indice').val() == '')? 'novo': 'alterar',
        id_indice: $('#id_indice').val(),
        nome_indice: $('#nome_indice').val(),
        percentual_indice: $('#percentual_indice').val()
    };
    var dados = {};

    $.post('indice.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalIndice').modal('hide')
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

    $.post('indice.php', {acao: 'listar', id_indice: this.id}, function (r, b, xhr) {
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
            $('#id_indice').val(dados.dados[0].id_indice);
            $('#nome_indice').val(dados.dados[0].nome_indice);
            $('#percentual_indice').val(dados.dados[0].percentual_indice);
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalIndice").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('indice.php', {acao: 'excluir', id_indice: $('#id_indice').val()}, function (r, b, xhr) {
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
            mensagem.espera = 5000;
            mensagem.exibe();
            $('#modalIndice').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });
});

$(document).on('click', '.excluir', function () {
    $('#id_indice').val(this.id);
    $("#modalExcluirIndice").modal({
        backdrop: 'static'
    });
});