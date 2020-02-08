<?php

include '../../../inicia.php';
include RAIZ . 'lib/xlsx/simplexlsx.php';

/*
 * Seta o timezone para Brasil
 */
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

use modelo\Captura;

$id_empresa = $_REQUEST['id_empresa'];
$id_contrato = $_REQUEST['id_contrato'];
$mes = $_REQUEST['mes'];
$ano = $_REQUEST['ano'];
$observacao_retencao = $_REQUEST['observacao_retencao'];
//$observacao_liberacao = $_REQUEST['observacao_liberacao'];
$arquivo = APP . $_REQUEST['arquivo_captura'];
$resumo = [];

if (!upload()) {
    return false;
}

try {
    if ($xlsx = SimpleXLSX::parse($arquivo)) {
        list( $num_cols, $num_rows ) = $xlsx->dimension();
        $temErro = false;

        foreach ($xlsx->rows(0) as $key => $r) {
            $dado = new \stdClass;

            // Despreza a primeira linha que é do cabeçalho
            if ($key > 0) {

                // Despreza os registros em branco
                if ($r[0] !== '') {
                    $dado->nome = $r[0];

                    $dado->cpf = str_replace('.', '', $r[1], $count);
                    $dado->cpf = str_replace('-', '', $dado->cpf, $count);
                    $dado->cpf = str_pad($dado->cpf, 11, '0', STR_PAD_LEFT);
                    $dado->nome_cargo = $r[2];
                    //pegar somente o código do turno
                    $dado->id_turno = (int) strtolower($r[3]);
                    $dado->dias_trabalhados = $r[4];
                    $dado->empresa = $id_empresa;
                    $dado->contrato = $id_contrato;
                    $dado->mesLancamento = $mes;
                    $dado->anoLancamento = $ano;

                    /*
                     * Verifica se a planilha possui alguma inconsistência
                     */
                    $temErro = Captura::erroPlanilha($dado);

                    if ($temErro) {
                        $retorno = ['erro' => false,
                            'mensagem' => 'Erro na captura da planilha.'];
                        break;
                    }
                }
            }
        }

        /*
         * Se a planilha não possuir inconsistência, efetua a captura
         */
        if (!$temErro) {
            foreach ($xlsx->rows(0) as $key => $r) {
                $dado = new \stdClass;

                // Despreza a primeira linha que é do cabeçalho
                if ($key > 0) {

                    // Despreza os registros em branco
                    if ($r[0] !== '') {
                        $dado->nome = $r[0];
                        $dado->cpf = str_replace('.', '', $r[1], $count);
                        $dado->cpf = str_replace('-', '', $dado->cpf, $count);
                        $dado->cpf = str_pad($dado->cpf, 11, '0', STR_PAD_LEFT);
                        $dado->nome_cargo = $r[2];
                        $dado->id_turno = (int) strtolower($r[3]);
                        $dado->dias_trabalhados = $r[4];
                        $dado->empresa = $id_empresa;
                        $dado->contrato = $id_contrato;
                        $dado->mesLancamento = $mes;
                        $dado->anoLancamento = $ano;
                        $dado->observacao_retencao = $observacao_retencao;
                        //$dado->observacao_liberacao = $observacao_liberacao;
                        
                        /*
                         * Captura o empregado caso a planilha não tenha inconsistências
                         */
                        $resumo[] = Captura::capturaLancamento($dado);
                    }
                }
            }

            $dadosCaptura = [
                'empresa' => $_REQUEST['id_empresa'],
                'contrato' => $_REQUEST['id_contrato'],
                'historico' => $_REQUEST['nome_arquivo'], //Mostra o nome da planilha capturada
                'mes' => $_REQUEST['mes'],
                'ano' => $_REQUEST['ano'],
                'status_captura' => 'Sucesso'
            ];

            Captura::insereHistoricoCaptura($dadosCaptura);
        }
    } else {
        $retorno = [
            'erro' => true,
            'mensagem' => SimpleXLSX::parse_error()
        ];
    }
} catch (Exception $ex) {
    $retorno = [
        'erro' => true,
        'mensagem' => $ex->getMessage()
    ];
}

echo json_encode($retorno);

/*
 * Efetua o upload da planilha pra dentro do servidor 
 * permitindo que seja capturada
 */
function upload() {

    /*
     * Verifica se existe a array de upload e se o arquivo 
     * enviado possui seu tamanho maior que zero  
     */
    if (isset($_FILES['upload']) && $_FILES['upload']['size'] > 0):

        /*
         * Verifica se o upload foi enviado via POST  
         */
        if (is_uploaded_file($_FILES['upload']['tmp_name'])):

            /*
             * Verifica se o diretório de destino existe, senão existir cria o diretório  
             */
            $caminho = APP . "planilhas/" . $_REQUEST['empresa'];
            $arquivo_upload = $caminho . "/" . $_FILES['upload']['name'];
            if (!file_exists($caminho)) {
                mkdir($caminho);
            }

            if (file_exists($arquivo_upload)) {
                try {
                    unlink($arquivo_upload);
                } catch (Exception $e) {
                    echo $e->getMessage(); // will print Exception message defined above.
                }
            }

            /*
             * Essa função move_uploaded_file() copia e verifica se o arquivo 
             * enviado foi copiado com sucesso para o destino  
             */
            if (move_uploaded_file($_FILES['upload']['tmp_name'], $arquivo_upload)):
                return true; ////echo "Arquivo enviado com sucesso!";
            else:
                return false; //"Houve um erro ao gravar arquivo na pasta de destino!";
            endif;

        endif;
    else:
        /*
         * Switch para verificação de posíveis erros durante o upload  
         */
        $erro = $_FILES['upload']['error'];
        switch ($erro):
            case 0:
                // Não houve erro, o upload foi bem sucedido.  
                return true; //"Houve um erro ao gravar arquivo na pasta de destino!";
                break;
            case 1:
                echo "O arquivo no upload é maior do que o limite definido em upload_max_filesize no php.ini.";
                return false; //"Houve um erro ao gravar arquivo na pasta de destino!";
                break;
            case 2:
                echo "O arquivo ultrapassa o limite de tamanho em MAX_FILE_SIZE que foi especificado no formulário HTML.";
                return false; //"Houve um erro ao gravar arquivo na pasta de destino!";
                break;
            case 3:
                echo "O upload do arquivo foi feito parcialmente.";
                return false; //"Houve um erro ao gravar arquivo na pasta de destino!";
                break;
            case 4:
                echo "Não foi feito o upload do arquivo.";
                return false; //"Houve um erro ao gravar arquivo na pasta de destino!";
                break;
            default:
                echo "Erro desconhecido!";
                return false; //"Houve um erro ao gravar arquivo na pasta de destino!";
                break;
        endswitch;
    endif;
}
