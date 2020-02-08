<?php
include '../../../inicia.php';

modelo\Usuario::validaToken();

include 'logacesso.html.php';
