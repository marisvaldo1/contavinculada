<?php
include '../../inicia.php';

modelo\Usuario::validaToken();

include 'perfil_usuario.html.php';
