var cargos = [];

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .conta-corrente').click();
    $('#sidebar-menu ul .lancamentos').addClass('active');

    var carregaContrato = function () {

        var dados = {};
        var data, status, xhr;

        $.post(APP_HTTP + 'cadastro/contratos/contrato.php', {acao: 'listar'}, function (data, status, xhr) {
            dados = toJson(data);

        }).success(function () {
            if (dados.erro == true) {
                mensagem.titulo = dados.mensagem;
                mensagem.espera = 5000;
                mensagem.exibe();
                return false;
            } else {
                if (dados.dados.length > 0) {
                    var cabecalhoContrato = `
                         <table class="table table-bordered table-hover display contratos" id="contratos">
                            <thead>
                                <tr class="badge-info text-uppercase">
                                    <th data-titulo="Acao" class="text-center">#</th>
                                    <th data-titulo="Contrato" class="text-center">Contrato</th>
                                    <th data-titulo="Objeto" class="text-center">Objeto</th>
                                    <th data-titulo="Valor" class="text-center">Valor</th>
                                </tr>
                            </thead>
                         </table>`;

                    $("#divContrato").html(cabecalhoContrato);

                    var acao = '';
                    var dataSet = [];

                    $.each(dados.dados, function (i, item) {
                        acao = `<a href="#" class="empregadosContrato" id="${item.id_contrato}-${item.id_empresa}"><i class="fa fa-check fa-2x"></i></a>&nbsp`;
                        dataSet.push([
                            acao,
                            item.nu_contrato,
                            item.objeto_contrato,
                            'R$ ' + item.valor.formatMoney()
                        ]);
                    });

                    $('#contratos').DataTable({
                        data: dataSet,
                        columns: [
                            {title: "Ação", className: "text-center"},
                            {title: "Contrato", className: "text-center"},
                            {title: "Objeto", className: "text-left"},
                            {title: "Valor", className: "text-rigth"}
                        ],
                        "scrollCollapse": true,
                        "paging": false,
                        "scrollY": 400,
                        "responsive": true,
                        "searching": false,
                        "info": false,
                        "ordering": false
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

    carregaContrato();

});

/*
 * Definições tabela de empregados
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
    "order": [2, 'asc'],
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
        {title: "CPF", className: "text-center"},
        {title: "NOME", className: "text-center"}
    ]
});

$(document).on('click', '.empregadosContrato', function () {

    tab_empregados.clear();
    tab_empregados.draw();

    var parametros = {
        acao: 'listarEmpregadosContrato',
        id_contrato: this.id.split('-')[0],
        id_empresa: this.id.split('-')[1]
    };

    $.post(APP_HTTP + 'cadastro/contratos/contrato.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            //Carrega os empregados do contrato
            if (dados.dados.length > 0) {
                $.each(dados.dados, function (i, item) {
                    tab_empregados.row.add([
                        `<a href="#" class="detalharLancamentos" id=${item.id_empresa}-${item.id_contrato}-${item.id_empregado} data-toggle="confirmation"><i class="fa fa-check fa-2x text-primary"></i></a>`,
                        item.cpf,
                        item.nome
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
});

//Abre a modal para detalhamento de lançamento de cada empregado do contrato
$(document).on('click', '.detalharLancamentos', function () {
    var parametros = {
        acao: 'detalharLancamentosEmpregados',
        id_empresa: this.id.split('-')[0],
        id_contrato: this.id.split('-')[1],
        id_empregado: this.id.split('-')[2]
    };

    $.post('lancamento.php', parametros, function (r, b, xhr) {
        dados = toJson(r);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            var linhaDetalhe = '';
            var totalRemuneracao = 0;
            var total13 = 0;
            var totalFeriasAbono = 0;
            var totalAdicionalFGTS = 0;
            var totalImpactoFerias13 = 0;
            var totalImpactoFeriasAbono = 0;
            var totalProvisionado = 0;

            //Carrega todos os lançamentos do empregado selecionado
            if (dados.dados.length > 0) {
                nome = '';
                
                $.each(dados.dados, function (i, item) {

                    var total_provisionado = parseFloat(dados.dados[i].decimo_terceiro) +
                            parseFloat(dados.dados[i].ferias_abono) +
                            parseFloat(dados.dados[i].multa_FGTS) +
                            parseFloat(dados.dados[i].impacto_encargos_13) + 
                            parseFloat(dados.dados[i].impacto_ferias_abono);

                    linhaDetalhe +=
                    `
                    <div class="card">
                        <div class="card-header">
                            <table id="atendimentosDia" class="table table-striped table-hover compact atendimentosDia display" width="100%" cellspacing="0">
                                <thead>
                                    <tr class="badge-info">
                                       <th data-titulo="EMPREGADO" class="text-center">EMPREGADO</th>
                                       <th data-titulo="MES" class="text-center">MES</th>
                                       <th data-titulo="ANO" class="text-center">ANO</th>
                                       <th data-titulo="VALOR DA REMUNERAÇÃO" class="text-center">VALOR DA REMUNERAÇÃO</th>
                                    </tr>
                                </thead>
                                <tr>
                                    <td class="text-center"><a class="card-link" data-toggle="collapse" href="#collapse${i}">${dados.dados[i].nome}</a></td>
                                    <td class="text-center">${mesCurto[parseInt(dados.dados[i].mes)]}</td>
                                    <td class="text-center">${dados.dados[i].ano}</td>
                                    <td class="text-center">R$ ${Numeros.formata(parseFloat(dados.dados[i].remuneracao), 2, ',')}</td>
                                </tr>
                            </table>
                        </div>
                        <div id="collapse${i}" class="collapse" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card bg-info text-white mb-3">
                                            <div class="card-body bg-light text-dark">
                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label id="label-dias-trabalhados" for="dias_trabalhados">Dias Trabalhados</label>
                                                        <input type="text" class="form-control text-right" id="dias_trabalhados" value="${dados.dados[i].dias_trabalhados}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label id="label-cargo" for="cargo">Remuneração</label>
                                                        <input type="text" class="form-control text-right money" id="remuneracao" value="${Numeros.formata(parseFloat(dados.dados[i].remuneracao), 2, ',')}"  readonly>
                                                        <div class="invalid-feedback">Campo obrigatório</div>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label id="label-cargo" for="cargo">13º Salário</label>
                                                        <input type="text" class="form-control text-right money" id="decimo_terceiro" value="${Numeros.formata(parseFloat(dados.dados[i].decimo_terceiro), 2, ',')}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label id="label-cargo" for="cargo">Férias + Abono</label>
                                                        <input type="text" class="form-control text-right money" id="ferias_abono" value="${Numeros.formata(parseFloat(dados.dados[i].ferias_abono), 2, ',')}" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-3">
                                                        <label id="label-cargo" for="cargo">Multa FGTS</label>
                                                        <input type="text" class="form-control text-right money" id="multa_FGTS" value="${Numeros.formata(parseFloat(dados.dados[i].multa_FGTS), 2, ',')}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label id="label-impacto" for="cargo">Impacto sobre 13º</label>
                                                        <input type="text" class="form-control text-right money" id="impacto_ferias_13" value="${Numeros.formata(parseFloat(dados.dados[i].impacto_encargos_13), 2, ',')}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label id="label-impacto" for="cargo">Impacto Férias + Abono</label>
                                                        <input type="text" class="form-control text-right money" id="impacto_ferias_abono" value="${Numeros.formata(parseFloat(dados.dados[i].impacto_ferias_abono), 2, ',')}" readonly>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label id="label-total" for="cargo">Total Provisionado</label>
                                                        <input type="text" class="form-control text-right" id="total_provisionado" value="${Numeros.formata(total_provisionado, 2, ',')}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                      
                    `;
                    
                    totalRemuneracao += parseFloat(dados.dados[i]['remuneracao']);
                    total13 += parseFloat(dados.dados[i]['decimo_terceiro']);
                    totalFeriasAbono += parseFloat(dados.dados[i]['ferias_abono']);
                    totalAdicionalFGTS += parseFloat(dados.dados[i]['multa_FGTS']);
                    totalImpactoFerias13 += parseFloat(dados.dados[i]['impacto_encargos_13']);
                    totalImpactoFeriasAbono += parseFloat(dados.dados[i]['impacto_ferias_abono']);
                    totalProvisionado += parseFloat(total_provisionado);
                    
                });

                //Totalização do empregado
                var linhaTotal =
                `
                <div class="card">
                    <div class="card-header">
                        <table id="linhaTotal"    class="table table-bordered table-striped table-hover table-responsive-md" width="100%" cellspacing="0">
                            <thead>
                                <tr class="badge-info">
                                    <th data-titulo="DÉCIMO<br>TERCEIRO" class="text-center">DÉCIMO<br>TERCEIRO</th>
                                   <th data-titulo="FÉRIAS +<br>ABONO" class="text-center">FÉRIAS +<br>ABONO</th>
                                   <th data-titulo="MULTA<br>FGTS" class="text-center">MULTA<br>FGTS</th>
                                   <th data-titulo="IMPACTO<br>SOBRE 13º" class="text-center">IMPACTO<br>SOBRE 13º</th>
                                   <th data-titulo="IMPACTO<br>FÉRIAS+ABONO" class="text-center">IMPACTO<br>FÉRIAS+ABONO</th>
            
                                   <th data-titulo="TOTAL<br>PROVISIONADO" class="text-center">TOTAL<br>PROVISIONADO</th>
                                </tr>
                            </thead>
                            <tr>
                                <td class="text-center">R$ ${Numeros.formata(parseFloat(total13), 2, ',')}</td>
                                <td class="text-center">R$ ${Numeros.formata(parseFloat(totalFeriasAbono), 2, ',')}</td>
                                <td class="text-center">R$ ${Numeros.formata(parseFloat(totalAdicionalFGTS), 2, ',')}</td>
                                <td class="text-center">R$ ${Numeros.formata(parseFloat(totalImpactoFerias13), 2, ',')}</td>
            
                                <td class="text-center">R$ ${Numeros.formata(parseFloat(totalImpactoFeriasAbono), 2, ',')}</a></td>
            
                                <td class="text-center">R$ ${Numeros.formata(parseFloat(totalProvisionado), 2, ',')}</td>
                            </tr>
                        </table>
                </div>                      
                `;

                $(".accordion").html(linhaDetalhe);
                $(".total").html(linhaTotal);

                //Mostra o modal com os dados para a alteração
                $("#modalDetalheLancamentoEmpregado").modal({
                    backdrop: 'static'
                });
            }
        }
    }).error(function (xhr) {
        mensagem.titulo = dados.mensagem;
        mensagem.espera = 5000;
        mensagem.exibe();
        return false;
    });
});

$(document).on('click', '.detalhar', function () {
    var dados = {};

    $.post('lancamento.php', {acao: 'listar', id_lancamento: this.id}, function (r, b, xhr) {
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
            $('#id_lancamento').val(dados.dados[0].id_lancamento);
            $('#cpf').val(dados.dados[0].cpf);
            $('#nome').val(dados.dados[0].nome);
            $('#turno').val(dados.dados[0].turno);
            $('#remuneracao').val(dados.dados[0].remuneracao);
            $('#dt_admissao').val(dados.dados[0].dt_admissao);
            $('#dt_desligamento').val(dados.dados[0].dt_desligamento);


            $('#seleciona-cargo').remove();
            $('#label-cargo').append('<select id="seleciona-cargo" class="form-control"></select>');
            var selecionado = '';
            $.each(cargos, function (i, item) {
                if (dados.dados[0].id_cargo == cargos[i][0]) {
                    selecionado = 'selected';
                }
                $('#seleciona-cargo').append('<option value="' + cargos[i][0] + '"' + selecionado + '>' + cargos[i][1] + '</option>');
            });

        }
    }).error(function (xhr) {
        if (xhr.status == 401) {
        }
        return false;
    });

    //Mostra o modal com os dados para a alteração
    $("#modalLancamento").modal({
        backdrop: 'static'
    });
});