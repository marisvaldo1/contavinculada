<?php

function autoload($classe) {
    if (strpos($classe, 'modelo') !== false ||
            strpos($classe, 'app') !== false ||
            strpos($classe, 'cli') !== false ||
            strpos($classe, 'lib') !== false) {
        $arquivo = RAIZ . str_replace(['\\'], DIRECTORY_SEPARATOR, $classe) . '.php';
        if (file_exists($arquivo)) {
            include $arquivo;
            return;
        }
    } else {
        $arquivo = RAIZ . str_replace(['\\'], DIRECTORY_SEPARATOR, 'core/' . $classe) . '.php';
        if (file_exists($arquivo)) {
            include $arquivo;
            return;
        }
        $arquivo = RAIZ . str_replace(['\\'], DIRECTORY_SEPARATOR, 'libs/' . $classe) . '.php';
        if (file_exists($arquivo)) {
            include $arquivo;
            return;
        }
    }
}

function autoload_api($classe) {
    $pastas = [
        'recursos',
        'core',
    ];
    foreach ($pastas as $pasta) {
        $file = RAIZ . (str_replace(['api\\', '\\'], ['api\\' . $pasta . '\\', '/'], $classe)) . '.php';
        if (file_exists($file)) {
            include $file;
            return;
        }
    }
}

function config($configuracao) {
    $arquivo = RAIZ . 'config/' . $configuracao . '.php';
    if (!file_exists($arquivo)) {
        throw new \Exception('Configuração ' . $configuracao . ' inexistente.');
    }
    return include($arquivo);
}

function delta_t() {
    return microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
}

/** Abreviatura para bd\MySQL::conexao();
 *
 * @return bd\MySQL;
 */
function conexao($data_source = null) {
    return bd\MySQL::conexao($data_source);
}

/**
 * Checa se uma string está contida em outra.
 * @param type $isso
 * @param type $nisso
 * @return boolean
 */
function contem($isso, $nisso) {
    return (strpos($nisso, $isso) !== false);
}

function jsonpar($str_obj) {
    return json_decode($str_obj, true);
}

function jsonstr($obj, $pretty = false) {
    if ($pretty) {
        return json_encode($obj, JSON_PRETTY_PRINT);
    }
    return json_encode($obj);
}

function regera_id() {
    if (!isset($_SESSION['iniciada'])) {
        //session_regenerate_id(true);
        $_SESSION['iniciada'] = true;
    }
}

function to_session($vetor, $nome) {
    if (isset($vetor)) {
        foreach ($vetor as $chave => $valor) {
            $_SESSION[$nome][$chave] = $valor;
        }
    }
    return $_SESSION[$nome];
}

function from_session($nome) {
    if (isset($_GET['p'])) {
        return $_SESSION[$nome];
    } else {
        unset($_SESSION[$nome]);
        return [];
    }
}

function dia_semana($strdata) {
    if ($strdata) {
        $d = date_create_from_format('d/m/Y', $strdata);
        switch (date_format($d, 'w')) {
            case 0:
                return 'dom';
            case 1:
                return 'seg';
            case 2:
                return 'ter';
            case 3:
                return 'qua';
            case 4:
                return 'qui';
            case 5:
                return 'sex';
            case 6:
                return 'sáb';
            default:
                return null;
        }
    }
    return null;
}

/* * Converte um número real em formato de moeda brasileira.<br>
 * Sensacional, não é mesmo?
 * 
 * @param double $numero Número real
 * @return string Moeda brasileira
 */

function moeda($numero) {
    if (!is_null($numero)) {
        return number_format($numero, 2, ',', '.');
    }
}

/* * Converte uma string no formato #.###,## em número real.
 * 
 * @param string $str_valor
 * @return double
 */

function real($str_valor) {
    $float = str_replace(['.', ','], ['', '.'], $str_valor);
    return floatval($float);
}

function hasha256($dado) {
    return hash('sha256', $dado);
}

function token($caracteres = 64) {
    return substr(hasha256(uniqid(rand(), true)), 0, $caracteres);
}

function e($txt) {
    return htmlentities($txt, ENT_QUOTES, 'UTF-8');
}

function hs($txt) {
    return htmlspecialchars($txt, ENT_COMPAT);
}

/* * Permite a escrita de strings Javascript diretamente em trechos de código Javascript.<br>
 * Exemplo: var qry = JSON.parse("<?= ejs(json_encode($qry)) ?>");
 * 
 * @param string $str_js String a ser escapada.
 * @return string String escapada
 */

function ejs($str_js) {
    return str_replace(["\n", '"'], ['\n', '\"'], $str_js);
}

function d($array) {
    echo '<br><pre class="printr">';
    print_r($array);
    echo '</pre>';
}

function dd($array) {
    d($array);
    exit;
}

function v($array) {
    var_dump($array);
}

function vv($array) {
    var_dump($array);
    exit;
}

function cacheia($dias) {
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $dias * 86400));
    header('Cache-Control: max-age=' . $dias * 86400);
    header('Pragma: ');
}

function gzip() {
    if ($_SERVER['HTTP_ACCEPT_ENCODING']) {
        ob_end_clean();
        ob_start('ob_gzhandler');
    }
}

function ajax($tipo = 'html', $diasCache = 0) {
    if ($diasCache) {
        cacheia($diasCache);
    } else {
        header('Cache-Control: no-cache, must-revalidate');
    }
    $tipo = strtolower($tipo);
    switch ($tipo) {
        case 'html':
            $GLOBALS['AJAX'] = 'html';
            header('Content-type: text/html; charset=utf-8');
            break;
        case 'json':
            $GLOBALS['AJAX'] = 'json';
            header('Content-type: application/json; charset=utf-8');
            break;
        case 'xml':
            $GLOBALS['AJAX'] = 'xml';
            header('Content-type: text/xml; charset=utf-8');
            echo '<?xml version="1.0" encoding="utf-8"?>';
            break;
        case 'text':
            $GLOBALS['AJAX'] = 'text';
            header('Content-type: text/plain; charset=utf-8');
            $GLOBALS['COMPRESSAO'] = false;
            break;
    }
    gzip();
}

function trim_all($string) {
    return trim(preg_replace('/\s+/', ' ', $string));
}

/* * Abreviatura de header('Location: url');
 * 
 * @param type $url
 */

function location($url) {
    header('Location: ' . $url);
    exit;
}

/**
 *
 * @return string retorna o dia de hoje no formato dd/mm/yyyy.
 */
function hoje() {
    return date('d/m/Y');
}

function load_json_file($file) {
    if (file_exists($file)) {
        return jsonpar(file_get_contents($file));
    }
    throw new Exception('Erro ao carregar arquivo JSON.');
}

/**
 * Converte uma string em maiúsculas.
 * @param string $string String.
 * @return string String em maiúsculas.
 */
function upper($string) {
    return mb_strtoupper($string, 'UTF-8');
}

/**
 * Converte uma string em minúsculas.
 * @param string $string String.
 * @return string String em minúsculas.
 */
function lower($string) {
    return mb_strtolower($string, 'UTF-8');
}

function header_json() {
    header('Content-type: application/json; charset=utf-8');
}

function cifra($texto, $chave) {
    return base64_encode(
            mcrypt_encrypt(
                    MCRYPT_RIJNDAEL_256, hash('SHA256', $chave, true), $texto, MCRYPT_MODE_CBC, hash('SHA256', $chave, true)
            )
    );
}

function decifra($texto, $chave) {
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, hash('SHA256', $chave, true), base64_decode($texto),
                    MCRYPT_MODE_CBC, hash('SHA256', $chave, true)), "\0");
}

function assina($string, $chave) {
    return base64_encode(hash_hmac("sha512", $string, $chave));
}

function verifica_assinatura($string, $assinatura, $chave) {
    return hash_equals(hash_hmac("sha512", $string, $chave), base64_decode($assinatura));
}

function json_encode64($array) {
    return base64_encode(json_encode($array));
}

function json_decode64($json_encoded64) {
    return json_decode(base64_decode($json_encoded64), true);
}

function envia_email($para, $assunto, $mensagem) {
    $emails = \config('email');
    $de = $emails[HOST];
    $to = [];
    $to_headers = [];
    if (is_array($para)) {
        foreach ($para as $valor) {
            $to_headers[] = $valor;
            $partes = explode(' ', $valor);
            $to[] = str_replace(['<', '>'], '', $partes[count($partes) - 1]);
        }
    } else {
        $to_headers[] = $para;
        $partes = explode(' ', $para);
        $to[] = str_replace(['<', '>'], '', $partes[count($partes) - 1]);
    }
    $headers = 'MIME-Version: 1.1' . "\r\n" .
            'Content-type: text/html; charset=utf-8' . "\r\n" .
            'From: ' . $de . "\r\n" .
            'To: ' . implode(',', $to_headers);
    $mensagem = str_replace(["\n."], ["\n.."], $mensagem);
    $mensagem = wordwrap($mensagem, 70);
    return mail(implode(',', $to), $assunto, $mensagem, $headers, "-r " . $de);
}

function escala_imagem($origem, $largura, $destino_sem_extensao, $altura = -1) {
    $extensao = lower(pathinfo($origem, PATHINFO_EXTENSION));
    switch ($extensao) {
        case 'jpg':
        case 'jpeg':
            $im = imagecreatefromjpeg($origem);
            break;
        case 'png':
            $im = imagecreatefrompng($origem);
            break;
        default :
            throw new \Exception('Extensão de imagem não suportada.');
    }
    if ($largura > 0) {
        $im = imagescale($im, $largura, $altura, IMG_BICUBIC_FIXED);
    }
    if ($extensao == 'jpg' || $extensao == 'jpeg') {
        imagejpeg($im, $destino_sem_extensao . '.' . $extensao);
    } else {
        imagepng($im, $destino_sem_extensao . '.' . $extensao);
    }
    return pathinfo($destino_sem_extensao . '.' . $extensao, PATHINFO_BASENAME);
}

function cors() {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Key, Cache-Control");
}

function cli() {
    if (php_sapi_name() != 'cli') {
        throw new \Exception('Somente CLI.');
    }
}

function listaArquivosRecursivamente($diretorio) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($diretorio));
    foreach ($it as $k => $v) {
        if ($v->getFilename()[0] !== '.') {
            yield $v;
        }
    }
}

function lock() {
    static $x = null;
    $x = fopen("lock.txt", "w");
    if (!flock($x, LOCK_EX | LOCK_NB)) {
        exit;
    }
}

function cache($arquivo, $callback) {
    if (file_exists($arquivo) && time() - filemtime($arquivo) < 10) {
        include $arquivo;
    } else {
        ob_end_clean();
        ob_start();
        $callback();
        $cache = ob_get_clean();
        file_put_contents($arquivo, $cache);
        echo $cache;
    }
}

function zip($pasta, $arquivo) {
    $base = basename($pasta);
    $zip = new ZipArchive();
    $zip->open($arquivo, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addEmptyDir($base);
    $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($pasta),
            RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($pasta) + 1);
            $zip->addFile($filePath, $base . '/' . $relativePath);
        }
    }
}

function oracle_con($nome_conexao = null) {
    $config = config('oracle');
    if (\Sistema::$ambiente_oracle) {
        $ambiente = \Sistema::$ambiente_oracle;
    } else {
        if (!isset($config[$_SERVER['AMBIENTE_PUBLICACAO']])) {
            throw new Exception('Configuração Oracle inexistente.');
        }
        $ambiente = $_SERVER['AMBIENTE_PUBLICACAO'];
    }
    $conexoes = $config[$ambiente];
    if ($nome_conexao === null) {
        $nome_conexao = array_keys($conexoes)[0];
    }
    $conexaoNativa = $conexoes[$nome_conexao];
    $c = @oci_pconnect(
                    $conexaoNativa['usuario'], $conexaoNativa['senha'], $conexaoNativa['servidor'], 'AL32UTF8'
    );
    if (!$c) {
        $e = oci_error();
        throw new \Exception('Erro Oracle: ' . $e['message']);
    }
    oracle_parse_execute($c, "alter session set NLS_DATE_FORMAT='dd/mm/yyyy hh24:mi:ss'");
    oracle_parse_execute($c, "alter session set NLS_COMP='LINGUISTIC'");
    oracle_parse_execute($c, "alter session set NLS_SORT='BINARY_AI'");
    return $c;
}

/**
 * @param $c resource
 * @param $sql string
 * @return resource
 * @throws Exception
 */
function oracle_parse($c, $sql) {
    $com = @oci_parse($c, $sql);
    if (!$com) {
        $e = oci_error($c);
        throw new Exception('oracle parse: ' . $e['message']);
    }
    return $com;
}

/**
 * @param $com resource
 * @param $nome string
 * @param $valor mixed
 */
function oracle_bind($com, $nome, $valor) {
    oci_bind_by_name($com, $nome, $valor);
}

/**
 * @param $com resource
 * @param $transacao boolean
 * @throws Exception
 */
function oracle_execute($com, $transacao = false) {
    if ($transacao) {
        $modo = OCI_NO_AUTO_COMMIT;
    } else {
        $modo = OCI_COMMIT_ON_SUCCESS;
    }
    $r = @oci_execute($com, $modo);
    if (!$r) {
        $e = oci_error($com);
        throw new Exception('oracle execute:' . $e['message']);
    }
}

/**
 * @param $c resource
 * @param $sql string
 * @return resource
 */
function oracle_parse_execute($c, $sql, $transacao = false) {
    $com = oracle_parse($c, $sql);
    oracle_execute($com, $transacao);
    return $com;
}

/**
 * @param $com resource
 * @return array
 */
function oracle_fetch_assoc($com) {
    return oci_fetch_assoc($com);
}

/**
 * @param $c resource
 */
function oracle_commit($c = null) {
    if (!$c) {
        $c = oracle_con();
    }
    oci_commit($c);
}

/**
 * @param $c resource
 */
function oracle_rollback($c) {
    oci_rollback($c);
}

/**
 * @param $array
 * @param string $prefixo
 * @return array
 */
function oracle_bindin($array, $prefixo = '') {
    $ret = [
        'chaves' => [],
        'valores' => $array,
    ];
    $binds = [];
    $n = count($array);
    for ($i = 0; $i < $n; $i++) {
        $ret['chaves'][] = ':' . $prefixo . $i;
    }
    return $ret;
}

/**
 * @param $binds array
 * @return string
 */
function oracle_bindin_implode($binds) {
    return implode(',', $binds['chaves']);
}

function oracle_bindin_bind($com, $binds) {
    $n = count($binds['chaves']);
    for ($i = 0; $i < $n; $i++) {
        oci_bind_by_name($com, $binds['chaves'][$i], $binds['valores'][$i]);
    }
}

/**
 * @return \PHPMailer\PHPMailer\PHPMailer
 */
function criaEmail() {
    $conf = config('email');
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->IsSMTP();
    $mail->Host = $conf['host'];
    $mail->SMTPAuth = $conf['smtpauth'];
    $mail->Port = $conf['port'];
    $mail->setFrom($conf['from']);
    return $mail;
}

function requestBody() {
    return file_get_contents('php://input');
}

function checkBrowser() {
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $msie = strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE') ? true : false;
        if ($msie) {
            return false;
        } else {
            return true;
        }
    }
}

function modal($id, $tamanho = "lg", $direcao = NULL, $titulo = "Titulo ?", $conteudo = "Conteúdo ?") {

    $modal = "
	<div class='modal " . $direcao . " fade' id='" . $id . "' tabindex='-1' role='dialog' aria-labelledby='" . $id . "' aria-hidden='true'>
        <div class='modal-dialog modal-" . $tamanho . "' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h5 class='modal-title' id='tituto-modal' id='" . $id . "'>" . $titulo . "</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>×</span>
                    </button>
                </div>
                <div class='modal-body'><div id='detalhe-modal'>"
            . $conteudo .
            "</div></div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Fechar</button>
                </div>
            </div>
        </div>
	</div>
	";
    echo($modal);
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function verificaAcesso($pagina) {
    if (($pagina == "administradores" ||
            $pagina == "clientes") && $_SESSION["dados_usuario"]->getNivel_acesso() != ADMINISTRADOR)
        return false;
    else
        return true;
}

function validaCPF($cpf = null) {

    // Verifica se um número foi informado
    if (empty($cpf)) {
        return false;
    }

    // Elimina possivel mascara
    $cpf = preg_replace("/[^0-9]/", "", $cpf);
    $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

    // Verifica se o numero de digitos informados é igual a 11 
    if (strlen($cpf) !== 11) {
        return false;
    }
    // Verifica se nenhuma das sequências invalidas abaixo 
    // foi digitada. Caso afirmativo, retorna falso
    else if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999') {
        return false;
        // Calcula os digitos verificadores para verificar se o
        // CPF é válido
    } else {

        for ($t = 9; $t < 11; $t++) {

            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }

        return true;
    }
}

function formatarCnpj($cnpj_cpf) {
    if (strlen(preg_replace("/\D/", '', $cnpj_cpf)) === 11) {
        $response = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } else {
        $response = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }

    return $response;
}

/**
 * Mascara genérica
 * mask( '###.###.###-##', '85489632587' )
 * 
 * @param str Define a máscara. Ex: ###.###.###-##
 * @param str Define o campo a ser mascarado

 * @return str campo mascarado. Ex 854.896.325-87
 */
function mask($mask, $str){
    $str = str_replace(" ", "", $str);

    for($i=0; $i<strlen($str); $i++){
        $mask[strpos($mask,"#")] = $str[$i];
    }
    return $mask;
}

/**
 * Retorna o endereço de IP do cliente
 * mask( '###.###.###-##', '85489632587' )
 * 
 * @param str Define a máscara. Ex: ###.###.###-##
 * @param str Define o campo a ser mascarado

 * @return str campo mascarado. Ex 854.896.325-87
 */
function get_client_ip()
{
    foreach (array(
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER)) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if ((bool) filter_var($ip, FILTER_VALIDATE_IP,
                                FILTER_FLAG_IPV4 |
                                FILTER_FLAG_NO_PRIV_RANGE |
                                FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
    }
    return null;
}