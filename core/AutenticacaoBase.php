<?php

use seguranca\Usuario;

class AutenticacaoBase {

//    const NIVEL_ACESSO = [
//        0 => 'ADMIN',
//        1 => 'USUARIO',
//        2 => 'CLIENTE',
//        3 => 'FUNCIONARIO',
//        4 => 'VISITANTE'
//    ];
    const ADMINISTRADOR = 0;
    const USUARIO = 1;
    const CLIENTE = 2;
    //const FUNCIONARIO = 3;
    const VISITANTE = 4;

    const VIEW_HTML = 'HTML';
    const VIEW_AJAX_HTML = 'AJAX_HTML';
    const VIEW_AJAX_JSON = 'AJAX_JSON';

    public static $idSistema;
    public static $idAutorizacao;
    public static $urlCas;
    public static $modoCas;

    /**
     * @var Usuario
     */
    public static $usuario;
    public static $menu;
    public static $conf;
    public static $grupos;
    public static $funcionalidades = [];

    public static function inicia() {
        $conf = include RAIZ . 'config/autenticacao.php';
        self::$conf = $conf[AMBIENTE];
        self::$idSistema = self::$conf['id_sistema'];
        //if (!self::$idSistema) {
        //    throw new \Exception('id do sistema não informado no arquivo de autorização.');
        //}
        if (isset($_SESSION['usuario'])) {
            self::$usuario = unserialize($_SESSION['usuario']);
            if (isset($_SESSION['grupos'])) {
                self::$grupos = $_SESSION['grupos'];
                self::$funcionalidades = $_SESSION['funcionalidades'];
                self::$menu = $_SESSION['menu'];
            }
        } else {
            self::$usuario = false;
        }
    }

    public static function loga($url_solicitante = null) {
        $parametros = [
            'service' => SITE . 'core/seguranca/service.php',
            'url_solicitante' => $url_solicitante
        ];
        if ($url_solicitante) {
            $_SESSION['url_solicitante'] = $url_solicitante;
        }
        //$query_string = SITE . 'app/login/index.php';
        //$query_string = http_build_query($parametros);
        $query_string = APP_HTTP . 'login/index.php';
        //location($query_string);
        location($query_string);
    }

    public static function logout() {
        //location(APP_HTTP . 'login/login.php');
        location(SITE);
    }

    /**
     *
     * @return Usuario
     */
    public static function usuario() {
        return self::$usuario;
    }

    public static function salvaUsuarioSessao() {
        $_SESSION['usuario'] = serialize(self::$usuario);
    }

    public static function logado() {
        if (self::$usuario && self::$usuario->getLogin()) {
            return true;
        }
        return false;
    }

    public static function impedeAcesso($mensagem) {
        $query = http_build_query([
            'msg' => $mensagem,
        ]);
        location(SITE . 'core/seguranca/acesso_impedido.php?' . $query);
    }

    public static function impedeAcessoAjax($mensagem) {
        ?>
        <div class="erro"><?= \e($mensagem) ?></div>
        <?php
        exit;
    }

    public static function impedeAcessoAjaxJSON($mensagem) {
        echo json_encode(
                [
                    'erro' => true,
                    'mensagem' => $mensagem,
                ]
        );
        exit;
    }

    public static function filtraLogado() {
        if (!self::logado()) {
            $mensagem = 'Você não está autenticado.';
            switch ($tipoView) {
                case self::VIEW_HTML:
                    self::impedeAcesso($mensagem);
                    break;
                case self::VIEW_AJAX_HTML:
                    self::impedeAcessoAjax($mensagem);
                    break;
                case self::VIEW_AJAX_JSON:
                    self::impedeAcessoAjaxJSON($mensagem);
                    break;
                default:
                    break;
            }
        }
    }

}
