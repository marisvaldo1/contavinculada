<?php
include '../../inicia.php';
$curl = \http\Curl::fabrica();
$json = $curl->exec('http://api.postmon.com.br/v1/cep/' . $_GET['cep']);
echo $json;