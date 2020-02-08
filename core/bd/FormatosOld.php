<?php

namespace bd;

class FormatosOld
{

    const BOOLEANO = 1;
    const INTEIRO = 2;
    const REAL = 3;
    const TEXTO = 4;
    const DATA = 5;
    const DATA_HORA = 6;
    const MOEDA = 7;
    const CEP = 8;
    const MATRICULA = 9;
    const CPF = 10;
    const FT = 11;
    const TELEFONE = 12;
    const CNPJ = 13;

    private $algoritmos;

    public function __construct()
    {
        $this->algoritmos[self::MOEDA] = [];
        $this->algoritmos[self::MOEDA]['bd'] = function ($dado) {
            if (!contem('.', $dado) || !contem(',', $dado)) {
                $dado = str_replace(',', '.', $dado);
            } else {
                $dado = str_replace(['.', ','], ['', '.'], $dado);
            }
            $dado = filter_var($dado, FILTER_VALIDATE_FLOAT);
            if (!is_bool($dado)) {
                return $dado;
            }
        };
        $this->algoritmos[self::MOEDA]['app'] = function ($dado) {
            $dado = filter_var(str_replace(',', '', $dado), FILTER_VALIDATE_FLOAT);
            return number_format($dado, 2, ',', '.');
        };

        $this->algoritmos[self::BOOLEANO] = [];
        $this->algoritmos[self::BOOLEANO]['bd'] = function ($dado) {
            return $dado ? 1 : 0;
        };
        $this->algoritmos[self::BOOLEANO]['app'] = function ($dado) {
            return boolval($dado);
        };

        $this->algoritmos[self::INTEIRO] = [
            'bd' => function ($dado) {
                return filter_var($dado, FILTER_VALIDATE_INT);
            },
            'app' => function ($dado) {
                return filter_var($dado, FILTER_VALIDATE_INT);
            }
        ];

        $this->algoritmos[self::REAL] = [
            'bd' => function ($dado) {
                return filter_var($dado, FILTER_VALIDATE_FLOAT);
            },
            'app' => function ($dado) {
                return filter_var($dado, FILTER_VALIDATE_FLOAT);
            }
        ];

        $this->algoritmos[self::TEXTO] = [
            'bd' => function ($dado) {
                return strval($dado);
            },
            'app' => function ($dado) {
                return strval($dado);
            }
        ];

        $this->algoritmos[self::DATA] = [
            'bd' => function ($data) {
                if (gettype($data) == 'string') {
                    $data = substr($data, 0, 10);
                    if (false !== strpos($data, '/')) {
                        $d = \date_create_from_format('d/m/Y', $data);
                        if ($d && $d->format('d/m/Y') == $data) {
                            return $d->format('Y-m-d');
                        }
                        throw new \Exception('Formato de data inválido.', 3);
                    } else {
                        $d = new \DateTime($data);
                        if ($d && $d->format('Y-m-d') == $data) {
                            return $d->format('Y-m-d');
                        }
                        throw new \Exception('Formato de data inválido.', 4);
                    }
                } elseif (is_object($data) && get_class($data) == 'DateTime') {
                    return $data->format('Y-m-d');
                } else {
                    throw new \Exception('Formato de data inválido.', 44);
                }
                return $data;
            },
            'app' => function ($dado) {
                try {
                    $d = new \DateTime($dado);
                    return $d->format('d/m/Y');
                } catch (\Exception $ex) {
                    throw new \Exception('Formato de data inválido.', 1, $ex);
                }
            }
        ];

        $this->algoritmos[self::DATA_HORA] = [
            'bd' => function ($dataHora) {
                if (gettype($dataHora) == 'string') {
                    if (strlen($dataHora) == 10) {
                        $dataHora .= ' 00:00:00';
                    }
                    if (false !== strpos($dataHora, '/')) {
                        $d = \date_create_from_format('d/m/Y H:i:s', $dataHora);
                        if ($d && $d->format('d/m/Y H:i:s') == $dataHora) {
                            return $d->format('Y-m-d H:i:s');
                        }
                        throw new \Exception('Formato de data/hora inválido.', 5);
                    } else {
                        $d = new \DateTime($dataHora);
                        if ($d && $d->format('Y-m-d H:i:s') == $dataHora) {
                            return $d->format('Y-m-d H:i:s');
                        }
                        throw new \Exception('Formato de data/hora inválido.', 6);
                    }
                } elseif (get_class($dataHora) == 'DateTime') {
                    return $dataHora->format('Y-m-d H:i:s');
                }
                throw new \Exception('Formato de data/hora inválido.', 9, $ex);
            },
            'app' => function ($dataHora) {
                try {
                    $d = new \DateTime($dataHora);
                    return $d->format('d/m/Y H:i:s');
                } catch (\Exception $ex) {
                    throw new \Exception('Formato de data/hora inválido.', 'data_hora_invalida', $ex);
                }
            }
        ];
        $this->algoritmos[self::CEP] = [
            'bd' => function ($dado) {
                if (preg_match('/^[0-9]{5}\-?[0-9]{3}$/', $dado)) {
                    return \str_replace('-', '', $dado);
                } else {
                    throw new \Exception('Formato de CEP inválido.');
                }
            },
            'app' => function ($dado) {
                if (preg_match('/^[0-9]{5}\-?[0-9]{3}$/', $dado)) {
                    $dado = \str_replace('-', '', $dado);
                    return substr($dado, 0, 5) . '-' . substr($dado, -3);
                } else {
                    throw new \Exception('Formato de CEP inválido.');
                }
            }
        ];

        $this->algoritmos[self::MATRICULA] = [
            'bd' => function () {

            },
            'app' => function () {

            }
        ];

        $this->algoritmos[self::CPF] = [];
        $this->algoritmos[self::CPF]['bd'] = function ($dado) {
            if (preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $dado)) {
                return str_replace(['.', '-'], '', $dado);
            } else {
                throw new \Exception('Formato de CPF inválido.');
            }
        };
        $this->algoritmos[self::CPF]['app'] = function ($dado) {
            return substr($dado, 0, 3) . '.' . substr($dado, 3, 3) . '.' . substr($dado, 6, 3) . '-' .
                substr($dado, 9, 2);
        };

        $this->algoritmos[self::FT] = [];
        $this->algoritmos[self::FT]['bd'] = function ($dado) {
            $palavras = explode(' ', $dado);
            $palavras_modificadas = [];
            foreach ($palavras as $palavra) {
                if ($palavra) {
                    $palavras_modificadas[] = '+' . $palavra . '*';
                }
            }
            if ($palavras_modificadas) {
                return str_replace(['.', '-'], '', implode(' ', $palavras_modificadas));
            }
            return null;
        };
        $this->algoritmos[self::FT]['app'] = function ($dado) {
            throw new \Exception('FT não possui versão proveniente do banco de dados.');
        };
        $this->algoritmos[self::TELEFONE] = [];
        $this->algoritmos[self::TELEFONE]['bd'] = function ($dado) {
            $dado = str_replace(['(', ')', '-', '?'], '', $dado);
            if (preg_match('/^[0-9]{8,11}$/', $dado)) {
                return $dado;
            }
        };
        $this->algoritmos[self::TELEFONE]['app'] = function ($dado) {
            switch (strlen($dado)) {
                case 8:
                    $dado = substr($dado, 0, 4) . '-' . substr($dado, 4, 4);
                    break;
                case 9:
                    $dado = substr($dado, 0, 5) . '-' . substr($dado, 5, 4);
                    break;
                case 10:
                    $dado = '(' . substr($dado, 0, 2) . ')' . substr($dado, 2, 4) . '-' . substr($dado, 6, 4);
                    break;
                case 11:
                    $dado = '(' . substr($dado, 0, 2) . ')' . substr($dado, 2, 5) . '-' . substr($dado, 7, 4);
                    break;
            }
            return $dado;
        };
        $this->algoritmos[self::CNPJ] = [];
        $this->algoritmos[self::CNPJ]['bd'] = function ($dado) {
            if (preg_match('/^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}\-?[0-9]{2}$/', $dado)) {
                return \str_replace(['.', '/', '-'], '', $dado);
            } else {
                throw new \Exception('Formato de CNPJ inválido.');
            }
        };
        $this->algoritmos[self::CNPJ]['app'] = function ($dado) {
            return substr($dado, 0, 2) . '.' .
                substr($dado, 2, 3) . '.' .
                substr($dado, 5, 3) . '/' .
                substr($dado, 8, 4) . '-' .
                substr($dado, 12, 2);
        };
    }

    /**
     *
     * @param integer|float|string $dado
     * @param integer $tipo
     */
    public function bd($dado, $tipo)
    {
        if (!$this->algoritmos[$tipo]) {
            throw new \Exception('Tipo não ' . $tipo . ' definido.', 7);
        }
        return $this->algoritmos[$tipo]['bd']($dado);
    }

    /**
     *
     * @param integer|float|string $dado
     * @param integer $tipo
     */
    public function app($dado, $tipo)
    {
        if (!$this->algoritmos[$tipo]) {
            throw new \Exception('Tipo não ' . $tipo . ' definido.', 8);
        }
        return $this->algoritmos[$tipo]['app']($dado);
    }

}
