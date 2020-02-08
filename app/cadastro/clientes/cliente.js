var categorias = [];
$('#cnpj').mask('99.999.999/9999-99');
$('#cep').mask('99.999-999');
$('#telefone').mask('(99) 99999-9999');
$('#telefone_contato').mask('(99) 99999-9999');

/**
 * Formata o telefone de acordo com o tipo
 * Celular ou fixo
 */
$("#telefone , #telefone_contato").focusout(function () {
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
    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    $('#sidebar-menu ul .controle').click();
    $('#sidebar-menu ul .clientes').addClass('active');

    var carregaCliente = function () {

        carregando('on');

        var dados = {};
        var data, status, xhr;

        $.post('cliente.php', { acao: 'listar' }, function (data, status, xhr) {
            dados = toJson(data);

        }).success(function () {
            if (dados.erro === true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 5000;
                mensagem.exibe();
                carregando('off');
                return false;
            } else {
                if (dados.dados.length > 0) {
                    var cabecalhoCliente = `
                         <table class="table table-bordered table-hover display clientes" id="clientes">
                            <thead>
                                <tr class="text-uppercase">
                                    <th data-titulo="Acao" class="text-center">#</th>
                                    <th data-titulo="Cnpj" class="text-center">Cnpj</th>
                                    <th data-titulo="Razão" class="text-center">Razão</th>
                                    <th data-titulo="Endereço" class="text-center">Endereço</th>
                                    <th data-titulo="Telefone" class="text-center">Telefone</th>
                                    <th data-titulo="e-mail" class="text-center">e-mail</th>
                                    <th data-titulo="Status" class="text-center">Status</th>
                                </tr>
                            </thead>
                         </table>`;

                    $("#divCliente").html(cabecalhoCliente);

                    var acao = '';
                    var dataSet = [];

                    $.each(dados.dados, function (i, item) {
                        acao = `
                                <a href="#" class="excluir" id="${item.id_cliente}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                                <a href="#" class="alterar" id="${item.id_cliente}"><i class="fa fa-pencil fa-2x"></i></a>`;

                        dataSet.push([
                            acao,
                            item.cnpj,
                            item.razao,
                            item.endereco,
                            item.telefone,
                            item.email,
                            item.status_cliente
                        ]);
                    });

                    $('#clientes').DataTable({
                        data: dataSet,
                        columns: [
                            { title: "Ação", className: "text-center", "width": "8%"},
                            { title: "Cnpj", className: "text-center" },
                            { title: "Razão", className: "text-center" },
                            { title: "Endereço", className: "text-center" },
                            { title: "Telefone", className: "text-center" },
                            { title: "e-mail", className: "text-center" },
                            { title: "Status", className: "text-center" }
                        ]
                    });
                }
            }
        }).complete(function () {
            carregando('off');
        }).error(function (xhr) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            carregando('off');
            return false;
        });
    };

    var carregaCategorias = function () {

        //So carrega as categorias uma vez
        if (categorias.length === 0) {
            $.post(APP_HTTP + 'cadastro/categorias/categoria.php', { acao: 'listar' }, function (data, status, xhr) {
                dados = toJson(data);
            }).success(function () {
                if (dados.erro == true) {
                    mensagem.titulo = dados.mensagem;
                    console.log(dados.mensagem);
                    mensagem.espera = 2000;
                    mensagem.exibe();
                    return false;
                } else {

                    $.each(dados.dados, function (i, item) {
                        categorias.push([item.id_categoria, item.nome_categoria]);
                    });

                    return categorias;
                }
            }).error(function (xhr) {
                if (xhr.status === 401) {
                }
                return false;
            });
        }
    };

    carregaCliente();
    carregaCategorias();

});

$(document).on('click', '.btn-novo-cliente', function () {
    //Limpa o formulário
    $('#formDadosCliente input').each(function () {
        //$('#id_cliente').val('');
        $(this).val('');
    });

    //Evita duplicidade na carga da select
    if ($("#seleciona-categoria").html().trim().length < 50) {
        $.each(categorias, function (i, item) {
            $('#seleciona-categoria').append('<option value="' + categorias[i][0] + '">' + categorias[i][1] + '</option>');
        });
    }

    //Abre a modal para cadastro
    $("#modalCliente").modal({
        backdrop: 'static'
    });
});

$(document).on('blur', '#cpf', function () {
    if (!(valida_cpf($('#cpf').val())) || $('#cpf').val() === "") {
        field = {
            id: '#cpf',
            erro: true,
            message: 'CPF Inválido'
        };
        return false;
    } else {
        field = {
            id: '#cpf',
            erro: false,
            message: ""
        };
        return true;
    }
});

$(document).on('click', '#botao-salvar', function () {

    //Capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_cliente').val() === '') ? 'novo' : 'alterar',
        id_cliente: $('#id_cliente').val(),
        cnpj: $('#cnpj').val(),
        razao: $('#razao').val(),
        endereco: $('#endereco').val(),
        cidade: $('#cidade').val(),
        estado: $('#estado').val(),
        cep: $('#cep').val(),
        telefone: $('#telefone').val(),
        nome_contato: $('#nome_contato').val(),
        telefone_contato: $('#telefone_contato').val(),
        email: $('#email').val(),
        id_categoria: $('#seleciona-categoria').val(),

        decimo_terceiro: $('#decimo_terceiro').val(),
        ferias_abono: $('#ferias_abono').val(),
        multa_fgts: $('#multa_fgts').val()
    };
    var dados = {};

    $.post('cliente.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalCliente').modal('hide');
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
    var dados = {};

    $.post('cliente.php', { acao: 'listar', id_cliente: this.id }, function (r, b, xhr) {
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
            $('#id_cliente').val(dados.dados[0].id_cliente);
            $('#cnpj').val(dados.dados[0].cnpj);
            $('#razao').val(dados.dados[0].razao);
            $('#endereco').val(dados.dados[0].endereco);
            $('#cidade').val(dados.dados[0].cidade);
            $('#estado').val(dados.dados[0].estado);
            $('#cep').val(dados.dados[0].cep);
            $('#telefone').val(dados.dados[0].telefone);
            $('#nome_contato').val(dados.dados[0].nome_contato);
            $('#telefone_contato').val(dados.dados[0].telefone_contato);
            $('#email').val(dados.dados[0].email);

            $('#decimo_terceiro').val(dados.dados[0].decimo_terceiro);
            $('#ferias_abono').val(dados.dados[0].ferias_abono);
            $('#multa_fgts').val(dados.dados[0].multa_fgts);

            var selecionado = '';
            //Evita duplicidade na carga da select
            if ($("#seleciona-categoria").html().trim().length < 50) {
                $.each(categorias, function (i, item) {
                    selecionado = (dados.dados[0].id_categoria === categorias[i][0]) ? 'selected' : '';
                    $('#seleciona-categoria').append('<option value="' + categorias[i][0] + '"' + selecionado + '>' + categorias[i][1] + '</option>');
                });
            }
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalCliente").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('cliente.php', { acao: 'excluir', id_cliente: $('#id_cliente').val() }, function (r, b, xhr) {
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
            $('#modalCliente').modal('hide');
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
});

$(document).on('click', '.excluir', function () {
    $('#id_cliente').val(this.id);
    $("#modalExcluirCliente").modal({
        backdrop: 'static'
    });
});