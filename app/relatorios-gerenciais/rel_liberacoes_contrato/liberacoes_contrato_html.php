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
    $cabecalho = $extrato->montaCabecalho($parametros);
    $corpo = '';
    $htmlFim = $extrato->montaFimHTML();

    $countItem = 0;

    if (!$retorno->erro) {

        $total_linha = 0;
        $totalDecimoTerceiro = 0;
        $totalFeriasAbono = 0;
        $totalMultaFGTS = 0;
        $totalImpactoEncargos = 0;
        $totalImpactoFeriasAbono = 0;
        $totalLiberado = 0;
        $nomeMes = ['Todos', 'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

        for ($i = 0; $i < count($retorno->dados); $i++) {

            if ($chave != 'erro') {

                //Total de renções da linha
                $total_linha = $retorno->dados[$i]->l_decimo_terceiro +
                        $retorno->dados[$i]->l_ferias_abono +
                        $retorno->dados[$i]->l_multa_FGTS +
                        $retorno->dados[$i]->l_impacto_encargos_13 +
                        $retorno->dados[$i]->l_impacto_ferias_abono;

                if ($total_linha > 0) {
                    $corpo .= '<tr style="text-align: right">';
                    $corpo .= ' <td style="text-align: center">' . $retorno->dados[$i]->ano . '</td>';
                    $corpo .= ' <td style="text-align: center">' . $nomeMes[number_format($retorno->dados[$i]->mes)] . '</td>';
                    $corpo .= ' <td style="text-align: left">' . $retorno->dados[$i]->nome . '</td>';
                    $corpo .= ' <td style="text-align: center">' . formatarCnpj($retorno->dados[$i]->cpf) . '</td>';
                    $corpo .= ' <td>' . 'R$ ' . moeda($retorno->dados[$i]->l_decimo_terceiro) . '</td>';
                    $corpo .= ' <td>' . 'R$ ' . moeda($retorno->dados[$i]->l_ferias_abono) . '</td>';
                    $corpo .= ' <td>' . 'R$ ' . moeda($retorno->dados[$i]->l_multa_FGTS) . '</td>';
                    $corpo .= ' <td>' . 'R$ ' . moeda($retorno->dados[$i]->l_impacto_encargos_13) . '</td>';
                    $corpo .= ' <td>' . 'R$ ' . moeda($retorno->dados[$i]->l_impacto_ferias_abono) . '</td>';
                    $corpo .= ' <td>' . 'R$ ' . moeda($total_linha) . '</td>';
                    $corpo .= ' <td>' . $retorno->dados[$i]->observacao_liberacao . '</td>';
                    $corpo .= '<tr>';

                    $totalDecimoTerceiro += $retorno->dados[$i]->l_decimo_terceiro;
                    $totalFeriasAbono += $retorno->dados[$i]->l_ferias_abono;
                    $totalMultaFGTS += $retorno->dados[$i]->l_multa_FGTS;
                    $totalImpactoEncargos += $retorno->dados[$i]->l_impacto_encargos_13;
                    $totalImpactoFeriasAbono += $retorno->dados[$i]->l_impacto_ferias_abono;
                    $totalLiberado += $total_linha;
                }
            }

            $countItem += 1;
        }

        //Totalizador da linha
        $corpo .= '<tr style="color: #0c0c0c;background-color: #EDEFEF;Font: bold 10pt Segoe UI;text-align: right;">';
        $corpo .= ' <td style="text-align: center"><strong>Totais</strong></td>';
        $corpo .= ' <td></td>';
        $corpo .= ' <td></td>';
        $corpo .= ' <td></td>';
        $corpo .= ' <td><strong>' . 'R$ ' . moeda($totalDecimoTerceiro) . '</strong></td>';
        $corpo .= ' <td><strong>' . 'R$ ' . moeda($totalFeriasAbono) . '</strong></td>';
        $corpo .= ' <td><strong>' . 'R$ ' . moeda($totalMultaFGTS) . '</strong></td>';
        $corpo .= ' <td><strong>' . 'R$ ' . moeda($totalImpactoEncargos) . '</strong></td>';
        $corpo .= ' <td><strong>' . 'R$ ' . moeda($totalImpactoFeriasAbono) . '</strong></td>';
        $corpo .= ' <td><strong>' . 'R$ ' . moeda($totalLiberado) . '</strong></td>';
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
