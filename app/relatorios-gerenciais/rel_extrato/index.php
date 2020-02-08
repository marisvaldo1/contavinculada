<?php
include '../../../inicia.php';

modelo\Usuario::validaToken();

include 'rel_extrato.html.php';
