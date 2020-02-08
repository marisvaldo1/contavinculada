<?php

namespace templates;

class TemplateBootstrap4pikeAdmin {

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

    function __construct($css = [], $js = []) {
        $nomes_ambientes = [
            'D' => 'Desenvolvimento',
            'H' => 'Homologação',
            'P' => 'Produção',
        ];
        $this->setTitulo(\Sistema::$nome);
        $this->nomeSistema = \Sistema::$nome . ' ' . \Sistema::$versao;
        $this->nomeAmbiente = $nomes_ambientes[\Sistema::$ambiente];
        $this->mostrarAmbiente = \Sistema::$mostrar_ambiente;
        //define('CSSJSV', \Sistema::$versao_css_js);
        define('TEMPLATE_HTTP', SITE . 'core/templates/bootstrap4pikeAdmin/');
        $this->arquivo = RAIZ . 'core/templates/bootstrap4pikeAdmin/padrao.php';
        //dd(SITE . 'static/img/');

        define('TEMPLATE_HTTP_IMG', SITE . 'static/img/');
        define('TEMPLATE_HTTP_CSS', SITE . 'static/css/');
        define('TEMPLATE_HTTP_JS', SITE . 'static/js/');
        define('ARQUIVO_CSS', 'style.css');
        $this->setCss($css);
        $this->setJs($js);
    }

    public static function paginaExcecao($ex) {
        if (AMBIENTE == 'P') {
            $detalha = false;
        } else {
            $detalha = true;
        }
        include RAIZ . 'core/templates/bootstrap4pikeAdmin/html/excecao.html.php';
    }

    public static function paginaExcecaoAjaxHTML(\Exception $ex) {
        if (AMBIENTE == 'P') {
            $detalha = false;
        } else {
            $detalha = true;
        }
        $mensagem = $ex->getMessage();
        include RAIZ . 'core/templates/g/html/excecao_ajax.html.php';
    }

    public static function paginaExcecaoAjaxJSON(\Exception $ex) {
        $mensagem = $ex->getMessage();
        echo json_encode(['erro' => true, 'mensagem' => $ex->getMessage()]);
    }

    public static function options($array, $selected = null) {
        foreach ($array as $k => $v) {
            echo '<option value="' . e($k) . '" ' . ($k == $selected ? 'selected' : '') . '>' . e($v) . '</option>';
        }
    }

    public static function sel($trechoUrl) {
        return strpos($_SERVER['REQUEST_URI'], $trechoUrl) !== false ? 'sel' : '';
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function getNomeSistema() {
        return $this->nomeSistema;
    }

    public function setNomeSistema($nomeSistema) {
        $this->nomeSistema = $nomeSistema;
    }

    public function getNomeAmbiente() {
        return $this->nomeAmbiente;
    }

    public function setNomeAmbiente($nomeAmbiente) {
        $this->nomeAmbiente = $nomeAmbiente;
    }

    public function getMostrarAmbiente() {
        return $this->mostrarAmbiente;
    }

    public function setMostrarAmbiente($mostrarAmbiente) {
        $this->mostrarAmbiente = $mostrarAmbiente;
    }

    public function getCss() {
        foreach ($this->css as $css) {
            ?>
            <link rel="stylesheet" type="text/css" href="<?= $css ?>">
            <?php
        }
    }

    function setCss($css) {
        $this->css = $css;
    }

    public function getJs() {
        foreach ($this->js as $js):
            ?>
            <script src="<?= $js ?>?<?= CSSJSV ?>"></script>
            <?php
        endforeach;
    }

    function setJs($js) {
        $this->js = $js;
    }

    public function inicioBreadCrumb() {
        ob_start();
    }

    public function fimBreadCrumb() {
        $this->breadCrumb = ob_get_clean();
    }

    public function getBreadCrumb() {
        return $this->breadCrumb;
    }

    public function inicioConteudo() {
        ob_start();
    }

    public function fimConteudo() {
        $this->conteudo = ob_get_clean();
    }

    public function getConteudo() {
        return $this->conteudo;
    }

    public function inicioCss() {
        ob_start();
    }

    public function fimCss() {
        $this->cssInline = ob_get_clean();
    }

    public function getCssInline() {
        return $this->cssInline;
    }

    public function inicioJs() {
        ob_start();
    }

    public function fimJs() {
        $this->jsInline = ob_get_clean();
    }

    public function getJsInline() {
        return $this->jsInline;
    }

    public function renderiza() {
        //ob_clean();
        ob_end_clean();
        ob_start('ob_gzhandler');
        include($this->arquivo);
    }

    public function menu($menu) {
        if ($menu) {
            ob_start();?>
            <?php
            foreach ($menu as $m) {
                if ($m['url']) {?>
                    <li class="nav-item nav-item-right">
                        <a class="nav-link" href="<?= SITE ?><?= $m['url'] ?>">
                            <i class="fa fa-fw fa-cloud"></i>
                            <span class="nav-link-text"><?= $m['nome'] ?></span>
                        </a>
                    </li>
                    <?php
                } else {
                    if (PAGINA == $m['menus'][array_search(PAGINA, array_column($m['menus'], "url"))]['url']) {
                        $menuAtivo = "class='menu-top-active'";
                    } else {
                        $menuAtivo = "class=''";
                    }?>
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><?= $m['nome'] ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li>
                                <?= self::menu($m['menus']) ?>
                                <?php //d(PAGINA); ?>
                                <?php //d(array_search(PAGINA, array_column($menu, "url"))); ?>
                                <?php //d($m['menus'][array_search(PAGINA, array_column($m['menus'], "url"))]['url']); ?>
                                <?php //d(array_search(PAGINA, array_column($menu["menus"], "url"))); ?>
                            </li>
                        </ul>

                    </li>
                    <?php
                }
            }
            $menu = ob_get_clean();
            return $menu;
        }
    }
}
