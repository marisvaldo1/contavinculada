<?php $template = new \templates\TemplateBootstrap4pikeAdmin() ?>

<?php $template->inicioCss() ?>
    <style>
        .erro {
            background: #f2dede;
            width: calc(100vw - 2rem);
            padding: 1rem;
            margin: auto;
            overflow: auto;
            border: 1px solid #ebccd1;
            color: #a94442;
        }

        p {
            margin: 1rem;
        }
    </style>
<?php $template->fimCss() ?>

<?php $template->inicioBreadCrumb() ?>
    Erro
<?php $template->fimBreadCrumb() ?>

<?php $template->inicioConteudo() ?>

    <div class="erro">
        <?= $ex->getMessage() ?>
    </div>

    <p>Detalhes</p>

    <div class="erro">
        <?php
        d($ex);
        ?>
    </div>

<?php $template->fimConteudo() ?>

<?php $template->renderiza() ?>