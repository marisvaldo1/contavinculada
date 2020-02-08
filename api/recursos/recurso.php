<?php
namespace api;

abstract class Recurso
{
    const MASTER = 'MASTER';

    protected $metodo;
    protected $input;
    /**
     * @var Usuario
     */
    protected $usuario;
    /**
     * @var array
     */
    protected $q;

    /**
     * Recurso constructor.
     */
    public function __construct()
    {
        $this->metodo = $_SERVER['REQUEST_METHOD'];
        $this->input = input();
        $this->q = $GLOBALS['q'];
        $this->{$this->metodo}();
    }

    public abstract function get();

    public abstract function post();

    public abstract function put();

    public abstract function delete();

    public function json($obj)
    {
        echo json_encode($obj, JSON_PRETTY_PRINT);
    }
}