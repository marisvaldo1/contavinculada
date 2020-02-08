<?php
/**
 * Created by PhpStorm.
 * User: roger
 * Date: 24/08/2018
 * Time: 10:32
 */

namespace componentes;


class AtendimentoException extends \Exception
{
    private $json;

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @param mixed $json
     */
    public function setJson($json)
    {
        $this->json = json_decode($json);
    }



}