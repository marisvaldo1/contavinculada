$(document).ready(function () {
    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    $('#sidebar-menu ul .controle').click();
    $('#sidebar-menu ul .usuarios').addClass('active');

    carregaUsuario();
    carregaClientes();
});

var carregaUsuario = function () {

    carregando('on');

    var dados = {};
    var data, status, xhr;

    $.post('usuario.php', { acao: 'listar' }, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                var cabecalhoUsuario = `
                     <table class="table table-bordered table-hover display usuarios" id="usuarios">
                        <thead>
                            <tr class="text-uppercase">
                                <th data-titulo="Acao" class="text-center">#</th>
                                <th data-titulo="Nome" class="text-center">Cnpj</th>
                                <th data-titulo="Email" class="text-center">Razão</th>
                                <th data-titulo="Acesso" class="text-center">Nível</th>
                                <th data-titulo="Cliente" class="text-center">Cliente</th>
                            </tr>
                        </thead>
                     </table>`;

                $("#divUsuario").html(cabecalhoUsuario);

                var acao = '';
                var dataSet = [];

                $.each(dados.dados, function (i, item) {
                    acao = `
                            <a href="#" class="excluir" id="${item.id_usuario}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                            <a href="#" class="alterar" id="${item.id_usuario}"><i class="fa fa-pencil fa-2x"></i></a>`;

                    dataSet.push([
                        acao,
                        item.nome,
                        item.email,
                        item.acesso,
                        item.nome_cliente
                    ]);
                });

                $('#usuarios').DataTable({
                    data: dataSet,
                    columns: [
                        { title: "Ação", className: "text-center"},
                        { title: "Nome", className: "text-center" },
                        { title: "Email", className: "text-center" },
                        { title: "Acesso", className: "text-center" },
                        { title: "Cliente", className: "text-center" }
                    ]
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

var carregaClientes = function () {

    var data, status, xhr;

    $.post(APP_HTTP + 'cadastro/clientes/cliente.php', { acao: 'listar' }, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                $.each(dados.dados, function (i, item) {
                    $("#cliente-usuario").append('<option value="' + this.id_cliente + '">' + this.razao + '</option>');
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

// Habilita / desabilita a combo de acordo com o nível de acesso cadastrado
$(document).on('change', '#nivel-acesso', function () {
    $("#cliente-usuario").prop('disabled', this.value === 0); //'ADMINISTRADOR'));
    $("#select-empresa").prop('disabled', this.value != 4); //'VISITANTE'));
});

$(document).on('change', '#cliente-usuario', function () {
    $('#select-empresa').children('option:not(:first)').remove();

    if ($('#cliente-usuario').val() !== "") {
        carregaSelectEmpresas($('#cliente-usuario').val());
    }
});

var carregaSelectEmpresas = function (id_cliente) {

    var data, status, xhr;

    var parametros = {
        acao: 'listar',
        id_cliente: id_cliente,
    };

    $.post(APP_HTTP + 'cadastro/empresas/empresa.php', parametros, function (data, status, xhr) {

        dados = toJson(data);

    }).success(function () {
        if (dados.erro === true) {
            return false;
        } else {
            if (dados.dados.length > 0) {

                //Evita duplicidade na carga da select
                if ($("#select-empresa").html().trim().length < 50) {

                    $.each(dados.dados, function (i, item) {
                        $("#select-empresa").append('<option value="' + item.id_empresa + '">' + item.razao + '</option>');
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

$(document).on('click', '.btn-novo-usuario', function () {
    //Limpa o formulário
    $('.form-dados-usuario input').each(function () {
        $(this).val('');
    });

    $('#id_usuario').val('');
    $('#nivel-acesso option').remove();

    //Somente usuários administradores podem cadastrar administradores
    if (NIVEL_ACESSO === 0) {
        $('#nivel-acesso').append('<option value="0">ADMINISTRADOR</option>');
        $("#cliente-usuario").prop('disabled', true);
    }

    $('select#nivel-acesso').append('<option value="1">USUARIO</option>');
    $('select#nivel-acesso').append('<option value="2">CLIENTE</option>');
    // $('select#nivel-acesso').append('<option value="3">FUNCIONARIO</option>');
    $('select#nivel-acesso').append('<option value="4">VISITANTE</option>');

    $("#modalUsuario").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-salvar', function () {

    //Usuário não pode ser vazio
    if ($('#nome').val() === '') {
        mensagem.titulo = 'Por favor informe um nome de usuário.';
        mensagem.icone = msg.ICO_EXCLAMATION;
        mensagem.tipo = msg.WARNING;
        mensagem.espera = msg.COM_ESPERA;
        mensagem.exibe();
        $('#nome').focus();
        return false;
    }

    //e-mail não pode ser vazio
    if ($('#email').val() === '') {
        mensagem.titulo = 'O email é obrigatório. Verifique';
        mensagem.icone = msg.ICO_EXCLAMATION;
        mensagem.tipo = msg.WARNING;
        mensagem.espera = msg.COM_ESPERA;
        mensagem.exibe();
        $('#email').focus();
        return false;
    } else {
        //Valida o email
        var sEmail = $("#email").val();
        var emailFilter = /^.+@.+\..{2,}$/;
        var illegalChars = /[\(\)\<\>\,\;\:\\\/\"\[\]]/
        // condição
        if (!(emailFilter.test(sEmail)) || sEmail.match(illegalChars)) {
            mensagem.titulo = 'E-mail inválido. Verifique';
            mensagem.icone = msg.ICO_EXCLAMATION;
            mensagem.tipo = msg.WARNING;
            mensagem.espera = msg.COM_ESPERA;
            mensagem.exibe();
            $('#email').focus();
            return false;
        }
    }

    //Senha não pode ser vazia
    if ($('#senha').val() === '' && $('#confirmar-senha').val() === '') {
        mensagem.titulo = 'A senha e a confirmação da senha são obrigatórias. Verifique';
        mensagem.icone = msg.ICO_EXCLAMATION;
        mensagem.tipo = msg.WARNING;
        mensagem.espera = msg.COM_ESPERA;
        mensagem.exibe();
        $('#senha').focus();
        return false;
    }

    //Confirma se as senhas são iguais
    if ($('#senha').val() !== $('#confirmar-senha').val()) {
        mensagem.titulo = 'Senhas não são iguais. Verifique';
        mensagem.icone = msg.ICO_EXCLAMATION;
        mensagem.tipo = msg.WARNING;
        mensagem.espera = msg.COM_ESPERA;
        mensagem.exibe();
        $('#confirmar-senha').focus();
        return false;
    }

    //Se o cadastro for de visitante a seleção da empresa é obrigatória
    if (parseInt($("#nivel-acesso option:selected").val()) === VISITANTE && $("#select-empresa option:selected").val() === "0") {
        mensagem.titulo = 'Para cadastrar um visitante é necessário definir a empresa onde ele pode visitar.';
        mensagem.icone = msg.ICO_EXCLAMATION;
        mensagem.tipo = msg.WARNING;
        mensagem.espera = msg.COM_ESPERA;
        mensagem.exibe();
        $('#select-empresa').focus();
        return false;
    }

    //capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_usuario').val() == '') ? 'novo' : 'alterar',
        id_usuario: $('#id_usuario').val(),
        nome: $('#nome').val(),
        email: $('#email').val(),
        senha: $('#senha').val(),
        nivel_acesso: $("#nivel-acesso option:selected").val(),
        id_cliente: $('#cliente-usuario').val(),
        id_empresa: $('#select-empresa').val()
    };
    var dados = {};

    $.post('usuario.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalUsuario').modal('hide')
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

    //capturando todos os campos input do formulario
    var parametros = {
        acao: 'listar',
        id_usuario: this.id
    };

    $.post('usuario.php', parametros, function (r, b, xhr) {
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
            $('#id_usuario').val(dados.dados[0].id_usuario);
            $('#nome').val(dados.dados[0].nome);
            $('#email').val(dados.dados[0].email);

            $('#nivel-acesso option').remove();

            //Somente usuários administradores podem cadastrar administradores
            if (NIVEL_ACESSO == 0) {
                selecao = dados.dados[0].nivel_acesso == 0 ? 'selected' : '';
                $('#nivel-acesso').append('<option value="0" ' + selecao + '>ADMINISTRADOR</option>');
                $("#cliente-usuario").prop('disabled', true);
            }

            selecao = dados.dados[0].nivel_acesso == 1 ? 'selected' : '';
            $('select#nivel-acesso').append('<option value="1" ' + selecao + '>USUARIO</option>');
            selecao = dados.dados[0].nivel_acesso == 2 ? 'selected' : '';
            $('select#nivel-acesso').append('<option value="2" ' + selecao + '>CLIENTE</option>');
            //selecao = dados.dados[0].nivel_acesso == 3 ? 'selected' : '';
            //$('select#nivel-acesso').append('<option value="3" ' + selecao + '>FUNCIONARIO</option>');
            selecao = dados.dados[0].nivel_acesso == 4 ? 'selected' : '';
            $('select#nivel-acesso').append('<option value="4" ' + selecao + '>VISITANTE</option>');

            $("#cliente-usuario").val(dados.dados[0].id_cliente);
            $("#select-empresa").val(dados.dados[0].id_empresa);

            //Habilita seleção se usuário for VISITANTE
            $("#select-empresa").prop('disabled', USUARIO_VISITANTE);

            if (dados.dados[0].nivel_acesso == VISITANTE) {
                selecao = dados.dados[0].nivel_acesso == 0 ? 'selected' : '';
                $("#cliente-usuario").prop('disabled', USUARIO_VISITANTE);

                carregaSelectEmpresas($('#cliente-usuario').val());

            }


        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalUsuario").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('usuario.php', { acao: 'excluir', id_usuario: $('#id_usuario').val() }, function (r, b, xhr) {
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

            $('#modalUsuario').modal('hide')
            location.href = 'index.php';

        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });
});

$(document).on('click', '.excluir', function () {
    $('#id_usuario').val(this.id);
    $("#modalExcluirUsuario").modal({
        backdrop: 'static'
    });
});