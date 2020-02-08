<?php

/*
 *  http://www.upinside.com.br/oferta/php-orientado-a-objetos/?pages=video&key=83963700

 */

class Sistema
{
    /**
     *
     * @var string Nome do sistema.
     */
    public static $nome;

    /**
     *
     * @var string Sigla do sistema.
     */
    public static $sigla;

    /**
     * @var string Nome do aplicativo para ser usado para identificação única, por exemplo no servidor Redis.
     */
    public static $app;

    /**
     *
     * @var string Versão do sistema.
     */
    public static $versao;

    /**
     *
     * @var string Ambiente do sistema. Deve conter exatamente D, H ou P.
     */
    public static $ambiente;

    /**
     * @var string|false Ambiente oracle do sistema. Deve conter exatamente D, H ou P. Em caso de false, usa a variável
     *do ambiente.
     */
    public static $ambiente_oracle;

    /** Mostra ou não o ambiente. Utilizado pelos templates para representar
     * visualmente o ambiente
     *
     * @var type
     */
    public static $mostrar_ambiente;

    /**
     *
     * @var string Versão atual de todos os arquivos JS e CSS.
     * Ideal para limpar cache dos navegadores quando há publicação de novos códigos CSS e JS.
     * Templates devem ser implementados levando em consideração esse atributo.
     */
    public static $versao_css_js;

    /**Se habilitada, indica para o template que deve utilizar as versõs minimizadas CSS e JS disponíveis do template.
     *
     * @var boolean
     */
    public static $compressao;

    /**Se true, modifica ligeiramente alguns estilos e comportamentos do sistema para se adequar melhor ao layout dos
     * Correios. Aplica modificações complementares em cascata e não é essencial para o funcionamento da aplicação.
     *
     * @var boolean
     */
    public static $layout_ect;

    /**
     * @var string
     */
    public static $lib_ext;
    public static $lib_ext2;

    public static function inicia()
    {
        $config = include(RAIZ . 'config/sistema.php');
        define('AMBIENTE', $config['ambiente']);
        //define('APP', $config['app']);
        self::$nome = $config['nome'];
        self::$sigla = $config['sigla'];
        self::$app = $config['app'];
        self::$versao = $config['versao'];
        self::$ambiente = $config['ambiente'];
        //self::$mostrar_ambiente = $config['mostrar_ambiente'];
        self::$versao_css_js = $config['versao_css_js'];
        self::$layout_ect = $config['layout_ect'];
        self::$lib_ext = $config['lib_ext'];
        self::$lib_ext2 = $config['lib_ext2'];
        //self::$compressao = false;
        define('CSSJSV', \Sistema::$versao_css_js);
        //define('CDN', $config['cdn']);
    }

    public static function propriedades()
    {
        return (new \ReflectionClass(__CLASS__))->getStaticProperties();
    }

    public static function trataExcecao(\Exception $ex)
    {
        $mensagem = $ex->getMessage();
        include RAIZ . 'app/erro/index.html.php';
    }

}
