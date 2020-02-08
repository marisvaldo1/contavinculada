<?php
include '../../../inicia.php';

modelo\Usuario::validaToken();

include 'cliente.html.php';
