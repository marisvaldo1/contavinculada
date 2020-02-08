<?php
include '../../../inicia.php';

modelo\Usuario::validaToken();

include 'empresa.html.php';
