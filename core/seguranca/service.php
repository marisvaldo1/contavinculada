<?php
include '../../inicia.php';
try {
    AutenticacaoExterna::validaUsuario();
} catch (Exception $ex) {
    dd($ex);
}
$url_solicitante = $_SESSION['url_solicitante'] ? $_SESSION['url_solicitante'] : '../../index.php';
$template = new \templates\TemplateBootstrap4SBAdminCorreios(['index.css'], ['index.js']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrando...</title>
</head>
<body>
<script>
  location = '../../app/';
</script>
</body>
</html>