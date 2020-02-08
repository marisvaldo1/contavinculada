<?php $usuario = Autenticacao::usuario(); ?>

<?php /* @var $this Template */ ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title><?= Sistema::$nome ?></title>
        <meta name="description" content="Free Bootstrap 4 Admin Theme | Pike Admin">
        <meta name="author" content="Pike Web Development - https://www.pikephp.com">

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?= TEMPLATE_HTTP_IMG ?>favicon.ico">

        <!-- Switchery css -->
        <link href="<?= LIB_HTTP ?>switchery/switchery.min.css" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="<?= TEMPLATE_HTTP_CSS ?>bootstrap.min.css" rel="stylesheet" type="text/css"/>

        <!-- Font Awesome CSS -->
        <link href="<?= LIB_HTTP ?>font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>

        <!-- DATEPICKER BOOTSTRAP  -->
        <link href="<?= LIB_HTTP ?>datepicker/1.7.1/bootstrap-datepicker.css" rel="stylesheet"/>

        <!-- MONTHPICKER BOOTSTRAP  -->
        <link href="<?= LIB_HTTP ?>monthpicker/monthpicker.css" rel="stylesheet"/>

        <!-- Custom CSS -->
        <link href="<?= TEMPLATE_HTTP_CSS ?><?= ARQUIVO_CSS ?>" rel="stylesheet" type="text/css"/>

        <!-- ANIMATE CSS  -->
        <link href="<?= LIB_HTTP ?>animate/3.5.1/css/animate.min.css" rel="stylesheet"/>

        <!-- COLORBOX  -->
        <link href="<?= LIB_HTTP ?>colorbox/1.5.9/css/colorbox4.css" rel="stylesheet"/>

        <!-- BEGIN CSS for this page -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css"/>
        <!-- END CSS for this page -->

        <!--Nova Modal-->
        <link href="<?= LIB_HTTP ?>jquery-ui-1.12.1.custom/ui.dialog.css" rel="stylesheet" type="text/css"/>
        <link href="<?= LIB_HTTP ?>jquery-ui-1.12.1.custom/ui.resizable.css" rel="stylesheet" type="text/css"/>
        <!--Nova Modal-->

        <!--MultSelect-->
        <!--<link href="<?= LIB_HTTP ?>jquery-jselect/jSelect.css" rel="stylesheet" type="text/css"/>-->
        <!--MultSelect-->

        <?php $this->getCss() ?>
        <?= $this->getCssInline() ?>

        <!--Envias as constantes definidas no PHP para o JavaScript -->
        <script>

            //Níveis de acesso
            var ADMINISTRADOR = 0;
            var USUARIO       = 1;
            var CLIENTE       = 2;
            var VISITANTE     = 4;

            var SITE = '<?= SITE ?>';
            var APP_HTTP = '<?= APP_HTTP ?>';
            var URL_ATUAL = '<?= URL_ATUAL ?>';
            var APP = '<?= APP ?>';

            var NIVEL_ACESSO = <?= $_SESSION['dados_usuario']->getNivel_acesso(); ?>;
            var USUARIO_VISITANTE = NIVEL_ACESSO === 4;

            if(USUARIO_VISITANTE){
                var EMPRESA_VISITANTE = <?= $_SESSION["dados_usuario"]->getId_empresa_visitante(); ?>;
                var NOME_EMPRESA_VISITANTE = '<?= $_SESSION['dados_usuario']->getNome_empresa_visitante(); ?>';
            }

        </script>

    </head>

    <body class="adminbody">

        <div id="main">

            <!-- top bar navigation -->
            <?php include RAIZ . 'core/templates/bootstrap4pikeAdmin/inc/top-bar-navigation.php' ?>
            <!-- End Navigation -->

            <!-- Left Sidebar -->
            <?php include RAIZ . 'core/templates/bootstrap4pikeAdmin/inc/left-main-sidebar.php' ?>
            <!-- End Sidebar -->

            <div class="content-page">

                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">
                        <?php echo $this->getConteudo(); ?>
                    </div>
                </div>
                <!-- END container-fluid -->

            </div>
            <!-- END content -->

        </div>
        <!-- END content-page -->

        <?php include RAIZ . 'core/templates/bootstrap4pikeAdmin/inc/footer.php' ?>

        <script src="<?= TEMPLATE_HTTP_JS ?>modernizr.min.js"></script>
        <script src="<?= TEMPLATE_HTTP_JS ?>jquery.min.js"></script>
        <script src="<?= TEMPLATE_HTTP_JS ?>moment.min.js"></script>

        <!-- Nova modal -->
        <script src="<?= LIB_HTTP ?>jquery-ui-1.12.1.custom/jquery-ui.js"></script>
        <!-- Nova modal -->

        <script src="<?= TEMPLATE_HTTP_JS ?>popper.min.js"></script>
        <script src="<?= TEMPLATE_HTTP_JS ?>bootstrap.min.js"></script>

        <script src="<?= TEMPLATE_HTTP_JS ?>detect.js"></script>
        <script src="<?= TEMPLATE_HTTP_JS ?>fastclick.js"></script>
        <script src="<?= TEMPLATE_HTTP_JS ?>jquery.blockUI.js"></script>
        <script src="<?= TEMPLATE_HTTP_JS ?>jquery.nicescroll.js"></script>

        <script src="<?= TEMPLATE_HTTP_JS ?>jquery.scrollTo.min.js"></script>
        <script src="<?= LIB_HTTP ?>switchery/switchery.min.js"></script>


        <!-- App js -->
        <script src="<?= TEMPLATE_HTTP_JS ?>pikeadmin.js"></script>

        <!-- BEGIN Java Script for this page -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <!-- <script src="<?= LIB_HTTP ?>chart/Chart.min.js"></script>
        <script src="<?= LIB_HTTP ?>datatables/jquery.dataTables.min.js"></script>
        <script src="<?= LIB_HTTP ?>datatables/dataTables.bootstrap4.min.js"></script> -->

        <!-- Counter-Up-->
        <script src="<?= LIB_HTTP ?>waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="<?= LIB_HTTP ?>counterup/jquery.counterup.min.js"></script>

        <!-- CONFIRMATION BOOTSTRAP  -->
        <script src="<?= LIB_HTTP ?>bootstrap-confirmation-4.0.1/bootstrap-confirmation.min.js"></script>

        <!-- BOOTSTRAP NOTIFY  -->
        <script src="<?= LIB_HTTP ?>bootstrap-notify/bootstrap-notify.js"></script>

        <!-- VALIDATE BOOTSTRAP  -->
        <script src="<?= LIB_HTTP ?>bootstrap-validator/validator.js"></script>

        <!-- JQUERY MASKINPUT -->
        <script src="<?= LIB_HTTP ?>jquery-mask/jquery.mask.min.js"></script>

        <!-- jQuery MaskMoney Plugin -->
        <script src="<?= LIB_HTTP ?>jquery-maskMoney/jquery.maskMoney.min.js"></script>

        <!-- DATEPICKER BOOTSTRAP  -->
        <script src="<?= LIB_HTTP ?>datepicker/1.7.1/bootstrap-datepicker.js"></script>
        <script src="<?= LIB_HTTP ?>datepicker/1.7.1/locales/bootstrap-datepicker.pt-BR.js"></script>

        <!-- MONTHPICKER BOOTSTRAP  -->
        <script src="<?= LIB_HTTP ?>monthpicker/monthpicker.min.js"></script>

        <!-- COLORBOX SCRIPTS  -->
        <script src="<?= LIB_HTTP ?>colorbox/1.5.9/js/jquery.colorbox-min.js"></script>

        <!-- ??? SCRIPTS  -->
        <script src="<?= SITE ?>core/js/core.js?<?= CSSJSV ?>"></script>
        <script src="<?= APP_FILES ?>/js/global.js"></script>
        <?php $this->getJs() ?>
        <?= $this->getJsInline() ?>
        <!-- END Java Script for this page -->

    </body>
</html>