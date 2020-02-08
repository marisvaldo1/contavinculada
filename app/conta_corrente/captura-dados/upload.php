<?php

include '../../../inicia.php';

/*
 * Verifica se existe a array de upload e se o arquivo enviado possui seu tamanho maior que zero  
 */
if (isset($_FILES['upload']) && $_FILES['upload']['size'] > 0):

    /*
     * Verifica se o upload foi enviado via POST  
     */
    if (is_uploaded_file($_FILES['upload']['tmp_name'])):
        
        //TODO: Verificar se existe o arquivo
        //Não criou o arquivo pois já existia o copia.

        /*
         * Verifica se o diretório de destino existe, senão existir cria o diretório  
         */
        $caminho = APP . "planilhas/" . $_REQUEST['empresa'];
        $arquivo = $caminho . "/" . $_FILES['upload']['name'];
        if(!file_exists($caminho)){
             mkdir($caminho);
        }

        if(file_exists($arquivo)){  
            try {
                unlink($arquivo);
            } catch (Exception $e) {
                echo $e->getMessage(); // will print Exception message defined above.
            }
        }

        /*
         * Essa função move_uploaded_file() copia e verifica se o arquivo enviado foi copiado com sucesso para o destino  
         */
        if (move_uploaded_file($_FILES['upload']['tmp_name'], $arquivo)):
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
            break;
        case 1:
            echo "O arquivo no upload é maior do que o limite definido em upload_max_filesize no php.ini.";
            break;
        case 2:
            echo "O arquivo ultrapassa o limite de tamanho em MAX_FILE_SIZE que foi especificado no formulário HTML.";
            break;
        case 3:
            echo "O upload do arquivo foi feito parcialmente.";
            break;
        case 4:
            echo "Não foi feito o upload do arquivo.";
            break;
        default:
            echo "Erro desconhecido!";
            break;
    endswitch;  
endif;  