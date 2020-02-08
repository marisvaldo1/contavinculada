<?php

include '../../../inicia.php';

const MIN_LINHAS = 5;
const MAX_LINHAS = 20;

use modelo\Extrato;
use componentes\Relatorios;
use GuzzleHttp\Exception\BadResponseException;

try {

    $parametros = json_decode($_REQUEST['parametros'], true);
    $retorno = Extrato::buscaExtrato($parametros);

    $pageBlank = false;
    $arrayMinExiste = false;
    $arrayMin = [];
    $arrayMax = [];

    $extrato = new Relatorios();
    $html = $extrato->montaInicioHTML();
    $cabecalho = $extrato->montaCabecalhoExtrato($parametros);
    $corpo = '';
    $htmlFim = $extrato->montaFimHTML();
    $nomeMes = ['Todos', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

    $countItem = 0;

    if (!$retorno->erro) {

        $total_r_linha = 0;
        $total_l_linha = 0;
        $totalDecimoTerceiro = 0;
        $totalFeriasAbono = 0;
        $totalMultaFGTS = 0;
        $totalImpactoEncargos = 0;
        $totalImpactoFeriasAbono = 0;
        $totalRetidoLiberado = 0;

        for ($i = 0; $i < count($retorno->dados); $i++) {

            if ($chave != 'erro') {

                $corpo .= '<tr style="text-align: right">';
                $corpo .= ' <td style="text-align: center">' . $retorno->dados[$i]->ano . '</td>';
                $corpo .= ' <td style="text-align: center">' . $nomeMes[number_format($retorno->dados[$i]->mes)] . '</td>';
                $corpo .= ' <td style="text-align: left">' . $retorno->dados[$i]->nome . '</td>';
                $corpo .= ' <td style="text-align: center">Retenção</td>';

                if ($parametros['decimo_terceiro']) {
                    $corpo .= ' <td>' . moeda($retorno->dados[$i]->r_decimo_terceiro) . '</td>';
                    $total_r_linha += $retorno->dados[$i]->r_decimo_terceiro;
                }

                if ($parametros['ferias_abono']) {
                    $corpo .= ' <td>' . moeda($retorno->dados[$i]->r_ferias_abono) . '</td>';
                    $total_r_linha += $retorno->dados[$i]->r_ferias_abono;
                }

                if ($parametros['fgts']) {
                    $corpo .= ' <td>' . moeda($retorno->dados[$i]->r_multa_FGTS) . '</td>';
                    $total_r_linha = $retorno->dados[$i]->r_multa_FGTS;
                }

                if ($parametros['impacto_13']) {
                    $corpo .= ' <td>' . moeda($retorno->dados[$i]->r_impacto_encargos_13) . '</td>';
                    $total_r_linha = $retorno->dados[$i]->r_impacto_encargos_13;
                }

                if ($parametros['impacto_ferias_abono']) {
                    $corpo .= ' <td>' . moeda($retorno->dados[$i]->r_impacto_ferias_abono) . '</td>';
                    $total_r_linha = $retorno->dados[$i]->r_impacto_ferias_abono;
                }

                $corpo .= ' <td>' . moeda($total_r_linha) . '</td>';
                $corpo .= ' <td>' . $retorno->dados[$i]->observacao_retencao . '</td>';
                $corpo .= '<tr>';

                /*
                 * Verifica se existe algum lançamento de liberação para criar a linha
                 */
                //Total de liberações da linha
                $total_l_linha = $retorno->dados[$i]->l_decimo_terceiro +
                    $retorno->dados[$i]->l_ferias_abono +
                    $retorno->dados[$i]->l_multa_FGTS +
                    $retorno->dados[$i]->l_impacto_encargos_13 +
                    $retorno->dados[$i]->l_impacto_ferias_abono;

                if ($total_l_linha > 0) {
                    $corpo .= '<tr style="text-align: right;color: red;">';
                    $corpo .= ' <td style="text-align: center">' . $retorno->dados[$i]->ano . '</td>';
                    $corpo .= ' <td style="text-align: center">' . $nomeMes[number_format($retorno->dados[$i]->mes)] . '</td>';
                    $corpo .= ' <td style="text-align: left">' . $retorno->dados[$i]->nome . '</td>';
                    $corpo .= ' <td style="text-align: center">Liberação</td>';

                    if ($parametros['decimo_terceiro']) {
                        $corpo .= $retorno->dados[$i]->l_decimo_terceiro > 0 ? ' <td>' . moeda($retorno->dados[$i]->l_decimo_terceiro * -1) . ' </td>' : '<td></td>';
                    }

                    if ($parametros['ferias_abono']) {
                        $corpo .= $retorno->dados[$i]->l_ferias_abono > 0 ? ' <td>' . moeda($retorno->dados[$i]->l_ferias_abono * -1) . ' </td>' : '<td></td>';
                    }

                    if ($parametros['fgts']) {
                        $corpo .= $retorno->dados[$i]->l_multa_FGTS > 0 ? ' <td>' . moeda($retorno->dados[$i]->l_multa_FGTS * -1) . ' </td>' : '<td></td>';
                    }

                    if ($parametros['impacto_13']) {
                        $corpo .= $retorno->dados[$i]->l_impacto_encargos_13 > 0 ? ' <td>' . moeda($retorno->dados[$i]->l_impacto_encargos_13 * -1) . ' </td>' : '<td></td>';
                    }

                    if ($parametros['impacto_ferias_abono']) {
                        $corpo .= $retorno->dados[$i]->l_impacto_ferias_abono > 0 ? ' <td>' . moeda($retorno->dados[$i]->l_impacto_ferias_abono * -1) . ' </td>' : '<td></td>';
                    }

                    $corpo .= ' <td>' . moeda($total_l_linha * -1) . '</td>';
                    $corpo .= ' <td>' . $retorno->dados[$i]->observacao_liberacao . '</td>';
                }

                $totalDecimoTerceiro += $retorno->dados[$i]->r_decimo_terceiro - $retorno->dados[$i]->l_decimo_terceiro;
                $totalFeriasAbono += $retorno->dados[$i]->r_ferias_abono - $retorno->dados[$i]->l_ferias_abono;
                $totalMultaFGTS += $retorno->dados[$i]->r_multa_FGTS - $retorno->dados[$i]->l_multa_FGTS;
                $totalImpactoEncargos += $retorno->dados[$i]->r_impacto_encargos_13 - $retorno->dados[$i]->l_impacto_encargos_13;
                $totalImpactoFeriasAbono += $retorno->dados[$i]->r_impacto_ferias_abono - $retorno->dados[$i]->l_impacto_ferias_abono;
                $totalRetidoLiberado += $total_r_linha - $total_l_linha;
            }

            $countItem += 1;
        }

        //Totalizador da linha
        $corpo .= '<tr style="color: #ffffff;background-color: #6610f2;Font: bold 14pt Segoe UI;text-align: right">';
        $corpo .= ' <td style="text-align: center">Totais</td>';
        $corpo .= ' <td></td>';
        $corpo .= ' <td></td>';
        $corpo .= ' <td></td>';

        if ($parametros['decimo_terceiro']) {
            $corpo .= ' <td>' . moeda($totalDecimoTerceiro) . '</td>';
        }

        if ($parametros['ferias_abono']) {
            $corpo .= ' <td>' . moeda($totalFeriasAbono) . '</td>';
        }

        if ($parametros['fgts']) {
            $corpo .= ' <td>' . moeda($totalMultaFGTS) . '</td>';
        }

        if ($parametros['impacto_13']) {
            $corpo .= ' <td>' . moeda($totalImpactoEncargos) . '</td>';
        }

        if ($parametros['impacto_ferias_abono']) {
            $corpo .= ' <td>' . moeda($totalImpactoFeriasAbono) . '</td>';
        }

        $corpo .= ' <td>' . moeda($totalRetidoLiberado) . '</td>';
        $corpo .= ' <td> - </td>';
        $corpo .= ' <tr>';
    }

    echo $html . $cabecalho . $corpo . $htmlFim;
} catch (BadResponseException $e) {

    $retorno = [
        'erro' => true,
        'statusCode' => $e->getResponse()->getStatusCode(),
        'body' => json_decode($e->getResponse()->getBody()->getContents(), JSON_PRETTY_PRINT)
    ];

    echo json_encode($retorno);
    return false;
} catch (Exception $ex) {

    $retorno = [
        'erro' => true,
        'statusCode' => 503,
        'body' => ['msgs' => 'Erro de comunicação com o servidor. Tente novamente']
    ];

    echo json_encode($retorno);
    return false;
}
