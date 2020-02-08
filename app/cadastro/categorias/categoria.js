// Desativado para usuário visitante
if (USUARIO_VISITANTE) {
    $('.btn-novo-cargo').removeClass('btn-novo-cargo');
}

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    //    $('#sidebar-menu ul .tabelas-basicas').click();
    $('#sidebar-menu ul .categorias').addClass('active');

    /*
     * Definições tabela de categorias
     */
    var tab_categorias = $('#categorias').DataTable({
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
            { title: "AÇÃO", className: "text-center" },
            { title: "NOME", className: "text-left" },
            { title: "STATUS", className: "text-center" }
        ]
    });

    var carregaCategoria = function () {

        carregando('on');

        var dados = {};
        var data, status, xhr;

        $.post('categoria.php', { acao: 'listar' }, function (data, status, xhr) {
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
                        tab_categorias.row.add([
                            `<a href="#" class="excluir" id="${item.id_categoria}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                                <a href="#" class="alterar" id="${item.id_categoria}"><i class="fa fa-pencil fa-2x"></i></a>`,
                            item.nome_categoria,
                            item.status_categoria
                        ]).draw(false);
                    });

                }
            }
        }).complete(function () {
            carregando('off');
        }).error(function (xhr) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        });
    };

    carregaCategoria();
});

$(document).on('click', '.btn-novo-categoria', function () {
    //Limpa o formulário
    $('#formDadosCategoria input').each(function () {
        $(this).val('');
    });

    //Abre a modal para cadastro
    $("#modalCategoria").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-salvar', function () {

    //Capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_categoria').val() == '') ? 'novo' : 'alterar',
        id_categoria: $('#id_categoria').val(),
        nome_categoria: $('#nome_categoria').val(),
        status_categoria: $('#status_categoria').val()
    };
    var dados = {};

    $.post('categoria.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalCategoria').modal('hide')
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

    $.post('categoria.php', { acao: 'listar', id_categoria: this.id }, function (r, b, xhr) {
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
            $('#id_categoria').val(dados.dados[0].id_categoria);
            $('#nome_categoria').val(dados.dados[0].nome_categoria);
            $('#status_categoria').val(dados.dados[0].status_categoria);
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalCategoria").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('categoria.php', { acao: 'excluir', id_categoria: $('#id_categoria').val() }, function (r, b, xhr) {
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
            $('#modalCategoria').modal('hide')
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

    $('#id_categoria').val(this.id);
    $("#modalExcluirCategoria").modal({
        backdrop: 'static'
    });
});