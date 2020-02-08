<?php
include '../../../inicia.php';

modelo\Usuario::validaToken();

include 'extrato.html.php';
