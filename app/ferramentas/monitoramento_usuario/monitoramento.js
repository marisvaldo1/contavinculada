$(document).ready(function () {
    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .ferramentas').click();
    $('#sidebar-menu ul .monitoramento').click();
    $('#sidebar-menu ul .monitoramento').addClass('active');

    carregaUsuario();
});

/*
 * Definições tabela de categorias
 */
var tab_monitoramento = $('#monitoramento').DataTable({
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
        {title: "CLIENTE", className: "text-left"},
        {title: "USUÁRIO", className: "text-left"},
        {title: "EMAIL", className: "text-left"},
        {title: "ACESSO", className: "text-left"},
        {title: "STATUS", className: "text-center"},
        {title: "LOGIN", className: "text-center"},
        {title: "IP", className: "text-center"},
        {title: "LOGADO", className: "text-center"}
    ]
});

var carregaUsuario = function () {

    var dados = {};
    var data, status, xhr;

    $.post('monitoramento.php', {acao: 'listar'}, function (data, status, xhr) {
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
                    confirmacao = (item.nivel_acesso > ADMINISTRADOR)? 'confirmation': '';
                    tab_monitoramento.row.add([
                        `<a href="#" class="alterar" id="${item.id_usuario}"><i class="fa fa-pencil fa-2x"></i></a>`,
                        item.nome_cliente,
                        item.nome,
                        item.email,
                        item.acesso,
                        item.status_usuario,
                        (item.data_login !== '0000-00-00 00:00:00')? moment(item.data_login).format("DD/MM/YYYY HH:mm:ss"): '',
                        item.IP_login,
                        (item.token_login !== '')? 
                            '<a class="fa fa-check fa-lg text-primary mao-link finalizaSessao" data-id=' + item.id_usuario
                            + ' data-indice=' + i 
                            + ' data-toggle="' + confirmacao + '"></a>'
                            : ''
                    ]).draw(false);
                });
                
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 2000;
        mensagem.exibe();
        return false;
    });
};

//Finaliza a sessão de um usuário
$('body').confirmation({
    selector: '[data-toggle=confirmation]',
    title: "Finaliza sessão do usuário?",
    placement: "left",
    btnOkLabel: "&nbsp;Sim",
    btnCancelLabel: "&nbsp;Não",
    onConfirm: function () {
        //Finaliza a sessão do usuário
        var parametros = {
            acao: 'finalizaSessao',
            id_usuario: $(this).attr('data-id')
        };

        var dados = {};
        var indice = parseInt($(this).attr('data-indice'));

        $.post('monitoramento.php', parametros, function (r, b, xhr) {
            dados = toJson(r);

        }).success(function () {
            if (dados.erro === true) {
                mensagem.titulo = dados.mensagem;
                console.log(dados.mensagem);
                mensagem.espera = 3000;
                mensagem.exibe();
                return false;
            } else {
                //document.querySelectorAll("table td:nth-child(7)")[indice].innerHTML = '';
                //document.querySelectorAll("table td:nth-child(8)")[indice].innerHTML = '';
                document.querySelectorAll("table td:nth-child(9)")[indice].innerHTML = '';
                return true;
            }
        }).error(function (xhr) {
            if (xhr.status === 401) {
            }
            //console.error(xhr.status);
            return false;
        });

    },
    onCancel: function () {
    }

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
    var dados = {};

    $.post('categoria.php', {acao: 'listar', id_categoria: this.id}, function (r, b, xhr) {
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

    $.post('categoria.php', {acao: 'excluir', id_categoria: $('#id_categoria').val()}, function (r, b, xhr) {
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
    $('#id_categoria').val(this.id);
    $("#modalExcluirCategoria").modal({
        backdrop: 'static'
    });
});