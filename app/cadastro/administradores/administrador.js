$(document).ready(function () {
    
    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    $('#sidebar-menu ul .controle').click();
    $('#sidebar-menu ul .administradores').addClass('active');
    
    var carregaAdministrador = function () {

        $("#divAdministrador").html("<div class='text-center'><br><br><br><br><br><i class='fa fa-refresh fa-spin fa-3x fa-fw'></i><span class='sr-only'>Carregando...</span></div>");

        var dados = {};
        var data, status, xhr;

        $.post('administrador.php', {acao: 'listar'}, function (data, status, xhr) {
            dados = toJson(data);

        }).success(function () {
            if (dados.erro == true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 5000;
                mensagem.exibe();
                return false;
            } else {
                if (dados.dados.length > 0) {
                    var cabecalhoAdministrador = `
                         <table class="table table-bordered table-hover display administradores" id="administradores">
                            <thead>
                                <tr class="text-uppercase">
                                    <th data-titulo="Acao" class="text-center">#</th>
                                    <th data-titulo="Nome" class="text-center">Nome</th>
                                    <th data-titulo="e-mail" class="text-center">e-mail</th>
                                    <th data-titulo="Telefone" class="text-center">Telefone</th>
                                    <th data-titulo="Status" class="text-center">Status</th>
                                </tr>
                            </thead>
                         </table>`;

                    $("#divAdministrador").html(cabecalhoAdministrador);

                    var acao = '';
                    var dataSet = [];

                    $.each(dados.dados, function (i, item) {
                        acao = `
                                <a href="#" class="excluir" id="${item.id_administrador}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                                <a href="#" class="alterar" id="${item.id_administrador}"><i class="fa fa-pencil fa-2x"></i></a>`;

                        dataSet.push([
                            acao,
                            item.nome_administrador,
                            item.email,
                            item.telefone,
                            item.status_administrador
                        ]);
                    });

                    $('#administradores').DataTable({
                        data: dataSet,
                        columns: [
                            {title: "Ação", className: "text-center"},
                            {title: "Nome", className: "text-center"},
                            {title: "email", className: "text-center"},
                            {title: "Telefone", className: "text-center"},
                            {title: "Status", className: "text-center"}
                        ]
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

    carregaAdministrador();
});

$(document).on('click', '.btn-novo-administrador', function () {
    //Limpa o formulário
    $('#formDadosAdministrador input').each(function () {
        $(this).val('');
    });

    //Abre a modal para cadastro
    $("#modalAdministrador").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-salvar', function () {

    //Capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_administrador').val() == '') ? 'novo' : 'alterar',
        id_administrador: $('#id_administrador').val(),
        nome_administrador: $('#nome_administrador').val(),
        email: $('#email').val(),
        telefone: $('#telefone').val()
    };
    var dados = {};

    $.post('administrador.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalAdministrador').modal('hide')
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

    $.post('administrador.php', {acao: 'listar', id_administrador: this.id}, function (r, b, xhr) {
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
            $('#id_administrador').val(dados.dados[0].id_administrador);
            $('#nome_administrador').val(dados.dados[0].nome_administrador);
            $('#email').val(dados.dados[0].email);
            $('#telefone').val(dados.dados[0].telefone);
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalAdministrador").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('administrador.php', {acao: 'excluir', id_administrador: $('#id_administrador').val()}, function (r, b, xhr) {
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
            $('#modalAdministrador').modal('hide')
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });
});

$(document).on('click', '.excluir', function () {
    $('#id_administrador').val(this.id);
    $("#modalExcluirAdministrador").modal({
        backdrop: 'static'
    });
});