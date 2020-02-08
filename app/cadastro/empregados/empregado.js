var cargos = [];
var empresas = [];
$('#cpf').mask('999.999.999-99');

// Desativado para usuário visitante
if (USUARIO_VISITANTE) {
    $('.btn-novo-empregado').removeClass('btn-novo-empregado');
}

/*
 * Definições tabela de cargos
 */
var tab_empregados = $('#tab_empregados').DataTable({
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
        { title: "AÇÃO", className: "text-center", width: "5%"  },
        { title: "CPF", className: "text-center", width: "12%"  },
        { title: "NOME", className: "text-left", width: "25%"  },
        { title: "CARGO", className: "text-center", width: "15%"  },
        { title: "TURNO", className: "text-center", width: "5%"  },
        { title: "OBSERVAÇÃO", className: "text-left", width: "30%" },
        { title: "OBS", className: "text-left", "visible": false }
    ]
});

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .cadastros').click();
    $('#sidebar-menu ul .empregados').addClass('active');

    var carregaEmpregado = function () {

        carregando('on');

        var dados = {};
        var data, status, xhr;

        empresa = ls.load().empresa;
        empregado = ls.load().empregado;

        var parametros = {
            acao: 'listar',
            id_empresa: ls.load().empresa.id,
            id_empregado: ls.load().empregado.id
        };

        $.post('empregado.php', parametros, function (data, status, xhr) {
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
                        tab_empregados.row.add([
                            `<a href="#" class="excluir" id="${item.id_empregado}"><i class="fa fa-trash fa-2x"></i></a>&nbsp
                            <a href="#" class="alterar" id="${item.id_empregado}"><i class="fa fa-pencil fa-2x"></i></a>`,
                            !(formata_cpf_cnpj(item.cpf)) ? 'CFP inválido' : formata_cpf_cnpj(item.cpf),
                            `<a href="#" class="selecionaEmpregado" data-id="${item.id_empresa}|${item.razao}" id="${item.id_empregado}" name="${item.nome}">${item.nome}</a>`,
                            item.nome_cargo,
                            item.turno,
                            item.observacao.substr(0, 50) + '...',
                            item.observacao
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

    var carregaCargos = function () {

        //So carrega os cargos uma vez
        if (cargos.length === 0) {
            $.post(APP_HTTP + 'cadastro/cargos/cargo.php', { acao: 'listar' }, function (r, b, xhr) {
                dados = toJson(r);
            }).success(function () {
                if (dados.erro == true) {
                    mensagem.titulo = dados.mensagem;
                    console.log(dados.mensagem);
                    mensagem.espera = 5000;
                    mensagem.exibe();
                    return false;
                } else {

                    $.each(dados.dados, function (i, item) {
                        cargos.push([item.id_cargo, item.nome_cargo + ' - ' + item.turno]);
                    });

                    return cargos;
                }
            }).error(function (xhr) {
                if (xhr.status === 401) {
                }
                return false;
            });
        }
    };

    var carregaEmpresas = function () {

        //So carrega as empresas uma vez
        if (empresas.length === 0) {
            $.post(APP_HTTP + 'cadastro/empresas/empresa.php', { acao: 'listar' }, function (r, b, xhr) {
                dados = toJson(r);
            }).success(function () {
                if (dados.erro === true) {
                    mensagem.titulo = dados.mensagem;
                    console.log(dados.mensagem);
                    mensagem.espera = 5000;
                    mensagem.exibe();
                    return false;
                } else {

                    $.each(dados.dados, function (i, item) {
                        empresas.push([item.id_empresa, item.razao]);
                    });

                    return empresas;
                }
            }).error(function (xhr) {
                if (xhr.status === 401) {
                }
                return false;
            });
        }
    };

    carregaEmpregado();
    carregaCargos();
    carregaEmpresas();

});

$(document).on('blur', '#cpf', function () {
    if (!(valida_cpf($('#cpf').val())) || $('#cpf').val() === "") {
        field = {
            id: '#cpf',
            erro: true,
            message: 'CPF Inválido'
        };
        tratarCampo(field);
        $(this).focus();
        return false;
    } else {
        field = {
            id: '#cpf',
            erro: false,
            message: ""
        };
        tratarCampo(field);
        return true;
    }
});

$(document).on('blur', '#nome', function () {
    if ($('#nome').val() === "") {
        field = {
            id: '#nome',
            erro: true,
            message: 'Preenchimento obrigatório'
        };
        tratarCampo(field);
        $(this).focus();
        return false;
    } else {
        field = {
            id: '#nome',
            erro: false,
            message: ""
        };
        tratarCampo(field);
        return true;
    }
});

$(document).on('blur', '#select-cargo', function () {
    if ($('#nome').val() === "") {
        field = {
            id: '#select-cargo',
            erro: true,
            message: 'Selecione um cargo'
        };
        tratarCampo(field);
        $(this).focus();
        return false;
    } else {
        field = {
            id: '#select_cargo',
            erro: false,
            message: ""
        };
        tratarCampo(field);
        return true;
    }
});

$(document).on('click', '.selecionaEmpregado', function () {
    /*
     * Ao selecionar um empregado, automaticamente seleciona a empresa deste empregado
     */
    $('#quantidade-empresas').html($(this).attr('data-id').split('|')[1].trim().substr(0, 30));
    $('#quantidade-empresas').css({ "font-size": "20px" });

    $('#quantidade-empregados').html(this.name.trim().substr(0, 30));
    $('#quantidade-empregados').css({ "font-size": "20px" });

    /*
     * Salva a storage com os dados da empresa selecionada
     */
    contaVinculada = ls.load()
    contaVinculada.empregado.id = this.id;
    contaVinculada.empregado.nome = this.name;
    contaVinculada.empresa.id = $(this).attr('data-id').split('|')[0];
    contaVinculada.empresa.nome = $(this).attr('data-id').split('|')[1];
    contaVinculada.contrato.id = '';
    contaVinculada.contrato.nu_contrato = '';
    ls.save(contaVinculada);
    location.href = APP_HTTP + 'index.php';
    return;

});

$(document).on('click', '.btn-novo-empregado', function () {
    //Limpa o formulário
    $('#formDadosEmpregado input').each(function () {
        $(this).val('');
    });

    $("#seleciona-cargo").attr('checked', false);
    $("#seleciona-empresa").attr('checked', false);

    //Evita duplicidade na carga da select
    if ($("#seleciona-cargo").html().trim().length < 50) {
        $.each(cargos, function (i, item) {
            $('#seleciona-cargo').append('<option value="' + cargos[i][0] + '">' + cargos[i][1] + '</option>');
        });
    }

    //Evita duplicidade na carga da select
    if ($("#seleciona-empresa").html().trim().length < 50) {
        $.each(empresas, function (i, item) {
            $('#seleciona-empresa').append('<option value="' + empresas[i][0] + '">' + empresas[i][1] + '</option>');
        });
    }

    //Abre a modal para cadastro
    $("#modalEmpregado").modal({
        backdrop: 'static'
    });

    $('#cpf').focus();
});

$('#formDadosEmpregado').validator().on('submit', function (e) {
    mensagem.icone = msg.ICO_EXCLAMATION;
    mensagem.tipo = msg.DANGER;
    mensagem.espera = 2000;

    //Verifica se o cargo foi selecionado
    if ($('#select-cargo option:selected').val() === "") {
        mensagem.titulo = 'Selecione um cargo';
        mensagem.exibe();
        return false;
    }
})

$(document).on('click', '#botao-salvar', function () {

    //Verifica se o cargo foi selecionado
    if ($('#cpf').val() === "" || $('#nome').val() === "") {
        return false;
    }

    //capturando todos os campos input do formulario
    var parametros = {
        acao: ($('#id_empregado').val() === '') ? 'novo' : 'alterar',
        id_empregado: $('#id_empregado').val(),
        id_empresa: $('#seleciona-empresa').val(),
        cpf: $('#cpf').val().replace(/[.]/g, '').replace('-', ''),
        nome: $('#nome').val(),
        id_cargo: $("#seleciona-cargo option:selected").val(),
        turno: $('#seleciona-turno').val(),
        dt_admissao: $('#dt_admissao').val(),
        dt_desligamento: $('#dt_desligamento').val(),
        observacao: $('#observacao').val()
    };
    var dados = {};

    $.post('empregado.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            $('#modalEmpregado').modal('hide');
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

    $.post('empregado.php', { acao: 'listar', id_empregado: this.id }, function (r, b, xhr) {
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
            $('#id_empregado').val(dados.dados[0].id_empregado);
            $('#cpf').val(dados.dados[0].cpf);
            $('#nome').val(dados.dados[0].nome);
            $('#turno').val(dados.dados[0].turno);
            //$('#remuneracao').val(dados.dados[0].remuneracao);
            $('#dt_admissao').val(dados.dados[0].dt_admissao);
            $('#dt_desligamento').val(dados.dados[0].dt_desligamento);
            $('#observacao').val(dados.dados[0].observacao);

            $('#seleciona-cargo').remove();
            $('#label-cargo').append('<select id="seleciona-cargo" class="form-control"></select>');
            var selecionado = '';
            $.each(cargos, function (i, item) {
                selecionado = (dados.dados[0].id_cargo === cargos[i][0]) ? 'selected' : '';
                $('#seleciona-cargo').append('<option value="' + cargos[i][0] + '"' + selecionado + '>' + cargos[i][1] + '</option>');
            });

            var selecionado = '';
            //Evita duplicidade na carga da select
            if ($("#seleciona-empresa").html().trim().length < 50) {
                $.each(empresas, function (i, item) {
                    selecionado = (dados.dados[0].id_empresa === empresas[i][0]) ? 'selected' : '';
                    $('#seleciona-empresa').append('<option value="' + empresas[i][0] + '"' + selecionado + '>' + empresas[i][1] + '</option>');
                });
            }

        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalEmpregado").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-excluir', function () {
    var dados = {};

    $.post('empregado.php', { acao: 'excluir', id_empregado: $('#id_empregado').val() }, function (r, b, xhr) {
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
            $('#modalEmpregado').modal('hide');
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

    $('#id_empregado').val(this.id);
    $("#modalExcluirEmpregado").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '.btn-captura-empregado', function () {
    //Abre a modal para cadastro
    $("#modalCapturaEmpregados").modal({
        backdrop: 'static'
    });
});

$(document).on('click', '#botao-captura', function () {
    var dados = {};

    //;;O arquivo está fixo
    //etirar e solicitar a seleção do arquivo para captura

    var arquivo = $('#arquivoCaptura').val().replace("C:\\fakepath\\", "");
    arquivo = SITE + 'ScriptBanco/BASE PARA DESENVOLVIMENTO DE SISTEMA.xlsx'

    $.post('empregado.php', { acao: 'captura', arquivo_captura: arquivo }, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            mensagem.titulo = 'Registros capturados com sucesso';
            mensagem.espera = 5000;
            mensagem.exibe();
            $('#modalEmpregado').modal('hide');
            location.href = 'index.php';
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
});