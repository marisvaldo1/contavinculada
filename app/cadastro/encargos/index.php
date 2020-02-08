<?php
include '../../../inicia.php';

modelo\Usuario::validaToken();

include 'encargo.html.php';
