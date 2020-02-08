<?php
include '../../inicia.php';

$parametros = [
    'service' => SITE . 'core/seguranca/service.php',
];
$query_string = http_build_query($parametros);
$url_cas = Autenticacao::$urlCas . '?' . $query_string;
$url_cas = Autenticacao::$urlCas;

if (strpos($_GET['msg'], 'Acesso negado') !== false) {
    $expirou = false;
} else {
    $expirou = true;
}

$template = new templates\TemplateBootstrap4pikeAdmin(['acesso_impedido.css'], ['acesso_impedido.js']);
?>

<?php $template->inicioConteudo() ?>
<div class="erro-inline">
    <?= e($_GET['msg']) ?>
</div>
<?php if ($expirou): ?>
    <div class="botoes">
        <?php location(SITE . 'core/seguranca/entrar.php'); ?>
    </div>
<?php endif; ?>
<?php $template->fimConteudo() ?>
<?php $template->renderiza() ?>