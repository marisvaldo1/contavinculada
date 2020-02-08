<?php
include '../../../inicia.php';

modelo\Usuario::validaToken();

include 'usuario.html.php';
