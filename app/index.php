<?php
include '../inicia.php';

//Autenticacao::filtraLogado(Autenticacao::VIEW_HTML);
Autenticacao::filtraLogado();

location(SITE . 'app/inicio');
