<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="mobile-web-app-capable" content="yes">        
        <meta name="apple-mobile-web-app-capable" content="yes">        
        <link rel="shortcut icon" sizes="64x64" href="<?= TEMPLATE_HTTP ?>img/favi-ect.png">
        <title><?= Sistema::$nome ?></title>
        <!-- ANIMATE CSS  -->
        <link href="<?= LIB_HTTP ?>animate/css/animate.min.css" rel="stylesheet" />
        <!-- COLORBOX  -->
        <link href="<?= LIB_HTTP ?>colorbox/css/colorbox4.css" rel="stylesheet" />
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="<?= LIB_HTTP ?>bootstrap/css/cosmo/bootstrap.css" rel="stylesheet" />
        <!-- BOOTSTRAP DATA-TABLES STYLE  -->
        <link href="<?= LIB_HTTP ?>bootstrap/css/dataTables.bootstrap.min.css" rel="stylesheet" />
        <!-- BOOTSTRAP RESPONSIVE -->
        <link href="<?= LIB_HTTP ?>bootstrap/css/responsive.bootstrap.min.css" rel="stylesheet" />
        <!-- BOOTSTRAP CORREIOS CUSTOMIZACAO  -->
        <!--<link href="<?= LIB_HTTP ?>bootstrap/css/bootstrap-theme-correios.css" rel="stylesheet" />-->
        <!-- FONT AWESOME ICONS  -->
        <link href="<?= LIB_HTTP ?>bootstrap/css/font-awesome.css" rel="stylesheet" />
        <link type="text/css" rel="stylesheet" href="<?= TEMPLATE_HTTP ?>css/style.css?<?= CSSJSV ?>">
        <link type="text/css" rel="stylesheet" href="<?= TEMPLATE_HTTP ?>css/bootstrapcorreios.css?<?= CSSJSV ?>">
        <?php $this->getCss() ?>
        <?= $this->getCssInline() ?>
    </head>
    <body class="sem-layout">
<!--        <header>
            <div class="titulo-sistema">
                <?= e(Sistema::$nome . ' ' . Sistema::$versao) ?>
            </div>
            <div class="separador"></div>
            <a id="logo" href="<?= SITE ?>app/index.php"></a>
        </header>-->
        <?php //include RAIZ . 'core/templates/bootstrapcorreios.css/inc/header-fixo.php' ?>
        <?php  //include RAIZ . 'core/templates/bootstrapcorreios.css/inc/menu.php' ?>
<!--        <section id="breadcrumb">
            <?= $this->getBreadCrumb() ?>
        </section>-->
        <!--<section id="geral">-->
            <!--<div id="msg"></div>-->
            <!--<div id="corpo">-->
                <?= $this->getConteudo() ?>
            <!--</div>-->
        <!--</section>-->
        <!--<div class="vidro" onclick="modal.fecha()"></div>-->
<!--        <div id="alerta">
            <div></div>
            <a></a>
        </div>-->
        <script>
            var SITE = '<?= SITE ?>';
        </script>
        <!-- CORE JQUERY SCRIPTS -->
        <!-- CORE JQUERY SCRIPTS -->
        <script src="<?= LIB_HTTP ?>jquery/js/jquery-1.12.0.min.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="<?= LIB_HTTP ?>bootstrap/js/bootstrap.min.js"></script>
        <!-- VALIDATE BOOTSTRAP  -->
        <script src="<?= LIB_HTTP ?>bootstrap/js/validator.js"></script>
        <!-- CONFIRMATION BOOTSTRAP  -->
        <script src="<?= LIB_HTTP ?>bootstrap/js/bootstrap-confirmation.min.js"></script>
        <!-- JQUERY DATA-TABLES -->
        <script src="<?= LIB_HTTP ?>jquery/js/jquery.dataTables.min.js"></script>
        <!-- BOOTSTRAP DATA-TABLES  -->
        <script src="<?= LIB_HTTP ?>bootstrap/js/dataTables.bootstrap.min.js"></script>
        <!-- RESPONSIVE DATA-TABLES  -->
        <script src="<?= LIB_HTTP ?>jquery/js/dataTables.responsive.min.js"></script>
        <!-- JQUERY MASKINPUT -->
        <script src="<?= LIB_HTTP ?>jquery/js/jquery.maskedinput.min.js"></script>
        <!-- COLORBOX SCRIPTS  -->
        <script src="<?= LIB_HTTP ?>colorbox/js/jquery.colorbox-min.js"></script>
        <!-- ??? SCRIPTS  -->
        <script src="<?= SITE ?>core/js/core.js?<?= CSSJSV ?>"></script>
        <!--<script src="<?= TEMPLATE_HTTP ?>js/bootstrapcorreios.css.js?<?= CSSJSV ?>"></script>-->
        <script src="<?= TEMPLATE_HTTP ?>js/formulario.js?<?= CSSJSV ?>"></script>
        <script src="<?= TEMPLATE_HTTP ?>js/scripts.js"></script>
        <?php $this->getJs() ?>
        <?= $this->getJsInline() ?>
    </body>
<!-- Construído em <?= delta_t() ?> segundos.-->
</html>