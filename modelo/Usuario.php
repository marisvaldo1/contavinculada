<?php

namespace modelo;

use bd\My;
use bd\MySQL;

class Usuario
{

    public $id_usuario;
    public $nome;
    public $email;
    public $senha;
    public $nivel_acesso;
    public $id_empresa_visitante;
    public $nome_empresa_visitante;
    public $opcoes_acesso;
    public $status_usuario;
    public $id_cliente;
    public $nome_cliente;
    public $acesso;
    public $data_login;
    public $IP_login;
    public $token_login;

    public static function buscaUsuario($dados = null)
    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT 
                usu.id_usuario, 
                usu.nome, 
                usu.email, 
                usu.opcoes_acesso, 
                usu.status_usuario, 
                usu.nivel_acesso, 
                usu.id_empresa_visitante, 
                usu.id_cliente, 
                usu.data_login, 
                IFNULL(usu.token_login, "") token_login, 
                usu.IP_login, 
                cli.razao nome_cliente, 
                (CASE 
                    WHEN usu.nivel_acesso = 0 THEN "ADMINISTRADOR" 
                    WHEN usu.nivel_acesso = 1 THEN "USUARIO" 
                    WHEN usu.nivel_acesso = 2 THEN "CLIENTE" 
                    WHEN usu.nivel_acesso = 3 THEN "FUNCIONARIO" 
                    WHEN usu.nivel_acesso = 4 THEN "VISITANTE" END
                ) acesso 
                FROM usuarios usu, clientes cli
                WHERE usu.id_cliente = cli.id_cliente 
                AND usu.status_usuario = "ATIVO" ';

        if ($_SESSION["dados_usuario"]->getNivel_acesso() !== ADMINISTRADOR) {
            if ($dados['definicao_acesso']) {
                $sql .= ' AND nivel_acesso <> ' . CLIENTE;
            }
        }

        if ($dados['id_usuario']) {
            $sql .= ' AND id_usuario = ' . $dados['id_usuario'];
        }

        $usuario = new \stdClass();
        $usuarios = new \stdClass();
        $usuarios->erro = false;
        $usuarios->dados = [];

        $resultado = $conexao->query($sql);
        if ($resultado->num_rows) {
            while ($row = $conexao->fetch($resultado)) {
                $usuario = new Usuario();
                $usuario->setId_usuario($row['id_usuario']);
                $usuario->setNome($row['nome']);
                $usuario->setEmail($row['email']);
                $usuario->setNivel_acesso($row['nivel_acesso']);
                $usuario->setId_empresa_visitante($row['id_empresa_visitante']);
                $usuario->setId_cliente($row['id_cliente']);
                $usuario->setNome_cliente($row['nome_cliente']);
                $usuario->setOpcoes_acesso($row['opcoes_acesso']);
                $usuario->setAcesso($row['acesso']);
                $usuario->setStatus_usuario($row['status_usuario']);
                $usuario->setData_login($row['data_login']);
                $usuario->setIP_login($row['IP_login']);
                $usuario->setToken_login($row['token_login']);
                $usuarios->dados[] = $usuario;
            }
        } else {
            $usuarios->erro = true;
            $usuarios->mensagem = 'Nenhum usuário cadastrado.';
        }

        return $usuarios;
    }

    public static function gravaUsuario($dados)
    {

        $conexao = MySQL::conexao();
        if ($dados['acao'] == 'alterar') {
            $sql = 'UPDATE usuarios SET '
                . 'nome = "' . $dados['nome'] . '", '
                . 'email = "' . $dados['email'] . '", ';

            if ($dados['nova_senha']) {
                $sql .= 'senha = "' . $dados['nova_senha'] . '" ';
            } else {
                $sql .= 'senha = "' . $dados['senha'] . '" ';
            }

            if ($dados['nivel_acesso']) {
                $sql .= ', nivel_acesso = "' . $dados['nivel_acesso'] . '" ';
            }

            if ($dados['id_empresa']) {
                $sql .= ', id_empresa_visitante = "' . $dados['id_empresa'] . '" ';
            }

            if ($dados['id_cliente']) {
                $sql .= ', id_cliente = "' . $dados['id_cliente'] . '" ';
            }

            if ($dados['id_usuario']) {
                $sql .= ' WHERE id_usuario = ' . $dados['id_usuario'];
            } else {
                $sql .= ' WHERE id_usuario = ' . $_SESSION["dados_usuario"]->getUsuario();
            }
        } else {
            $sql = 'INSERT '
                . 'INTO usuarios ('
                . 'nome, '
                . 'email, '
                . 'senha, '
                . 'nivel_acesso, '
                . 'id_cliente, '
                . 'id_empresa_visitante, '
                . 'status_usuario) '
                . ' VALUES ( '
                . '"' . $dados['nome'] . '",'
                . '"' . $dados['email'] . '",'
                . '"' . $dados['senha'] . '",'
                . '"' . $dados['nivel_acesso'] . '",'
                . '"' . $dados['id_cliente'] . '",';

            if ($dados['id_empresa']) {
                $sql .= '"' . $dados['id_empresa'] . '",';
            } else {
                $sql .= '"",';
            }

            $sql .= '"ATIVO")';
        }

        $r = $conexao->query($sql);
        $usuario = new \stdClass();
        $usuario->erro = false;

        if ($dados['acao'] == 'alterar')
            $usuario->mensagem = 'Alteração efetuada com sucesso';
        else
            $usuario->mensagem = 'Inclusão efetuada com sucesso';

        return $usuario;
    }

    public static function gravaAcesso($dados)
    {

        $conexao = MySQL::conexao();
        $sql = 'UPDATE usuarios SET '
            . 'opcoes_acesso = "' . $dados['nome'] . '" '
            . 'WHERE id_usuario = ' . $dados['id_usuario'];

        $r = $conexao->query($sql);
        $usuarios = new \stdClass();
        $usuarios->erro = false;
        $usuarios->mensagem = 'Alteração efetuada com sucesso';

        return $usuarios;
    }

    public static function excluiUsuario($id_usuario)
    {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE usuarios SET '
            . 'status_usuario = "EXCLUIDO" '
            . 'WHERE id_usuario = ' . $id_usuario;

        $r = $conexao->query($sql);
        $usuarios = new \stdClass();
        $usuarios->erro = false;
        $usuarios->mensagem = 'Usuario excluído com sucesso';

        return $usuarios;
    }

    public static function buscaLogin($email, $senha)
    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
            . 'usu.id_usuario, '
            . 'usu.nome, '
            . 'usu.email,'
            . 'usu.senha,'
            . 'usu.nivel_acesso, '
            . 'usu.opcoes_acesso, '
            . 'usu.id_empresa_visitante, '
            . 'usu.id_cliente, '
            . 'cli.razao nome_cliente, '
            . 'usu.status_usuario, '
            . 'IFNULL(usu.token_login, "") token_login '
            . 'FROM usuarios usu, clientes cli '
            . 'WHERE usu.status_usuario = "ATIVO" '
            . ' AND usu.email =  "' . $email . '"'
            . ' AND usu.senha =  "' . $senha . '"'
            . ' AND usu.id_cliente = cli.id_cliente';

        $usuario = new \stdClass();

        try {
            $resultado = $conexao->query($sql);
        } catch (\Exception $e) {
            $usuario->erro = true;
            $usuario->mensagem = $e->getMessage();
            return $usuario;
        } catch (\Error $e) {
            $usuario->erro = true;
            $usuario->mensagem = 'Usuário não cadastrado';
            return $usuario;
        }

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);

            if ($l['email'] != $email || $l['senha'] != $senha) {
                $usuario->erro = true;
                $usuario->mensagem = 'Usuário ou senha inválido. Verifique';
                return $usuario;
            } else {
                $usuario->erro = false;
                $usuario->nome = $l['nome'];
                $usuario->email = $l['email'];
                $usuario->nivel_acesso = $l['nivel_acesso'];
                $usuario->id_empresa_visitante = $l['id_empresa_visitante'];
                $usuario->nome_empresa_visitante = ''; //Só carrega este valor para usuário visitante
                $usuario->opcoes_acesso = $l['opcoes_acesso'];
                $usuario->id_cliente = $l['id_cliente'];
                $usuario->id_usuario = $l['id_usuario'];
                $usuario->nome_cliente = $l['nome_cliente'];
                $usuario->status_usuario = $l['status_usuario'];
                $usuario->token_login = $l['token_login'];
            }

            //Se usuário visitante, busca a empresa que ele está vinculado para visitar
            if ($l['nivel_acesso'] === "4") {
                $sql = ' SELECT razao nome_empresa_visitante '
                    . ' FROM empresas '
                    . ' WHERE id_empresa = ' . $usuario->id_empresa_visitante;
                $empresa = new \stdClass();
                $resultado = $conexao->query($sql);
                $l = $conexao->fetch($resultado);
                $usuario->nome_empresa_visitante = $l['nome_empresa_visitante'];
            }

            //Verifica se o cliente tem contrato de utilização do sistema e se este está vigente
            $sql = ' SELECT id_contrato '
                . ' FROM contrato_sistema '
                . ' WHERE id_cliente = ' . $usuario->id_cliente
                . ' AND curdate() >= dt_inicio AND curdate() <= dt_final '
                . ' AND status_contrato_sistema = "ATIVO" ';
            $contrato = new \stdClass();

            try {
                $resultado = $conexao->query($sql);
            } catch (\Exception $e) {
                $contrato->erro = true;
                $contrato->mensagem = $e->getMessage();

                return $contrato;
            }

            if (!$resultado->num_rows && $usuario->nivel_acesso > 0) {
                $contrato->erro = true;
                $contrato->mensagem = 'Problemas no acesso ao sistema. <br>Contate o Administrador informando o código <b>#020</b>';

                return $contrato;
            } else {
                /*
                 * Verifica a situação financeira do cliente para que o usuário possa acessar o sistema
                 * A data atual válida vem do servidor de banco de dados
                 */
                $sql = ' SELECT curdate() data_hoje, pg.data_vencimento, pg.data_pagamento, valor_pagamento '
                    . ' FROM pagamentos pg, contrato_sistema cs '
                    . ' WHERE pg.id_cliente = ' . $usuario->id_cliente
                    . ' AND pg.id_contrato = cs.id_contrato '

                    //Garante que a data atual está dentro da validade do contrato
                    . ' AND curdate() >= cs.dt_inicio AND curdate() <= cs.dt_final '

                    //Verifica a situação financeira do contrato para o cliente
                    . ' AND curdate() > pg.data_vencimento '
                    . ' AND data_pagamento is null '
                    . ' AND cs.status_contrato_sistema = "ATIVO" '
                    . ' ORDER BY data_vencimento ';
                $acesso = new \stdClass();

                try {
                    $resultado = $conexao->query($sql);
                } catch (\Exception $e) {
                    $acesso->erro = true;
                    $acesso->mensagem = $e->getMessage();
                    return $acesso;
                }

                /*
                 * Se esta query retornar valor significa que o contrato está com pagamento em atrazo
                 * e o usuário não poderá acessar o sistema
                 */
                if ($resultado->num_rows && $usuario->nivel_acesso > 0) {
                    $acesso->erro = true;
                    $acesso->mensagem = 'Problemas no acesso ao sistema. <br>Contate o Administrador informando o código <b>#010</b>';
                    return $acesso;
                }
            }
        } else {
            $usuario->erro = true;
            $usuario->mensagem = 'Usuário não cadastrado ou Senha inválida. Verifique.';
            return $usuario;
        }

        /*
         * Se for administrador, zera as informações de login de todos os usuários logados
         * com data anterior à data atual forçando o usuário a se logar novamente
         * */
        $sql = 'UPDATE usuarios SET 
                    token_login = ""
                WHERE data_login < CURDATE()';
        $r = $conexao->query($sql);

        /*
         * Se chegou aqui, o usuário existe, 
         * tem contrato vigente de utilização do sistema, 
         * e as parcelas estão em dia
         * 
         * Se o usuário estiver logado em outro IP seta para o 
         * reescreve o token no banco para matar a sessão anterior
         */
        $token = Usuario::getToken(10);
        $sql = 'UPDATE usuarios SET 
                    data_login = "' . date('Y-m-d H:i:s') . '",
                    IP_login = "' . get_client_ip() . '", 
                    token_login = "' . $token . '"
                WHERE status_usuario = "ATIVO" 
                AND email =  "' . $email . '"
                AND senha =  "' . $senha . '"
                AND id_usuario =  ' . $usuario->id_usuario;
        $r = $conexao->query($sql);
        $usuario->token_login = $token;

        return $usuario;
    }

    public static function novaSessao($email, $senha)
    {
        $conexao = MySQL::conexao();
        $sql = 'SELECT token_login
                FROM usuarios 
                WHERE status_usuario = "ATIVO" 
                AND email =  "' . $email . '"
                AND senha =  "' . $senha . '"';
        $r = $conexao->query($sql);

        $usuario = new Usuario();
        $usuario->erro = false;
        $usuario->token_login = Usuario::getToken(10);

        //Seta o novo token da sessão
        $_SESSION["dados_usuario"]->token_login = Usuario::getToken(10);

        return $usuario;
    }

    //Gera um token do login para o usuário
    public static function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[rand(0, $max - 1)];
        }

        return $token;
    }

    public static function perfilUsuario()
    {
        $conexao = MySQL::conexao();

        $sql = 'SELECT '
            . ' usuarios.id_usuario, '
            . ' usuarios.nome, '
            . ' usuarios.email, '
            . ' usuarios.senha, '
            . ' usuarios.opcoes_acesso, '
            . ' usuarios.status_usuario, '
            . ' usuarios.id_empresa_visitante, '
            . ' usuarios.nivel_acesso, '
            . ' usuarios.id_cliente, '
            . ' clientes.razao nome_cliente, '
            . ' (CASE '
            . '    WHEN usuarios.nivel_acesso = 0 THEN "ADMINISTRADOR" '
            . '    WHEN usuarios.nivel_acesso = 1 THEN "USUARIO" '
            . '    WHEN usuarios.nivel_acesso = 2 THEN "CLIENTE" '
            . '    WHEN usuarios.nivel_acesso = 3 THEN "FUNCIONARIO" '
            . '    WHEN usuarios.nivel_acesso = 4 THEN "VISITANTE" END) AS acesso '
            . '    FROM usuarios, clientes '
            . ' WHERE usuarios.id_cliente = clientes.id_cliente '
            . '    AND status_usuario = "ATIVO" ';

        $sql .= ' AND id_usuario =  ' . $_SESSION["dados_usuario"]->getUsuario();
        $sql .= ($_SESSION["dados_usuario"]->getNivel_acesso() > 0) ? ' AND nivel_acesso > 0 ' : '';

        $resultado = $conexao->query($sql);
        $usuario = new Usuario();
        $usuario->erro = false;

        if ($resultado->num_rows) {
            $l = $conexao->fetch($resultado);
            $usuario->setId_usuario($l['id_usuario']);
            $usuario->setNome($l['nome']);
            $usuario->setSenha($l['nome']);
            $usuario->setEmail($l['email']);
            $usuario->setNivel_acesso($l['nivel_acesso']);
            $usuario->setId_empresa_visitante($l['id_empresa_visitante']);
            $usuario->setId_cliente($l['id_cliente']);
            $usuario->setNome_cliente($l['nome_cliente']);
            $usuario->setOpcoes_acesso($l['opcoes_acesso']);
            $usuario->setAcesso($l['acesso']);
            $usuario->setStatus_usuario($l['status_usuario']);
        } else {
            $usuario->erro = true;
            $usuario->mensagem = 'Nenhum usuario cadastrado.';
        }

        return $usuario;
    }

    /*
     * Efetua Logout no sistema e libera para que o usuário possa se logar em outro local
     */

    public static function efetuaLogout()
    {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE usuarios SET 
                    token_login = "", IP_login = ""                    
                WHERE id_cliente = ' . $_SESSION["dados_usuario"]->getCliente_usuario() . '
                      AND id_usuario = ' . $_SESSION["dados_usuario"]->getUsuario() . '
                      AND email = "' . $_SESSION["dados_usuario"]->getEmail() . '"';
        $conexao->query($sql);

        return true;
    }

    public static function validaToken()
    {
        /*
         * Se a sessão não foi iniciada ou houve tentativa de acessar uma página sem antes logar
         */
        if (isset($_SESSION["dados_usuario"])) {

            //Não verifica acesso para usuários administradores
            if ($_SESSION["dados_usuario"]->getNivel_acesso() === ADMINISTRADOR) {
                return true;
            }

            $conexao = MySQL::conexao();

            $sql = 'SELECT token_login, nivel_acesso FROM usuarios 
                WHERE status_usuario = "ATIVO" 
                AND id_usuario =  ' . $_SESSION["dados_usuario"]->getUsuario() . '
                AND email =  "' . $_SESSION["dados_usuario"]->getEmail() . '"
                AND token_login =  "' . $_SESSION["dados_usuario"]->getToken_login() . '"';
            try {
                $resultado = $conexao->query($sql);
            } catch (\Exception $e) {
                return false;
            }

            if ($resultado->num_rows) {
                return true;
            }
        }

        //Abre a modal de fim de sessão
        session_destroy();
        location(APP_HTTP . 'pagina_fim_sessao.html');
    }

    /*
     * Usado pelo administrador para finalizar a sessão de um usuário logado
     */
    public static function finalizaSessao($id_usuario)
    {
        $conexao = MySQL::conexao();
        $sql = 'UPDATE usuarios SET 
                    token_login = ""
                WHERE id_usuario = ' . $id_usuario;
        $conexao->query($sql);

        return true;
    }

    function getId_usuario()
    {
        return $this->id_usuario;
    }

    function getNome()
    {
        return $this->nome;
    }

    function getEmail()
    {
        return $this->email;
    }

    function getSenha()
    {
        return $this->senha;
    }

    function getNivel_acesso()
    {
        return $this->nivel_acesso;
    }

    public function getId_empresa_visitante()
    {
        return $this->id_empresa_visitante;
    }

    function getOpcoes_acesso()
    {
        return $this->opcoes_acesso;
    }

    function getStatus_usuario()
    {
        return $this->status_usuario;
    }

    function getId_cliente()
    {
        return $this->id_cliente;
    }

    function getNome_cliente()
    {
        return $this->nome_cliente;
    }

    function getAcesso()
    {
        return $this->acesso;
    }

    function getData_login()
    {
        return $this->data_login;
    }

    function getIP_login()
    {
        return $this->IP_login;
    }

    function getToken_login()
    {
        return $this->token_login;
    }

    function setId_usuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    function setNome($nome)
    {
        $this->nome = $nome;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function setSenha($senha)
    {
        $this->senha = $senha;
    }

    function setNivel_acesso($nivel_acesso)
    {
        $this->nivel_acesso = $nivel_acesso;
    }

    public function setId_empresa_visitante($id_empresa_visitante)
    {
        $this->id_empresa_visitante = $id_empresa_visitante;

        return $this;
    }

    function setOpcoes_acesso($opcoes_acesso)
    {
        $this->opcoes_acesso = $opcoes_acesso;
    }

    function setStatus_usuario($status_usuario)
    {
        $this->status_usuario = $status_usuario;
    }

    function setId_cliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;
    }

    function setNome_cliente($nome_cliente)
    {
        $this->nome_cliente = $nome_cliente;
    }

    function setAcesso($acesso)
    {
        $this->acesso = $acesso;
    }

    function setData_login($data_login)
    {
        $this->data_login = $data_login;
    }

    function setIP_login($IP_login)
    {
        $this->IP_login = $IP_login;
    }

    function setToken_login($token_login)
    {
        $this->token_login = $token_login;
    }

    public function getNome_empresa_visitante()
    {
        return $this->nome_empresa_visitante;
    }

    public function setNome_empresa_visitante($nome_empresa_visitante)
    {
        $this->nome_empresa_visitante = $nome_empresa_visitante;

        return $this;
    }
}
