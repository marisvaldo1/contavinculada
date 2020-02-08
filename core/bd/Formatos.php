<?php

namespace bd;

class Formatos
{
    public static function nome($nome)
    {
        if ($nome) {
            if (mb_strlen($nome) < 3) {
                throw new FormatosException('Formato de nome deve conter ao menos 3 caracteres.');
            }
            $nome = preg_replace('/\s+/', ' ', $nome);
            return upper($nome);
        }
    }

    public static function email($email)
    {
        if ($email) {
            if (!preg_match('/^[a-zA-Z0-9\-\.\_]+@[a-zA-Z0-9]+(\.[a-zA-Z\.]+)?$/', $email)) {
                throw new FormatosException('Formato de e-mail inválido');
            }
            return lower($email);
        }
    }

    public static function telefoneApp($telefone)
    {
        if ($telefone) {
            if (preg_match('/^(\(?[0-9?]{2}\)?)?[0-9]{3,5}\-?[0-9]{4}$/', $telefone)) {
                $telefone = str_replace(['(', ')', '-'], '', $telefone);
                switch (strlen($telefone)) {
                    case 11:
                        return '(' . substr($telefone, 0, 2) . ')' .
                            substr($telefone, 2, 5) . '-' .
                            substr($telefone, 7, 4);
                    case 10:
                        //DDD + 8 dígitos
                        return '(' . substr($telefone, 0, 2) . ')' .
                            substr($telefone, 2, 4) . '-' .
                            substr($telefone, 6);
                    case 9:
                        //9 dígitos                        
                        return '(??)' . substr($telefone, 0, 5) . '-' . substr($telefone, 5);
                    case 8:
                        //8 dígitos
                        return '(??)' . substr($telefone, 0, 4) . '-' . substr($telefone, 4);
                    default:
                        throw new FormatosException('Formato de telefone inválido. (2)');
                }
            } else {
                throw new FormatosException('Formato de telefone inválido.');
            }
        }
    }

    public static function telefoneBd($telefone)
    {
        if ($telefone) {
            if (preg_match('/^(\(?[0-9?]{2}\)?)?[0-9]{3,5}\-?[0-9]{4}$/', $telefone)) {
                return str_replace(['(', ')', '-'], '', $telefone);
            } else {
                throw new FormatosException('Formato de telefone inválido.');
            }
        }
    }

    public static function cpfApp($cpf)
    {
        if ($cpf) {
            if (preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $cpf)) {
                $cpf = str_replace(['.', '-'], '', $cpf);
                return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
            } else {
                throw new FormatosException('Formato de CPF inválido.');
            }
        }
    }

    public static function cpfBd($cpf)
    {
        if ($cpf) {
            if (preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $cpf)) {
                return str_replace(['.', '-'], '', $cpf);
            } else {
                throw new FormatosException('Formato de CPF inválido.');
            }
        }
    }

    public static function cepBd($cep)
    {
        if ($cep) {
            if (preg_match('/^[0-9]{5}\-?[0-9]{3}$/', $cep)) {
                return str_replace('-', '', $cep);
            } else {
                throw new FormatosException('Formato de CEP inválido.');
            }
        }
    }

    public static function cepApp($cep)
    {
        if ($cep) {
            if (preg_match('/^[0-9]{5}\-?[0-9]{3}$/', $cep)) {
                $cep = str_replace('-', '', $cep);
                return substr($cep, 0, 5) . '-' . substr($cep, -3);
            } else {
                throw new FormatosException('Formato de CEP inválido.');
            }
        }
    }

    public static function dataBd($data)
    {
        if ($data) {
            if (gettype($data) == 'string') {
                if (strpos($data, '/') !== false) {
                    $formato = 'd/m/Y';
                } else {
                    $formato = 'Y-m-d';
                }
                $d = \DateTime::createFromFormat($formato, $data);
                if ($d && $d->format($formato) == $data) {
                    return $d->format('Y-m-d');
                } else {
                    throw new FormatosException('Formato de data inválido.');
                }
            } elseif (is_object($data) && get_class($data) == 'DateTime') {
                return $data->format('Y-m-d');
            }
            throw new FormatosException('Formato de data inválido.');
        }
    }

    public static function dataApp($data)
    {
        if ($data) {
            if (gettype($data) == 'string') {
                if (strpos($data, '/') !== false) {
                    $formato = 'd/m/Y';
                } else {
                    $formato = 'Y-m-d';
                }
                $d = \DateTime::createFromFormat($formato, $data);
                if ($d && $d->format($formato) == $data) {
                    return $d->format('d/m/Y');
                } else {
                    throw new FormatosException('Formato de data inválido.');
                }
            } elseif (is_object($data) && get_class($data) == 'DateTime') {
                return $data->format('d/m/Y');
            }
            throw new FormatosException('Formato de data inválido.');
        }
    }

    public static function inteiro($numero)
    {
        if ($numero !== null) {
            if (is_numeric($numero)) {
                return intval($numero);
            } else {
                throw new FormatosException('Formato inteiro inválido.');
            }
        }
    }

    public static function cnpjBd($cnpj)
    {
        //74.787.271/0001-75
        if ($cnpj) {
            $regex = '/^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}\-?[0-9]{2}$/';
            if (preg_match($regex, $cnpj)) {
                return str_replace(['.', '/', '-'], '', $cnpj);
            } else {
                throw new FormatosException('Formato de CNPJ inválido.');
            }
        }
    }

    public static function cnpjApp($cnpj)
    {
        //74.787.271/0001-75
        if ($cnpj) {
            $regex = '/^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}\-?[0-9]{2}$/';
            if (preg_match($regex, $cnpj)) {
                $cnpj = str_replace(['.', '/', '-'], '', $cnpj);
                return substr($cnpj, 0, 2) . '.' .
                    substr($cnpj, 2, 3) . '.' .
                    substr($cnpj, 5, 3) . '/' .
                    substr($cnpj, 8, 4) . '-' .
                    substr($cnpj, 12);
            } else {
                throw new FormatosException('Formato de CNPJ inválido.');
            }
        }
    }

    public static function ft($texto)
    {
        return MySQL::ft($texto);
    }

    public static function real($numero)
    {
        if (!is_null($numero) && $numero != '') {
            if (!is_numeric($numero)) {
                $numero = str_replace(['.', ','], ['', '.'], $numero);
                return floatval($numero);
            } else {
                return $numero;
            }
        }
    }

    public static function moeda($numero)
    {
        if (!is_null($numero)) {
            return number_format(self::real($numero), 2, ',', '.');
        }
    }

    public static function realFormatado($numero)
    {
        return str_replace('.', ',', self::real($numero));
    }

    public static function mcuApp($mcu)
    {
        $mcu = trim($mcu);
        if (preg_match('/^[0-9]{8}$/', $mcu)) {
            return $mcu;
        } else {
            throw new \Exception('Formato de MCU inválido.');
        }
    }

    public static function mcuBd($mcu)
    {
        $mcu = trim($mcu);
        if (preg_match('/^[0-9]{8}$/', $mcu)) {
            return '    ' . $mcu;
        } else {
            throw new \Exception('Formato de MCU inválido.');
        }
    }

    public static function placaBd($placa)
    {
        $placa = trim($placa);
        if (preg_match('/^[a-zA-Z]{3}\-?[0-9]{4}$/', $placa)) {
            $placa = str_replace('-', '', $placa);
            return upper($placa);
        } else {
            throw new \Exception('Formato de placa de veículo inválido.');
        }
    }

    public static function placaApp($placa)
    {
        $placa = trim($placa);
        if (preg_match('/^[a-zA-Z]{3}\-?[0-9]{4}$/', $placa)) {
            $placa = str_replace('-', '', $placa);
            $placa = upper($placa);
            return substr($placa, 0, 3) . '-' . substr($placa, 3);
        } else {
            throw new \Exception('Formato de placa de veículo inválido.');
        }
    }

    public static function dashToCamel($dashed)
    {
        $partes = explode('-', $dashed);
        $len = count($partes);
        for ($i = 1; $i < $len; $i++) {
            $partes[$i] = ucfirst($partes[$i]);
        }
        return implode('', $partes);
    }
}
