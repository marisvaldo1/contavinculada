categorias = [];

$('#form-menu ul').removeClass();
$('#form-menu ul .inicial').addClass('active');

$(document).ready(function () {

    /*
     * Verifica se algum filtro definido na storage
     */
    contaVinculada = ls.load();
    if ($.isEmptyObject(contaVinculada)) {
        iniciaVariaveis();
        contaVinculada = ls.load();
    }

    if (contaVinculada.empresa.id !== '') {
        $('#quantidade-empresas').html(contaVinculada.empresa.nome.trim().substr(0, 30));
        $('#quantidade-empresas').css({ "font-size": "20px" });
    } else {
        //Salva na storage somente as empresas que o visitante tem acesso
        if (USUARIO_VISITANTE) {
            contaVinculada = ls.load()
            if(contaVinculada.empregado.id === ''){
                contaVinculada.empresa.id = EMPRESA_VISITANTE;
                contaVinculada.empresa.nome = NOME_EMPRESA_VISITANTE;        

            };
            ls.save(contaVinculada);
            
            $('#quantidade-empresas').html(contaVinculada.empresa.nome.trim().substr(0, 30));
            $('#quantidade-empresas').css({ "font-size": "20px" });
    
        }
    }

    if (contaVinculada.empregado.id !== '') {
        $('#quantidade-empregados').html(contaVinculada.empregado.nome.trim().substr(0, 30));
        $('#quantidade-empregados').css({ "font-size": "20px" });
    }

    if (contaVinculada.contrato.id !== '') {
        $('#quantidade-contratos').html(contaVinculada.contrato.nu_contrato.trim().substr(0, 30));
        $('#quantidade-contratos').css({ "font-size": "20px" });
    } else {
        carregaTotais('contratos');
    }

    carregaTotais('saldoContas');

    //Monta gráficos com valores para administradores
    if (NIVEL_ACESSO == 0) {
        carregaTotais('empregados');
        carregaTotais('clientes');
        carregaTotais('quantidadeEmpregadosCategoria');
        carregaTotais('listarEmpregadosCategoria');
        carregaTotais('listarContratosCategoria');
        carregaRecebimentosPrevistosRealizados();
    } else {
        //Monta gráficos com valores para usuários
        if (contaVinculada.empresa.id === '') {
            carregaTotais('empresas');
        }

        if (contaVinculada.empregado.id === '') {
            carregaTotais('empregados');
        }

        carregaLiberacoes();
        carregaSaldos();
        carregaRetencoesLiberacoes();
    }

});

var carregaTotais = function (tipo) {

    var dados = {};

    var parametros = {
        acao: tipo,
        id_empresa: ls.load().empresa.id,
        id_empregado: ls.load().empregado.id
    };

    $.post('inicio.php', parametros, function (r, b, xhr) {
        dados = toJson(r);
    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            //mensagem.exibe();
            return false;
        } else {
            if (tipo === 'listarEmpregadosCategoria') {
                var ctx3 = document.getElementById("doughnutChart").getContext('2d');
                var valores = [];
                var categorias = [];

                $.each(dados.dados, function () {
                    valores.push(this.qt_empregados);
                    categorias.push(this.nome_categoria);
                });

                var doughnutChart = new Chart(ctx3, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: valores,
                            backgroundColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255,99,132,1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            label: 'Empregados'
                        }],
                        labels: categorias
                    },
                    options: {
                        responsive: true
                    }
                });
            } else if (tipo === 'listarContratosCategoria') {
                var ctx2 = document.getElementById("pieChart").getContext('2d');

                var valores = [];
                var categorias = [];

                $.each(dados.dados, function () {
                    valores.push(this.qt_contratos);
                    categorias.push(this.nome_categoria);
                });

                var pieChart = new Chart(ctx2, {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: valores,
                            backgroundColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255,99,132,1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            label: 'Contratos'
                        }],
                        labels: categorias
                    },
                    options: {
                        responsive: true
                    }
                });
            } else {
                if (tipo === 'saldoContas') {
                    $('#quantidade-' + tipo).html('R$ ' + dados.quantidade.formatMoney());
                    $('#quantidade-retencoes').html('R$ ' + dados.quantidadeRetencoes.formatMoney());
                    $('#quantidade-liberacoes').html('R$ ' + dados.quantidadeLiberacoes.formatMoney());
                } else {
                    $('#quantidade-' + tipo).html(dados.quantidade);
                }
                return true;
            }
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
};

var carregaRecebimentosPrevistosRealizados = function () {
    var dados = {};

    var parametros = {
        acao: 'recebimentosPrevistosRealizados',
        id_empresa: ls.load().empresa.id,
        id_empregado: ls.load().empregado.id
    };

    $.post(APP_HTTP + 'controle_contrato/pagamento/pagamento.php', parametros, function (data, status, xhr) {
        dados = toJson(data);
    }).success(function () {
        if (dados.erro === true) {
            modal.fecha();
            mensagem.titulo = dados.mensagem;
            console.log(dados.mensagem);
            mensagem.espera = 2000;
            //mensagem.exibe();
            return false;
        } else {
            var ctx1 = document.getElementById("lineChart").getContext('2d');

            var nomeMeses = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            var meses = [];
            var previstos = [];
            var realizados = [];

            $.each(dados.dados, function () {
                meses.push(nomeMeses[this.mes]);
                previstos.push(this.valor_parcela.formatMoney());
                realizados.push(this.valor_pagamento === null ? 0 : this.valor_pagamento);
            });

            var lineChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Realizados',
                        backgroundColor: '#3EB9DC',
                        data: realizados
                    }, {
                        label: 'Previstos',
                        backgroundColor: '#EBEFF3',
                        data: previstos
                    }]

                },
                options: {
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return 'R$ ' + Numeros.formata((tooltipItem.yLabel == 0 || isNaN(tooltipItem.yLabel)) ? 0 : tooltipItem.yLabel, 2, ',');
                            }
                        }
                    },
                    multiTooltipTemplate: "<%= 'R$ ' + value %>",
                    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' %' %>",
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            });

        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
};

var carregaRetencoesLiberacoes = function () {

    var dados = {};

    var parametros = {
        acao: 'retencoesLiberacoes',
        id_empresa: ls.load().empresa.id,
        id_empregado: ls.load().empregado.id
    };

    $.post(APP_HTTP + 'conta_corrente/lancamentos/lancamento.php', parametros, function (data, status, xhr) {
        dados = toJson(data);
    }).success(function () {
        if (dados.erro === true) {
            //modal.fecha();
            //mensagem.titulo = dados.mensagem;
            //console.log(dados.mensagem);
            //mensagem.espera = 2000;
            //mensagem.exibe();
            return false;
        } else {
            var ctx1 = document.getElementById("lineChart").getContext('2d');

            var nomeMes = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            var meses = [];
            var retencoes = [];
            var liberacoes = [];

            $.each(dados.dados, function () {
                meses.push(nomeMes[parseInt(this.mes)] + ' / ' + this.ano);
                retencoes.push(this.retencoes === null ? 0 : this.retencoes);
                liberacoes.push(this.liberacoes === null ? 0 : this.liberacoes);
            });

            var lineChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Retenções',
                        backgroundColor: '#3EB9DC',
                        data: retencoes
                    }, {
                        label: 'liberacoes',
                        backgroundColor: 'rgba(255,99,132,1)',
                        data: liberacoes
                    }]
                },
                options: {
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return 'R$ ' + Numeros.formata((tooltipItem.yLabel == 0 || isNaN(tooltipItem.yLabel)) ? 0 : tooltipItem.yLabel, 2, ',');
                            }
                        }
                    },
                    multiTooltipTemplate: "<%= 'R$ ' + value %>",
                    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' %' %>",
                    responsive: true,
                    scales: {
                        xAxes: [{
                            stacked: true
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    }
                }
            });

        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
};

var carregaLiberacoes = function () {

    var dados = {};

    var parametros = {
        acao: 'liberacoes',
        id_empresa: ls.load().empresa.id,
        id_empregado: ls.load().empregado.id
    };

    $.post(APP_HTTP + 'conta_corrente/lancamentos/lancamento.php', parametros, function (data, status, xhr) {
        dados = toJson(data);
    }).success(function () {
        if (dados.erro === true) {
            //modal.fecha();
            //mensagem.titulo = dados.mensagem;
            //console.log(dados.mensagem);
            //mensagem.espera = 2000;
            //mensagem.exibe();
            return false;
        } else {
            var ctx3 = document.getElementById("doughnutChart").getContext('2d');
            var valores = [];

            var categorias = ['13º', 'Impacto sobre 13º', 'Férias', 'Impacto sobre Férias', 'Multa FGTS'];

            $.each(dados.dados, function () {
                valores.push(
                    parseFloat(this.decimo_terceiro),
                    parseFloat(this.impacto_encargos_13),
                    parseFloat(this.ferias_abono),
                    parseFloat(this.impacto_ferias_abono),
                    parseFloat(this.multa_FGTS)
                );
            });

            var doughnutChart = new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: valores,
                        backgroundColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: 'Empregados'
                    }],
                    labels: categorias
                },
                options: {
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return 'R$ ' + Numeros.formata(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index], 2, ',');
                            }
                        }
                    },
                    responsive: true
                }
            });
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
};

var carregaSaldos = function () {

    var dados = {};

    var parametros = {
        acao: 'saldos',
        id_empresa: ls.load().empresa.id,
        id_empregado: ls.load().empregado.id,
        id_contrato: ls.load().contrato.id
    };

    $.post(APP_HTTP + 'conta_corrente/lancamentos/lancamento.php', parametros, function (data, status, xhr) {
        dados = toJson(data);
    }).success(function () {
        if (dados.erro === true) {
            //mensagem.titulo = dados.mensagem;
            //console.log(dados.mensagem);
            //mensagem.espera = 2000;
            //mensagem.exibe();
            return false;
        } else {

            var valores = [];
            var verbas = ['13º', 'Impacto sobre 13º', 'Férias', 'Impacto sobre Férias', 'Multa FGTS'];

            $.each(dados.dados, function () {
                valores.push(this.decimo_terceiro_impacto,
                    this.impacto_encargos_13,
                    this.ferias_abono_impacto,
                    this.impacto_ferias_abono,
                    this.multa_FGTS);
            });

            var ctx2 = document.getElementById("pieChart").getContext('2d');

            var pieChart = new Chart(ctx2, {
                type: 'pie',
                data: {
                    datasets: [{
                        data: valores,
                        backgroundColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255,99,132,1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        label: 'Verbas'
                    }],
                    labels: verbas
                },
                options: {
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                return 'R$ ' + Numeros.formata(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index], 2, ',');
                            }
                        }
                    },
                    responsive: true
                }
            });
        }
    }).error(function (xhr) {
        if (xhr.status === 401) {
        }
        return false;
    });
};

function carregaEmpregados() {

    var dados = {};
    var data, status, xhr;

    var parametros = {
        acao: 'listarEmpregadosCategoria'
    };

    $.post(APP_HTTP + 'cadastro/empregados/empregado.php', parametros, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            //mensagem.titulo = dados.mensagem;
            //mensagem.espera = 2000;
            //mensagem.exibe();
            return false;
        } else {

            //Carrega as categorias
            if (dados.dados.length > 0) {
                $.each(dados.dados, function (i, item) {
                    categorias.push({
                        "nomeCategoria": item.nome_categoria,
                        "qt_empregados": item.qt_empregados
                    });
                });
            }
        }
    }).error(function (xhr) {
        //mensagem.titulo = dados.mensagem;
        //mensagem.espera = 2000;
        //mensagem.exibe();
        return false;
    });
}