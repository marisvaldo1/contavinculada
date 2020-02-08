<?php
include '../../../inicia.php';

modelo\Usuario::validaToken();

include 'lancamento.html.php';
