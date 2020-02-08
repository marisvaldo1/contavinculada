
<?php
include '../../inicia.php';

//Libera o usuário para efetuar login em outra sessão do sistema
use modelo\Usuario;
Usuario::efetuaLogout();

session_destroy();
?>

<script >
    iniciaVariaveis();
    ls.save(contaVinculada);
</script>

<?php
    location(APP_HTTP . 'pagina_fim_sessao.html');
?>