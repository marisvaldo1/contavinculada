<?php

namespace templates;

class TemplateBootstrapSemLayout
{

    private $arquivo;
    private $css;
    private $js;
    private $cssInline;
    private $jsInline;
    private $breadCrumb;
    private $conteudo;
    private $titulo;
    private $nomeSistema;
    private $nomeAmbiente;
    private $mostrarAmbiente;

    function __construct($css = [], $js = [])
    {
        $nomes_ambientes = [
            'D' => 'Desenvolvimento',
            'H' => 'Homologação',
            'P' => 'Produção',
        ];
        $this->setTitulo(\Sistema::$nome);
        $this->nomeSistema = \Sistema::$nome . ' ' . \Sistema::$versao;
        $this->nomeAmbiente = $nomes_ambientes[\Sistema::$ambiente];
        $this->mostrarAmbiente = \Sistema::$mostrar_ambiente;
        define('CSSJSV', \Sistema::$versao_css_js);
        define('TEMPLATE_HTTP', SITE . 'core/templates/bootstrapcorreios.css/');
        $this->arquivo = RAIZ . 'core/templates/bootstrapcorreios.css/semLayout.php';
        define('TEMPLATE_HTTP_IMG', TEMPLATE_HTTP . 'img/');
        define('TEMPLATE_HTTP_CSS', TEMPLATE_HTTP . 'css/');
        define('TEMPLATE_HTTP_JS', TEMPLATE_HTTP . 'js/');
        define('ARQUIVO_CSS', 'style.css');
        $this->setCss($css);
        $this->setJs($js);
    }

    public static function paginaExcecao($ex)
    {
        if (AMBIENTE == 'P') {
            $detalha = false;
        } else {
            $detalha = true;
        }
        include RAIZ . 'core/templates/v2/html/excecao.html.php';
    }

    public static function paginaExcecaoAjaxHTML(\Exception $ex)
    {
        if (AMBIENTE == 'P') {
            $detalha = false;
        } else {
            $detalha = true;
        }
        $mensagem = $ex->getMessage();
        include RAIZ . 'core/templates/g/html/excecao_ajax.html.php';
    }

    public static function paginaExcecaoAjaxJSON(\Exception $ex)
    {
        $mensagem = $ex->getMessage();
        echo json_encode(['erro' => true, 'mensagem' => $ex->getMessage()]);
    }

    public static function options($array, $selected = null)
    {
        foreach ($array as $k => $v) {
            echo '<option value="' . e($k) . '" ' . ($k == $selected ? 'selected' : '') . '>' . e($v) . '</option>';
        }
    }

    public static function sel($trechoUrl)
    {
        return strpos($_SERVER['REQUEST_URI'], $trechoUrl) !== false ? 'sel' : '';
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getNomeSistema()
    {
        return $this->nomeSistema;
    }

    public function setNomeSistema($nomeSistema)
    {
        $this->nomeSistema = $nomeSistema;
    }

    public function getNomeAmbiente()
    {
        return $this->nomeAmbiente;
    }

    public function setNomeAmbiente($nomeAmbiente)
    {
        $this->nomeAmbiente = $nomeAmbiente;
    }

    public function getMostrarAmbiente()
    {
        return $this->mostrarAmbiente;
    }

    public function setMostrarAmbiente($mostrarAmbiente)
    {
        $this->mostrarAmbiente = $mostrarAmbiente;
    }

    public function getCss()
    {
        foreach ($this->css as $css) {
            ?>
            <link rel="stylesheet" type="text/css" href="<?= $css ?>">
            <?php
        }
    }

    function setCss($css)
    {
        $this->css = $css;
    }

    public function getJs()
    {
        foreach ($this->js as $js):
            ?>
            <script src="<?= $js ?>?<?= CSSJSV ?>"></script>
            <?php
        endforeach;
    }

    function setJs($js)
    {
        $this->js = $js;
    }

    public function inicioBreadCrumb()
    {
        ob_start();
    }

    public function fimBreadCrumb()
    {
        $this->breadCrumb = ob_get_clean();
    }

    public function getBreadCrumb()
    {
        return $this->breadCrumb;
    }

    public function inicioConteudo()
    {
        ob_start();
    }

    public function fimConteudo()
    {
        $this->conteudo = ob_get_clean();
    }

    public function getConteudo()
    {
        return $this->conteudo;
    }

    public function inicioCss()
    {
        ob_start();
    }

    public function fimCss()
    {
        $this->cssInline = ob_get_clean();
    }

    public function getCssInline()
    {
        return $this->cssInline;
    }

    public function inicioJs()
    {
        ob_start();
    }

    public function fimJs()
    {
        $this->jsInline = ob_get_clean();
    }

    public function getJsInline()
    {
        return $this->jsInline;
    }

    public function renderiza()
    {
        ob_clean();
        ob_start('ob_gzhandler');
        include($this->arquivo);
    }

    public function menu($menu)
    {
        if ($menu) {
            ob_start();
            foreach ($menu as $m) {
                if ($m['url']) {
                    ?>
                    <a href="<?= SITE ?><?= $m['url'] ?>"><?= $m['nome'] ?></a>
                    <?php
                } else {
                    ?>
                    <a><?= $m['nome'] ?></a>
                    <div class="submenu">
                        <?= self::menu($m['menus']) ?>
                    </div>
                    <?php
                }
            }
            $menu = ob_get_clean();
            return $menu;
        }
    }
}
