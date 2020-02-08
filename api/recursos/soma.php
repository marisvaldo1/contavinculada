<?php
namespace api;

class Soma extends recurso
{
    public function get()
    {
        $soma = 0;
        foreach ($this->q as $v) {
            $soma += $v;
        }
        $this->json(['soma' => $soma]);
    }

    public function post()
    {
        Aut::filtraPerfil('MASTER', 'INTERNAUTA');
        $soma = 0;
        foreach ($this->input as $v) {
            $soma += $v;
        }
        $this->json(['soma' => $soma]);
    }

    public function put()
    {
        $soma = 0;
        foreach ($this->input as $v) {
            $soma += $v;
        }
        $this->json(['soma' => $soma]);
    }

    public function delete()
    {
        // TODO: Implement delete() method.
    }
}