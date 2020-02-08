<?php

/**
 * Monsta informações dos relatórios
 * Date: 02/04/2019
 * Time: 22:56
 */

namespace componentes;

class Relatorios
{

  public function montaInicioHTML()
  {
    $html = "<html>
                <head>
                    <meta charset='UTF-8'>
                    <style type='text/css'>
                        .sHeader1{color: #ffffff;background-color: #6610f2;Font: bold 14pt Segoe UI;text-align: center;}
                        .sHeader2{color: #607D8B;Font: bold 14pt Segoe UI;border: 0px;text-align: center;}
                        .sBody{color: #607D8B;Font: bold 12pt Segoe UI;border: 3px;text-align: center;}
                        .sFooter2{color:#9E9E9E;Font:9pt Segoe UI;border:0px;border-top-width:1px;}
                    </style>
                </head>";
    return $html;
  }

  public function montaCabecalho($dados)
  {

    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    $nomeMes = ['Todos', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    $html = '
            <body style="margin:10;">
                <div align="center">

                    <table width="80%" border="1" cellpadding="5" cellspacing="1" style="border-width: 1px; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="height:45px;">
                              <td class="sHeader1" colspan="12">' . $dados['tipo_relatorio'] . '</td>
                          </tr>
                            <tr style="height:19px;">
                              <td class="sFooter2" colspan="2">' . strftime('%A, %d de %B de %Y', strtotime('today')) . '</td>
                              <td class="sFooter2" colspan="10" style="text-align: right"> Página: 1' . '</td>
                            </tr>';

                            if($dados['id_empresa'] !== ''){
                              $html .= '
                              <tr style="height:29px;">
                              <td class="sHeader2" colspan="12">' . $dados['nome_empresa'] . '</td>
                              </tr>';
                            }
                            
                            $html .= '<tr style="height:29px;">';
                            
                            if($dados['nu_contrato'] !== 'Selecione'){
                              $html .= '
                              <td class="sHeader2" colspan="2" style="text-align: left">Contrato Nº ' . $dados['nu_contrato'] . '</td>';
                            }

                            if($dados['dataInicio'] !== null || $dados['dataFim'] !== null) {
                                $html .= '<td class="sHeader2" colspan="10" style="text-align: right">Período de referência: <br> ' 
                                  . $nomeMes[substr($dados['dataInicio'], 4, 2)] . '/' . substr($dados['dataInicio'], 0, 4);
                                if($dados['dataFim'] !== null) {
                                  $html .= '<br>' . $nomeMes[substr($dados['dataFim'], 4, 2)] .  '/' . substr($dados['dataFim'], 0, 4);
                              }
                              $html .= ' </td>';
                            } else {
                              $html .= '
                              <td class="sHeader2" colspan="10" style="text-align: right">Período de referência: <br>Todos';
                            }
                              
                          $html .= '<tr>
                          <tr bgcolor="#EDEFEF" class="sBody">
                                <td style="width: 1%"><strong>Ano</strong></td>
                                <td style="width: 1%"><strong>Mes</strong></td>
                                <td style="width: 5%"><strong>Funcionário</strong></td>
                                <td style="width: 5%"><strong>Tipo</strong></td> ';

                                if($dados['decimo_terceiro']){
                                  $html .= '<td style="width: 5%"><strong>13&ordm; Sal&aacute;rio</strong></td> ';

                                }
          
                                if($dados['ferias_abono']){
                                  $html .= '<td style="width: 5%"><strong>F&eacute;rias +<br /> Abono</strong></td> ';
                                }
          
                                if($dados['fgts']){
                                  $html .= '<td style="width: 5%"><strong>FGTS</td> ';
                                }
          
                                if($dados['impacto_13']){
                                  $html .= '<td style="width: 5%"><strong>Impacto<br /> sobre13&ordm;</strong></td> ';
                                }
          
                                if($dados['impacto_ferias_abono']){
                                  $html .= '<td style="width: 5%"><strong>Impacto F&eacute;rias + <br />Abono</strong></td> ';
                                }                                

                                $html .= '<td style="width: 5%"><strong>Total<br /> Retido</strong></td>
                                <td style="width: 5%"><strong><br /> Observações</strong></td>
                            </tr>
                        </thead>';

    return $html;
  }

  public function montaCabecalhoExtrato($dados)
  {

    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
    $nomeMes = ['Todos', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    $html = '
            <body style="margin:10;">
                <div align="center">

                    <table width="80%" border="1" cellpadding="5" cellspacing="1" style="border-width: 1px; border-collapse: collapse; font-size: 12px;">
                        <thead>
                            <tr style="height:45px;">
                              <td class="sHeader1" colspan="12">Extrato de Contas</td>
                          </tr>
                          <tr style="height:19px;">
                          <td class="sFooter2" colspan="2">' . strftime('%A, %d de %B de %Y', strtotime('today')) . '</td>
                          <td class="sFooter2" colspan="10" style="text-align: right"> Página: 1' . '</td>
                        </tr>';

                        if($dados['id_empresa'] !== ''){
                          $html .= '
                          <tr style="height:29px;">
                          <td class="sHeader2" colspan="12">' . $dados['nome_empresa'] . '</td>
                          </tr>';
                        }
                        
                        $html .= '<tr style="height:29px;">';
                        
                        if($dados['nu_contrato'] !== 'Selecione'){
                          $html .= '
                          <td class="sHeader2" colspan="2" style="text-align: left">Contrato Nº ' . $dados['nu_contrato'] . '</td>';
                        }

                        if($dados['dataInicio'] !== null || $dados['dataFim'] !== null) {
                            $html .= '<td class="sHeader2" colspan="10" style="text-align: right">Período de referência: <br> ' 
                              . $nomeMes[substr($dados['dataInicio'], 4, 2)] . '/' . substr($dados['dataInicio'], 0, 4);
                            if($dados['dataFim'] !== null) {
                              $html .= '<br>' . $nomeMes[substr($dados['dataFim'], 4, 2)] .  '/' . substr($dados['dataFim'], 0, 4);
                          }
                          $html .= ' </td>';
                        } else {
                          $html .= '
                          <td class="sHeader2" colspan="12" style="text-align: right">Período de referência: <br>Todos';
                        }
                          
                      $html .= '<tr>
                        <tr bgcolor="#EDEFEF" class="sBody">
                            <td style="width: 1%"><strong>Ano</strong></td>
                            <td style="width: 1%"><strong>Mes</strong></td>
                            <td style="width: 5%"><strong>Funcionário</strong></td>
                            <td style="width: 5%"><strong>Tipo</strong></td>';

                            if($dados['decimo_terceiro']){
                              $html .= '<td style="width: 5%"><strong>13&ordm; Sal&aacute;rio</strong></td> ';
                            }
      
                            if($dados['ferias_abono']){
                              $html .= '<td style="width: 5%"><strong>F&eacute;rias +<br /> Abono</strong></td> ';
                            }
      
                            if($dados['fgts']){
                              $html .= '<td style="width: 5%"><strong>FGTS</td> ';
                            }
      
                            if($dados['impacto_13']){
                              $html .= '<td style="width: 5%"><strong>Impacto<br /> sobre13&ordm;</strong></td> ';
                            }
      
                            if($dados['impacto_ferias_abono']){
                              $html .= '<td style="width: 5%"><strong>Impacto F&eacute;rias + <br />Abono</strong></td> ';
                            }
                            
                            $html .= '<td style="width: 5%"><strong>Total<br /> Retido</strong></td>
                                      <td style="width: 5%"><strong><br /> Observações</strong></td>
                        </tr>
                        </thead>';  
    return $html;
  }

  public function insereQuebraDePagina()
  {
    $html = "<h1></h1>";
    return $html;
  }

  public function montaFimHTML()
  {
    $html = "           </table>
                        </div>
                    </body>
		</html>";

    return $html;
  }
}
