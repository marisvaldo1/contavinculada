<?php

use componentes\Componentes;

class AutenticacaoExterna extends AutenticacaoBase {

    public static function validaTicket() {
        if (!self::$usuario) {

            foreach ($out[1] as $i => $chave) {
                $cas[$chave] = $out[2][$i];
            }

            d('aqui');
            dd($cas['user']);
            $login = $cas['user'];

            if (!$login) {
                throw new \Exception('Erro ao capturar login.');
            }
            if (strpos($login, 'reconhecido') !== false) {
                throw new \Exception($login);
            }
            self::carregaSessaoComLogin($login);
        }
    }

    public static function validaUsuario() {
        try {
            $retorno = Componentes::usuario()->listaUsuarios($login);
        } catch (Exception $ex) {
            $retorno = [
                'erro' => true,
                'mensagem' => $ex->getMessage()
            ];
        }
        return $retorno;
    }

    public static function carregaSessaoComLogin($login) {
        if (!isset($_SESSION['iniciada'])) {
            session_regenerate_id(true);
            $_SESSION['iniciada'] = true;
        }
        $usuario = new \seguranca\Usuario($login);
        $idCorreios = Componentes::idCorreios();
        $retorno = $idCorreios->listarUsuariosPJDoUsuarioPF($login);

        if ($retorno) {
            //TODO incluir mcu da lotação, caso um dia seja criada e remover MCU Fixo
            $usuario->setCpf($retorno->cpf);
            $usuario->setTipo($retorno->tipo);
            $usuario->setNome($idCorreios->listarNome($login));
            $usuario->setEmail($retorno->email);
            $usuario->setNomeLotacao($retorno->nome);
            self::$usuario = $usuario;
            $_SESSION['usuario'] = serialize($usuario);
            return true;
        }
        $retorno = $idAutorizadorUsuarioService->getUsuarioInternoAtivoPeloLogin($login);
        if ($retorno) {
            if ($retorno->usuarioFuncionario) {
                $usuario->setTipo(Usuario::$TIPO_FUNCIONARIO);
                $usuario->setNome($retorno->usuarioFuncionario->nome);
                $usuario->setEmail($retorno->email);
                $usuario->setCpf($retorno->usuarioFuncionario->cpf);
                $usuario->setId($retorno->usuarioFuncionario->id);
                $usuario->setIdInterno($retorno->id);
            } else {
                $usuario->setTipo(Usuario::$TIPO_INTERNO);
                $usuario->setNome($retorno->nome);
                $usuario->setEmail($retorno->email);
                $usuario->setId($retorno->id);
            }
        } else {
            $retorno = Autenticacao::consultaLDAPExtranet($login);
            if ($retorno['mensagem']) {
                throw new Exception(
                'Usuário não encontrado no rol de usuário internos nem extranet.'
                );
            }
            $usuario->setTipo(Usuario::$TIPO_EXTRANET);
            $usuario->setNome($retorno['nome']);
        }

        self::$usuario = $usuario;
        $_SESSION['usuario'] = serialize($usuario);
        $grupos = [];
        $funcionalidades = [];
    }

    public static function autenticaUsuarioExterno($login, $senha) {
        if (!isset($_SESSION['iniciada'])) {
            $_SESSION['iniciada'] = true;
        }
        $usuario = new \seguranca\Usuario($login);
        if (!$retorno) {
            throw new Exception("Usuário ou senha inválidos!");
        }

        if ($retorno) {
            $usuario->setTipo(Usuario::$TIPO_EXTERNO);
            $usuario->setNome($retorno->nome);
            $usuario->setEmail($retorno->email);
            self::$usuario = $usuario;
            $_SESSION['usuario'] = serialize($usuario);
        }
    }

    public static function filtraLogado($tipoView = 'HTML') {
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

    public static function impedeAcesso($mensagem) {
        $query = http_build_query([
            'msg' => $mensagem,
        ]);
        location(SITE . 'app/entrar/index.php?modo=login?' . $query);
    }

}
