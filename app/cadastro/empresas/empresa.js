$('#cnpj').mask('99.999.999/9999-99');
$('#cep').mask('99.999-999');
$('#telefone').mask('(99) 99999-9999');

// Desativado para usuário visitante
if (USUARIO_VISITANTE) {
    $('.btn-novo-empresa').removeClass('btn-novo-empresa');
}

/**
 * Formata o telefone de acordo com o tipo
 * Celular ou fixo
 */
$("#telefone").focusout(function () {
    var phone, element;
    element = $(this);
    element.unmask();
    phone = element.val().replace(/\D/g, '');
    if (phone.length > 10) {
        element.mask("(99) 99999-9999");
    } else {
        element.mask("(99) 9999-9999?9");
    }
}).trigger('focusout');

$(document).ready(function () {

    var achou_erro = false;

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    $('#sidebar-menu ul .empresas').addClass('active');

    /*
     * Definições tabela de empresas
     */
    var tab_empresas = $('#tab_empresas').DataTable({
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
            { title: "CNPJ", className: "text-center" },
            { title: "RAZÃO", className: "text-left" },
            { title: "ENDEREÇO", className: "text-left" },
            { title: "TELEFONE", className: "text-center" },
            { title: "E-MAIL", className: "text-left" },
        ]
    });

    var carregaEmpresa = function () {

        carregando('on');

        var dados = {};
        var data, status, xhr;

        var parametros = {
            acao: 'listar',
            id_empresa: 0
        }

        // Desativado para usuário visitante
        if (USUARIO_VISITANTE) {
            parametros.id_empresa = EMPRESA_VISITANTE
        }

        $.post('empresa.php', parametros, function (data, status, xhr) {
            dados = toJson(data);

        }).success(function () {
            if (dados.erro == true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 5000;
                mensagem.exibe();
                return false;
            } else {
                if (dados.dados.length > 0) {

                    var acao = '';
                    var dataSet = [];

                    $.each(dados.dados, function (i, item) {
                        tab_empresas.row.add([
                            `<a href="#" class="excluir" id="${item.id_empresa}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                            <a href="#" class="alterar" id="${item.id_empresa}"><i class="fa fa-pencil fa-2x"></i></a>`,
                            item.cnpj,
                            `<a href="#" class="selecionaEmpresa" id="${item.id_empresa}" name="${item.razao}">${item.razao}</a>`,
                            item.endereco,
                            item.telefone,
                            item.email
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
        //}
    };

    carregaEmpresa();
});

$(document).on('click', '.selecionaEmpresa', function () {
    $('#quantidade-empresas').html(this.name);

    /*
     * Salva a storage com os dados da empresa selecionada
     */
    contaVinculada = ls.load()
    contaVinculada.empresa.id = this.id;
    contaVinculada.empresa.nome = this.name;

    //Ao selecionar a empresa, limpa o empregado e o contrato selecionado
    contaVinculada.empregado.id = '';
    contaVinculada.empregado.nome = 'Todos';
    contaVinculada.contrato.id = '';
    contaVinculada.contrato.nu_contrato = '';
    ls.save(contaVinculada);
    location.href = APP_HTTP + 'index.php';
    return;

});

$(document).on('blur', '#cnpj', function () {
    if (!criticaCnpj()) {
        this.focus();
        return false;
    }
});

$(document).on('blur', '#razao', function () {
    if (!validaCampo('#razao')) {
        $(this).focus();
        return false;
    }
});

function criticaCnpj() {

    if (!(valida_cnpj($('#cnpj').val()))) {
        field = {
            id: '#cnpj',
            erro: true,
            message: msg.CNPJ_INVALIDO
        };
        tratarCampoLupa(field);
        return false;
    } else {
        field = {
            id: '#cnpj',
            erro: false,
            message: "",
        }
        tratarCampoLupa(field);
        return true;
    }
    return true;
}

$(document).on('click', '.btn-novo-empresa', function () {
    //Limpa o formulário
    formDadosEmpresa.reset();
    $('#formDadosEmpresa input').each(function () {
        //$('#id_empresa').val('');
        $(this).val('');
    });

    //Abre a modal para cadastro
    $("#modalEmpresa").modal({
        backdrop: 'static'
    });
    $('#cnpj').focus();

});

//Valida campo
function validaCampo(campo) {
    if ($(campo).val() === '') {
        field = { id: campo, erro: true };
        tratarCampoLupa(field);
        $(campo).focus();
        return false;
    } else {
        if (campo === '#email') {
            if (!validaEmail($(campo).val())) {
                field = { id: campo, erro: true };
                tratarCampoLupa(field);
                $(campo).focus();
                return false;
            }
        }
        field = { id: campo, erro: false };
        tratarCampoLupa(field);
        return true;
    }
}

$(document).on('click', '#botao-salvar', function () {

    //Verifica se cnpj é válido
    if (($('#cnpj').val() !== "") && ($('#cnpj').val() !== null)) {
        $('#cnpj').attr('required', 'required');
        if (!(criticaCnpj())) {
            achou_erro = true;
            $('#cnpj').focus();
            return false;
        }
    }

    //Valida os campos da tela
    if (!validaCampo('#razao'))
        return false;
    if (!validaCampo('#endereco'))
        return false;
    if (!validaCampo('#cidade'))
        return false;
    if (!validaCampo('#telefone'))
        return false;
    if (!validaCampo('#email'))
        return false;

    //Capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_empresa').val() === '') ? 'novo' : 'alterar',
        id_empresa: $('#id_empresa').val(),
        cnpj: $('#cnpj').val(),
        razao: $('#razao').val(),
        endereco: $('#endereco').val(),
        cidade: $('#cidade').val(),
        estado: $('#estado').val(),
        cep: $('#cep').val(),
        telefone: $('#telefone').val(),
        email: $('#email').val()
    };
    var dados = {};

    $.post('empresa.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalEmpresa').modal('hide');
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

    $.post('empresa.php', { acao: 'listar', id_empresa: this.id }, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#id_empresa').val(dados.dados[0].id_empresa);
            $('#cnpj').val(dados.dados[0].cnpj);
            $('#razao').val(dados.dados[0].razao);
            $('#endereco').val(dados.dados[0].endereco);
            $('#cidade').val(dados.dados[0].cidade);
            $('#estado').val(dados.dados[0].estado);
            $('#cep').val(dados.dados[0].cep);
            $('#telefone').val(dados.dados[0].telefone);
            $('#email').val(dados.dados[0].email);
        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalEmpresa").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('empresa.php', { acao: 'excluir', id_empresa: $('#id_empresa').val() }, function (r, b, xhr) {
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
            $('#modalEmpresa').modal('hide')
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

    $('#id_empresa').val(this.id);
    $("#modalExcluirEmpresa").modal({
        backdrop: 'static'
    });
});