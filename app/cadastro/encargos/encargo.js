$("#percentual_encargo").maskMoney({allowNegative: false, decimal: '.', affixesStay: true});

// Desativado para usuário visitante
if (USUARIO_VISITANTE) {
    $('.btn-novo-encargo').removeClass('btn-novo-encargo');
}

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    $('#sidebar-menu ul .encargos-sociais').addClass('active');

/*
     * Definições tabela de cargos
     */
    var tab_encargos = $('#tab_encargos').DataTable({
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
            {title: "INSERÇÃO<BR>AUTOMÁTICA", className: "text-center"},
            {title: "%", className: "text-right"}
        ]
    });
    
    var carregaEncargo = function () {

        carregando('on');

        var dados = {};
        var data, status, xhr;

        $.post('encargo.php', {acao: 'listar'}, function (data, status, xhr) {
            dados = toJson(data);

        }).success(function () {
            if (dados.erro === true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            } else {
                if (dados.dados.length > 0) {
                    $.each(dados.dados, function (i, item) {
                        botao = `<input type="checkbox" 
                                 ${(item.insere_automatico_contrato === '1' )? 'checked': ''} 
                                 class="chk-favorito mao-link" data-id="${item.id_encargo}">`
                        tab_encargos.row.add([
                        `   <a href="#" class="excluir" id="${item.id_encargo}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                            <a href="#" class="alterar" id="${item.id_encargo}"><i class="fa fa-pencil fa-2x"></i></a>`,
                            item.nome_encargo,
                            botao,
                            item.percentual_encargo.formatMoney()
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

    carregaEncargo();
});

$(document).on('click', '.btn-novo-encargo', function () {
    //Limpa o formulário
    $('#nome_encargo').val('');
    $('#percentual_encargo').val('');

    //Abre a modal para cadastro
    $("#modalEncargo").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '.chk-favorito', function () {
    var dados = {};

    $.post('encargo.php', {acao: 'mudaInsereAutomatico', id_encargo: $(this).attr('data-id')}, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 3000;
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

});

$(document).on('click', '#botao-salvar', function () {

    //capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_encargo').val() === '') ? 'novo' : 'alterar',
        id_encargo: $('#id_encargo').val(),
        nome_encargo: $('#nome_encargo').val(),
        percentual_encargo: $('#percentual_encargo').val()
    };
    var dados = {};

    $.post('encargo.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalEncargo').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        //console.error(xhr.status);
        return false;
    });
});

$(document).on('click', '.alterar', function () {

    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    var dados = {};

    $.post('encargo.php', {acao: 'listar', id_encargo: this.id}, function (r, b, xhr) {
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
            $('#id_encargo').val(dados.dados[0].id_encargo);
            $('#nome_encargo').val(dados.dados[0].nome_encargo);
            $('#percentual_encargo').val(dados.dados[0].percentual_encargo);
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalEncargo").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('encargo.php', {acao: 'excluir', id_encargo: $('#id_encargo').val()}, function (r, b, xhr) {
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
            mensagem.espera = 5000;
            mensagem.exibe();
            $('#modalEncargo').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
});

$(document).on('click', '.excluir', function () {

    // Desativado para usuário visitante
    if (USUARIO_VISITANTE) { return false; }

    $('#id_encargo').val(this.id);
    $("#modalExcluirEncargo").modal({
        backdrop: 'static'
    });
});