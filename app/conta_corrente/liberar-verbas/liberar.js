var cargos = [];

$(document).ready(function () {

    //Matem menu sobre a opção selecionada
    $('#sidebar-menu ul a').removeClass('active');
    $('#sidebar-menu ul .conta-corrente').click();
    $('#sidebar-menu ul .verbas').click();
    $('#sidebar-menu ul .liberar-verbas').addClass('active');

    carregaSelectEmpresa();

});

$('#dataInicio').Monthpicker({
    onSelect: function () {
        $('#dataFim').Monthpicker('option', { minValue: $('#dataInicio').val() });
    }
});
$('#dataFim').Monthpicker({
    onSelect: function () {
        $('#dataInicio').Monthpicker('option', { maxValue: $('#dataFim').val() });
    }
});

/*
 * Definições para a tabela de encargos
 */
var tab_liberacao = $('#tab_liberacao').DataTable({
    paging: true,
    responsive: true,
    destroy: false,
    clear: false,
    searchable: true,
    searching: true,
    info: false,
    ordering: true,
    "order": [0, 1, 'desc'],
    "oLanguage": {
        "sEmptyTable": "Nenhum registro encontrado",
        "sProcessing": "Processando...",
        "sZeroRecords": "Não foram encontrados resultados",
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
        { title: "Ano", className: "text-center" },
        { title: "Mês", className: "text-center" },
        { title: "Empregados<br>do Contrato", className: "text-left" },
        { title: "Décimo<br>Terceiro", className: "text-right decimo_terceiro" },
        { title: "Abono<br>Férias", className: "text-right abono_ferias" },
        { title: "Multa<br>FGTS", className: "text-right multa_fgts" },
        { title: "Impacto<br>sobre 13º", className: "text-right impacto_13" },
        { title: "Impacto<br>férias/Abono", className: "text-right impacto_ferias_abono" }
    ],
    'columnDefs': [{
        'targets': [0, 1, 3, 4, 5, 6, 7],
        'searchable': false,
        'orderable': false
    }]
});

$('#tab_liberacao tbody').on('mouseover', 'tr', function () {
    $('[data-toggle="popover"]').popover({
        'trigger': 'hover',
        'placement': 'left',
        'container': 'body'
    });
    $(this).css('background-color', 'lavender');
}).on('mouseout', 'tr', function () {
    $(this).css('background-color', '#fff');
});

$('#button').click(function () {
    table.row('.selected').remove().draw(false);
});

function carregaLiberacao(id_empresa = null, id_contrato = null, dataInicio = null, dataFim = null, id_empregado = null, observacao_liberacao = null, observacao_retencao = null) {

    carregando('on');

    var dados = {};
    var data, status, xhr;

    var parametros = {
        acao: 'listarLiberacao',
        id_empresa: id_empresa,
        id_contrato: id_contrato,
        dataInicio: isEmpty(dataInicio) ? '' : parseInt(dataInicio.toString().slice(-4) + dataInicio.toString().slice(-6, -4)),
        dataFim: isEmpty(dataFim) ? '' : parseInt(dataFim.toString().slice(-4) + dataFim.toString().slice(-6, -4)),
        id_empregado: id_empregado,
        observacao_liberacao: observacao_liberacao,
        observacao_retencao: observacao_retencao
    };

    tab_liberacao.clear();
    tab_liberacao.draw();

    $.post(APP_HTTP + 'conta_corrente/extrato/extrato.php', parametros, function (data, status, xhr) {
        dados = toJson(data);

        totalDecimoTerceiro = 0;
        totalFeriasAbono = 0;
        totalMultaFGTS = 0;

        totalDecimoTerceiroLiberacao = 0;
        totalFeriasAbonoLiberacao = 0;
        totalMultaFGTSLiberacao = 0;

        totalImpactoEncargos = 0;
        totalImpactoFeriasAbono = 0;

        // Limpa a linha de totalizações
        $('#totalDecimoTerceiro').html('0,00');
        $('#totalFeriasAbono').html('0,00');
        $('#totalMultaFGTS').html('0,00');
        $('#totalImpactoEncargos').html('0,00');
        $('#totalImpactoFeriasAbono').html('0,00');


    }).success(function () {
        if (dados.erro === true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {

            var nomeMeses = ['', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

            //Carrega os encargos do contrato
            if (dados.dados.length > 0) {
                $.each(dados.dados, function (i, item) {
                    identificacaoVerba = item.id_empresa + '|' + item.id_contrato + '|' + item.id_empregado;

                    impacto_encargos_13 = (parseFloat(item.liberacao_impacto_encargos_13) === 0 || item.liberacao_impacto_encargos_13 === null)
                        ? Numeros.formata(item.impacto_encargos_13, 2, ',') : '0,00'

                    if (!document.getElementById("liberacao_casada").checked) {
                        impacto_encargos_13 += '<a href="#" '

                        impacto_encargos_13 += (parseFloat(item.liberacao_impacto_encargos_13) === 0 || item.liberacao_impacto_encargos_13 === null)
                            ? 'class="liberar-valor" '
                            : 'class="cancelar-liberacao" '

                        impacto_encargos_13 += ' mao-link" data-id_lancamento=' + item.id_lancamento
                            + ' data-observacao_liberacao= "' + item.observacao_liberacao + '"'
                            + ' data-id="impacto_encargos_13|' + item.mes + '|' + item.ano + '|' + identificacaoVerba + '"'
                            + ' data-toggle="confirmation"><i class="fa fa-edit fa-s 2x fa-fw" '

                        impacto_encargos_13 += (parseFloat(item.liberacao_impacto_encargos_13) === 0 || item.liberacao_impacto_encargos_13 === null)
                            ? ' title="Liberar Verba"></i></a>'
                            : ' title="Cancelar Liberação">'
                    }

                    impacto_ferias_abono = (parseFloat(item.liberacao_impacto_ferias_abono) === 0 || item.liberacao_impacto_ferias_abono === null)
                        ? Numeros.formata(item.impacto_ferias_abono, 2, ',') : '0,00'

                    if (!document.getElementById("liberacao_casada").checked) {
                        impacto_ferias_abono += '<a href="#" '

                        impacto_ferias_abono += (parseFloat(item.liberacao_impacto_ferias_abono) === 0 || item.liberacao_impacto_ferias_abono === null)
                            ? 'class="liberar-valor" '
                            : 'class="cancelar-liberacao" '

                        impacto_ferias_abono += ' mao-link" data-id_lancamento=' + item.id_lancamento
                            + ' data-observacao_liberacao= "' + item.observacao_liberacao + '"'
                            + ' data-id="impacto_ferias_abono|' + item.mes + '|' + item.ano + '|' + identificacaoVerba + '"'
                            + ' data-toggle="confirmation"><i class="fa fa-edit fa-s 2x fa-fw" '

                        impacto_ferias_abono += (parseFloat(item.liberacao_impacto_ferias_abono) === 0 || item.liberacao_impacto_ferias_abono === null)
                            ? ' title="Liberar Verba"></i></a>'
                            : ' title="Cancelar Liberação">'
                    }

                    tab_liberacao.row.add([
                        `
                        <div class="show-popover" data-toggle="popover" data-html="true" data-trigger="hover" 
                            title="<br>Observações</b>" data-content="<b>Liberação:</b> ${item.observacao_liberacao} 
                            <br /><hr><b>Retenção:</b> ${item.observacao_retencao}">
                            ${item.ano}
                        </div>
                        `,
                        nomeMeses[parseInt(item.mes)],
                        item.nome,

                        (parseFloat(item.liberacao_decimo_terceiro) === 0 || item.liberacao_decimo_terceiro === null)
                            ?
                            Numeros.formata(item.decimo_terceiro, 2, ',')
                            + '<a href="#" class="liberar-valor mao-link" data-id_lancamento=' + item.id_lancamento
                            + ' data-observacao_liberacao= "' + item.observacao_liberacao + '"'
                            + ' data-id="decimo_terceiro|' + item.mes + '|' + item.ano + '|'
                            + identificacaoVerba + '" data-toggle="confirmation"><i class="fa fa-edit fa-s 2x fa-fw" title="Liberar Verba"></i></a>'
                            :
                            '0,00'
                            + '<a href="#" class="cancelar-liberacao mao-link" data-id_lancamento=' + item.id_lancamento
                            + ' data-observacao_liberacao= ""'
                            + ' data-id="decimo_terceiro|' + item.mes + '|' + item.ano + '|'
                            + identificacaoVerba + '" data-toggle="confirmation"><i class="fa fa-edit fa-s 2x fa-fw" title="Cancelar Liberação"></i></a>',

                        (parseFloat(item.liberacao_ferias_abono) === 0 || item.liberacao_ferias_abono === null)
                            ?
                            Numeros.formata(item.ferias_abono, 2, ',')
                            + '<a href="#" class="liberar-valor mao-link" data-id_lancamento=' + item.id_lancamento
                            + ' data-observacao_liberacao= "' + item.observacao_liberacao + '"'
                            + ' data-id="ferias_abono|' + item.mes + '|'
                            + item.ano + '|'
                            + identificacaoVerba + '" data-toggle="confirmation"><i class="fa fa-edit fa-s 2x fa-fw" title="Liberar Verba"></i></a>'
                            :
                            '0,00'
                            + '<a href="#" class="cancelar-liberacao mao-link" data-id_lancamento=' + item.id_lancamento
                            + ' data-observacao_liberacao= ""'
                            + ' data-id="ferias_abono|'
                            + item.mes + '|'
                            + item.ano + '|'
                            + identificacaoVerba + '" data-toggle="confirmation"><i class="fa fa-edit fa-s 2x fa-fw" title="Cancelar Liberação"></i></a>',

                        (parseFloat(item.liberacao_multa_FGTS) === 0 || item.liberacao_multa_FGTS === null)
                            ?
                            Numeros.formata(item.multa_FGTS, 2, ',')
                            + '<a href="#" class="liberar-valor mao-link" data-id_lancamento=' + item.id_lancamento
                            + ' data-observacao_liberacao= "' + item.observacao_liberacao + '"'
                            + ' data-id="multa_FGTS|'
                            + item.mes + '|'
                            + item.ano + '|'
                            + identificacaoVerba + '" data-toggle="confirmation"><i class="fa fa-edit fa-s 2x fa-fw" title="Liberar Verba"></i></a>'
                            :
                            '0,00'
                            + '<a href="#" class="cancelar-liberacao mao-link" data-id_lancamento=' + item.id_lancamento
                            + ' data-observacao_liberacao= ""'
                            + ' data-id="multa_FGTS|'
                            + item.mes + '|'
                            + item.ano + '|'
                            + identificacaoVerba + '" data-toggle="confirmation"><i class="fa fa-edit fa-s 2x fa-fw" title="Cancelar Liberação"></i></a>',

                        impacto_encargos_13,
                        impacto_ferias_abono,
                    ]).draw(false);

                    totalDecimoTerceiro += (parseFloat(item.liberacao_decimo_terceiro) === 0 || item.liberacao_decimo_terceiro === null) ? parseFloat(item.decimo_terceiro) : 0;
                    totalFeriasAbono += (parseFloat(item.liberacao_ferias_abono) === 0 || item.liberacao_ferias_abono === null) ? parseFloat(item.ferias_abono) : 0;
                    totalMultaFGTS += (parseFloat(item.liberacao_multa_FGTS) === 0 || item.liberacao_multa_FGTS === null) ? parseFloat(item.multa_FGTS) : 0;

                    totalDecimoTerceiroLiberacao += parseFloat(item.liberacao_decimo_terceiro);
                    totalFeriasAbonoLiberacao += parseFloat(item.liberacao_ferias_abono);
                    totalMultaFGTSLiberacao += parseFloat(item.liberacao_multa_FGTS);

                    totalImpactoEncargos += (parseFloat(item.liberacao_decimo_terceiro) === 0 || item.liberacao_decimo_terceiro === null) ? parseFloat(item.impacto_encargos_13) : 0;
                    totalImpactoFeriasAbono += (parseFloat(item.liberacao_ferias_abono) === 0 || item.liberacao_ferias_abono === null) ? parseFloat(item.impacto_ferias_abono) : 0;
                });

                /*
                 * Só mostra o botão de liberação pelo total se hover filtro pela empresa, contrato, mês e ano
                 * não filtrar pelo empregado se tiver valor na totalização da coluna
                 */
                acao = '';
                acaoCancelar = '';
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    //$('#select-empregado').val() === "" &&
                    totalDecimoTerceiro > 0) {
                    acao =
                        `<a href="#" class="liberar-valor mao-link" data-id_lancamento="" 
                            data-id="total_decimo_terceiro| 
                            ${dados.dados[0]['mes'].trim()}|
                            ${dados.dados[0]['ano'].trim()}|
                            ${identificacaoVerba}" data-toggle="confirmation">
                                <span class="text-white bg-blue">
                                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #090ed8;" title="Liberar todas as verbas"></i>
                                </span>
                        </a>`;
                    //$('#totalDecimoTerceiro').html(Numeros.formata(totalDecimoTerceiro, 2, ',') + acao);
                }

                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    //$('#select-empregado').val() === "" &&
                    totalDecimoTerceiroLiberacao > 0) {
                    acaoCancelar =
                        `<a href="#" class="cancelar-liberacao mao-link" ata-id_lancamento="" 
                            data-id="total_decimo_terceiro| 
                            ${dados.dados[0]['mes'].trim()}|
                            ${dados.dados[0]['ano'].trim()}|
                            ${identificacaoVerba}" data-toggle="confirmation">
                                <span class="text-white bg-blue">
                                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #d61437;" title="Cancelar todas as liberações"></i>
                                </span>
                        </a>`;
                }
                //acaoCancelar = (cancelaDecimoTerceiro)? acaoCancelar : '';
                $('#totalDecimoTerceiro').html(acaoCancelar + Numeros.formata(totalDecimoTerceiro, 2, ',') + acao);

                /*
                 * Só mostra o botão de liberação pelo total se hover filtro pela empresa, contrato, mês e ano
                 * não filtrar pelo empregado se tiver valor na totalização da coluna
                 */
                acao = '';
                acaoCancelar = '';
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    //$('#select-empregado').val() === "" &&
                    totalFeriasAbono > 0) {
                    acao =
                        `<a href="#" class="liberar-valor mao-link" data-id_lancamento="" 
                            data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                            data-id="total_ferias_abono| 
                            ${dados.dados[0]['mes']}|
                            ${dados.dados[0]['ano']}|
                            ${identificacaoVerba}" data-toggle="confirmation">
                                <span class="text-white bg-blue">
                                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #090ed8;" title="Liberar todas as verbas"></i>
                                </span>
                        </a>`;
                }
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    // $('#select-empregado').val() === "" &&
                    totalFeriasAbonoLiberacao > 0) {
                    acaoCancelar =
                        `<a href="#" class="cancelar-liberacao mao-link" data-id_lancamento="" 
                            data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                            data-id="total_ferias_abono| 
                            ${dados.dados[0]['mes'].trim()}|
                            ${dados.dados[0]['ano'].trim()}|
                            ${identificacaoVerba}" data-toggle="confirmation">
                                <span class="text-white bg-blue">
                                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #d61437;" title="Cancelar todas as liberações"></i>
                                </span>
                        </a>`;
                }
                $('#totalFeriasAbono').html(acaoCancelar + Numeros.formata(totalFeriasAbono, 2, ',') + acao);

                /*
                 * Só mostra o botão de liberação pelo total se hover filtro pela empresa, contrato, mês e ano
                 * não filtrar pelo empregado se tiver valor na totalização da coluna
                 */
                acao = '';
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    //$('#select-empregado').val() === "" &&
                    totalMultaFGTS > 0) {
                    acao =
                        `<a href="#" class="liberar-valor mao-link" data-id_lancamento="" 
                    data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                    data-id="total_multa_fgts| 
                    ${dados.dados[0]['mes']}|
                    ${dados.dados[0]['ano']}|
                    ${identificacaoVerba}" data-toggle="confirmation">
                    <span class="text-white bg-blue">
                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #090ed8;" title="Liberar todas as verbas"></i>
                                </span>
                                </a>`;
                }
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    //$('#select-empregado').val() === "" &&
                    totalMultaFGTSLiberacao > 0) {
                    acaoCancelar =
                        `<a href="#" class="cancelar-liberacao mao-link" data-id_lancamento="" 
                    data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                    data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                    data-id="total_multa_fgts| 
                    ${dados.dados[0]['mes'].trim()}|
                    ${dados.dados[0]['ano'].trim()}|
                    ${identificacaoVerba}" data-toggle="confirmation">
                    <span class="text-white bg-blue">
                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #d61437;" title="Cancelar todas as liberações"></i>
                    </span>
                    </a>`;
                }

                $('#totalMultaFGTS').html(acaoCancelar + Numeros.formata(totalMultaFGTS, 2, ',') + acao);


                /*
                 * Só mostra o botão de liberação pelo total se hover filtro pela empresa, contrato, mês e ano
                 * não filtrar pelo empregado se tiver valor na totalização da coluna
                 * E se não for liberação / retenção casada
                 */
                acao = '';
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    !document.getElementById("liberacao_casada").checked &&
                    totalImpactoEncargos > 0) {
                    acao =
                        `<a href="#" class="liberar-valor mao-link" data-id_lancamento="" 
                            data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                            data-id="total_impacto_encargos_13| 
                            ${dados.dados[0]['mes']}|
                            ${dados.dados[0]['ano']}|
                            ${identificacaoVerba}" data-toggle="confirmation">
                                <span class="text-white bg-blue">
                                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #090ed8;" title="Liberar todas as verbas"></i>
                                </span>
                        </a>`;
                }
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    !document.getElementById("liberacao_casada").checked &&
                    totalImpactoEncargos > 0) {
                    acaoCancelar =
                        `<a href="#" class="cancelar-liberacao mao-link" data-id_lancamento="" 
                            data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                            data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                            data-id="total_impacto_encargos_13| 
                            ${dados.dados[0]['mes'].trim()}|
                            ${dados.dados[0]['ano'].trim()}|
                            ${identificacaoVerba}" data-toggle="confirmation">
                                <span class="text-white bg-blue">
                                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #d61437;" title="Cancelar todas as liberações"></i>
                                </span>
                        </a>`;
                }
                // ////////////////////////////

                if(document.getElementById("liberacao_casada").checked){
                    $('#totalImpactoEncargos').text(Numeros.formata(totalImpactoEncargos, 2, ','));
                } else {
                    $('#totalImpactoEncargos').html(acaoCancelar + Numeros.formata(totalImpactoEncargos, 2, ',') + acao);
                }

                /*
                 * Só mostra o botão de liberação pelo total se hover filtro pela empresa, contrato, mês e ano
                 * não filtrar pelo empregado se tiver valor na totalização da coluna
                 * E se não for liberação / retenção casada
                 */
                acao = '';
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    !document.getElementById("liberacao_casada").checked &&
                    totalImpactoFeriasAbono > 0) {
                    acao =
                        `<a href="#" class="liberar-valor mao-link" data-id_lancamento="" 
                            data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                            data-id="total_impacto_ferias_abono| 
                            ${dados.dados[0]['mes']}|
                            ${dados.dados[0]['ano']}|
                            ${identificacaoVerba}" data-toggle="confirmation">
                                <span class="text-white bg-blue">
                                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #090ed8;" title="Liberar todas as verbas"></i>
                                </span>
                        </a>`;
                }
                if ($('#select-empresa').val() !== "" &&
                    $('#select-contrato').val() !== "" &&
                    $('#dataInicio').val() !== "" &&
                    $('#dataFim').val() !== "" &&
                    !document.getElementById("liberacao_casada").checked &&
                    totalImpactoFeriasAbono > 0) {
                    acaoCancelar =
                        `<a href="#" class="cancelar-liberacao mao-link" data-id_lancamento="" 
                            data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                            data-observacao_liberacao="${dados.dados[0]['observacao_liberacao'].trim()}"
                            data-id="total_impacto_ferias_abono| 
                            ${dados.dados[0]['mes'].trim()}|
                            ${dados.dados[0]['ano'].trim()}|
                            ${identificacaoVerba}" data-toggle="confirmation">
                                <span class="text-white bg-blue">
                                    <i class="fa fa-edit fa-s 2x fa-fw-18" style="color: #d61437;" title="Cancelar todas as liberações"></i>
                                </span>
                        </a>`;
                }

                if(document.getElementById("liberacao_casada").checked){
                    $('#totalImpactoFeriasAbono').text(Numeros.formata(totalImpactoFeriasAbono, 2, ','));
                } else {
                    $('#totalImpactoFeriasAbono').html(acaoCancelar + Numeros.formata(totalImpactoFeriasAbono, 2, ',') + acao);
                }

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
}

$('body').confirmation({
    selector: '[data-toggle=confirmation]',
    title: "Confirma?",
    placement: "left",
    btnOkLabel: "&nbsp;Sim",
    btnCancelLabel: "&nbsp;Não",
    onConfirm: function () {

        // Desativado para usuário visitante
        if (USUARIO_VISITANTE) { return false; }

        idVerba = $(this).attr('data-id').split('|');
        observacao_liberacao = $(this).attr('data-observacao_liberacao');
        thisVerba = $(this);

        mensagem.tipo = msg.SUCCESS;
        mensagem.espera = 2000;

        //Verifica se a observação foi informada
        //if (!$(this).attr('class').match(/cancelar-liberacao/) && $('#observacao_liberacao').val() === "") {
        //    mensagem.tipo = msg.DANGER;
        //    mensagem.titulo = 'Informe uma observação para a liberação';
        //    mensagem.exibe();
        //    return false;
        //}

        if (idVerba) {
            var data, status, xhr;

            var parametros = {
                acao: $(this).attr('class').match(/liberar-valor/) ? 'liberarVerba' : 'cancelarLiberacao',
                verba: idVerba[0],
                // Se liberação pelo total utiliza o período do filtro
                // Se não, utiliza a data do empregado clicado
                mesIni: idVerba[0].match(/total/) ? $('#dataInicio').val().substr(0, 2) : idVerba[1].trim(),
                anoIni: idVerba[0].match(/total/) ? $('#dataInicio').val().substr(3, 4) : idVerba[2].trim(),
                mesFim: idVerba[0].match(/total/) ? $('#dataFim').val().substr(0, 2) : "",
                anoFim: idVerba[0].match(/total/) ? $('#dataFim').val().substr(3, 4) : "",
                id_lancamento: $(this).attr('data-id_lancamento'),
                id_empresa: idVerba[3],
                id_contrato: idVerba[4],
                id_empregado: idVerba[0].match(/total/) && $('#select-empregado').val() === "" ? "" : idVerba[5],
                observacao_liberacao: $('#observacao_liberacao').val(),
                liberacao_casada: document.getElementById("liberacao_casada").checked
            };

            $.post(APP_HTTP + 'conta_corrente/lancamentos/lancamento.php', parametros, function (data, status, xhr) {
                dados = toJson(data);

            }).success(function () {
                if (dados.erro === true) {
                    mensagem.titulo = dados.mensagem;
                    mensagem.exibe();
                    return false;
                } else {
                    if (idVerba[0] === 'decimo_terceiro') {
                        thisVerba[0].parentNode.parentNode.cells[6].textContent = "0,00";
                    }
                    if (idVerba[0] === 'ferias_abono') {
                        thisVerba[0].parentNode.parentNode.cells[7].textContent = "0,00";
                    }
                    //Retira o ícone da liberação e zera o valor liberado
                    thisVerba.hide();
                    thisVerba[0].parentNode.innerText = '0,00';

                    /*
                     * Recarrega toda a table sempre que libera uma verba ou o total da verba
                     * Verificar a performance
                     */
                    $('#botao-filtrar').click();

                    mensagem.titulo = dados.mensagem;
                    mensagem.espera = 2000;
                    mensagem.exibe();

                }
            }).error(function (xhr) {
                mensagem.titulo = dados.mensagem;
                mensagem.exibe();
                return false;
            });
        } else {
            //Zerar lançamento na tabela

        }
    },
    onCancel: function () { }
});

$(document).on('change', '#select-empresa', function () {
    $('#select-contrato').children('option:not(:first)').remove();
    $('#select-empregado').children('option:not(:first)').remove();
    if ($('#select-empresa').val() != "") {
        carregaSelectContrato($('#select-empresa').val());
        carregaEmpregado($('#select-empresa').val());
    }
});

$(document).on('click', '#botao-filtrar', function () {

    if ($('#select-empresa').val() === "" &&
        $('#select-contrato').val() === "" &&
        $('#dataInicio').val() === "" &&
        $('#dataFim').val() === "" &&
        $('#select-empregado').val() === "" &&
        $('#observacao_retencao').val() === "" &&
        $('#observacao_liberacao').val() === "") {

        mensagem.icone = msg.ICO_EXCLAMATION;
        mensagem.tipo = msg.DANGER;
        mensagem.espera = 2000;
        mensagem.titulo = 'Defina algum critério para filtro';
        mensagem.exibe();
        return false;

    }

    carregaLiberacao(
        $('#select-empresa').val() == "" ? null : $('#select-empresa').val(),
        $('#select-contrato').val() == "" ? null : $('#select-contrato').val(),
        $('#dataInicio').val() === "" ? null : parseInt($('#dataInicio').val().replace('/', '')),
        $('#dataFim').val() === "" ? null : parseInt($('#dataFim').val().replace('/', '')),
        $('#select-empregado').val() == 0 ? null : $('#select-empregado').val(),
        $('#observacao_liberacao').val() == 0 ? null : $('#observacao_liberacao').val(),
        $('#observacao_retencao').val() == 0 ? null : $('#observacao_retencao').val(),
    );
});

var carregaSelectEmpresa = function () {

    var data, status, xhr;

    $.post(APP_HTTP + 'cadastro/empresas/empresa.php', { acao: 'listar' }, function (data, status, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                $('.cnpj').html(dados.dados[0].cnpj);

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

var carregaSelectContrato = function (id_empresa) {

    var data, status, xhr;

    var parametros = {
        acao: 'listarContratosEmpresa',
        id_empresa: id_empresa
    };

    $.post(APP_HTTP + 'cadastro/contratos/contrato.php', parametros, function (data, b, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 5000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                //Evita duplicidade na carga da select
                if ($("#select-contrato").html().trim().length < 50) {

                    $.each(dados.dados, function (i, item) {
                        $("#select-contrato").append('<option value="' + item.id_contrato + '">' + item.nu_contrato + '</option>');
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

var carregaEmpregado = function (id_empresa) {

    var data, status, xhr;

    var parametros = {
        acao: 'listarEmpregadosEmpresa',
        id_empresa: id_empresa
    };

    $.post(APP_HTTP + 'cadastro/empregados/empregado.php', parametros, function (data, b, xhr) {
        dados = toJson(data);

    }).success(function () {
        if (dados.erro == true) {
            mensagem.titulo = dados.mensagem;
            mensagem.espera = 2000;
            mensagem.exibe();
            return false;
        } else {
            if (dados.dados.length > 0) {
                //Evita duplicidade na carga da select
                if ($("#select-empregado").html().trim().length < 50) {

                    $.each(dados.dados, function (i, item) {
                        $("#select-empregado").append('<option value="' + item.id_empregado + '">' + item.nome + '</option>');
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