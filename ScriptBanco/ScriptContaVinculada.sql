-- MySQL Script generated by MySQL Workbench
-- Fri Feb  8 23:59:03 2019
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
SHOW WARNINGS;
-- -----------------------------------------------------
-- Schema contavinculada
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `contavinculada` ;

-- -----------------------------------------------------
-- Schema contavinculada
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `contavinculada` DEFAULT CHARACTER SET utf16 COLLATE utf16_bin ;
SHOW WARNINGS;
USE `contavinculada` ;

-- -----------------------------------------------------
-- Table `contavinculada`.`categorias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`categorias` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`categorias` (
  `id_categoria` INT NOT NULL AUTO_INCREMENT,
  `nome_categoria` VARCHAR(45) NOT NULL,
  `status_categoria` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_categoria`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`clientes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`clientes` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`clientes` (
  `id_cliente` INT NOT NULL AUTO_INCREMENT,
  `cnpj` VARCHAR(18) NOT NULL,
  `razao` VARCHAR(50) NOT NULL,
  `endereco` VARCHAR(100) NOT NULL,
  `cidade` VARCHAR(50) NOT NULL,
  `estado` VARCHAR(2) NOT NULL,
  `cep` VARCHAR(10) NOT NULL,
  `telefone` VARCHAR(15) NULL,
  `email` VARCHAR(50) NULL,
  `nome_contato` VARCHAR(50) NULL,
  `telefone_contato` VARCHAR(15) NULL,
  `status_cliente` VARCHAR(10) NULL,
  `id_categoria` INT NOT NULL,
	`decimo_terceiro` DECIMAL(10,2) NULL DEFAULT NULL,
	`ferias_abono` DECIMAL(10,2) NULL DEFAULT NULL,
	`multa_fgts` DECIMAL(10,2) NULL DEFAULT NULL,  
  PRIMARY KEY (`id_cliente`, `id_categoria`),
  CONSTRAINT `fk_clientes_categorias1`
    FOREIGN KEY (`id_categoria`)
    REFERENCES `contavinculada`.`categorias` (`id_categoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_clientes_categorias1_idx` ON `contavinculada`.`clientes` (`id_categoria` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`usuarios`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`usuarios` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`usuarios` (
  `id_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` INT NOT NULL,
  `nome` VARCHAR(50) NOT NULL,
  `email` VARCHAR(50) NOT NULL,
  `senha` VARCHAR(20) NOT NULL,
  `nivel_acesso` INT(1) NOT NULL,
  `opcoes_acesso` TEXT NOT NULL,
  `status_usuario` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_usuario`, `id_cliente`),
  CONSTRAINT `fk_usuarios_clientes1`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `contavinculada`.`clientes` (`id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf16
COLLATE = utf16_bin;

SHOW WARNINGS;
CREATE INDEX `fk_usuarios_clientes1_idx` ON `contavinculada`.`usuarios` (`id_cliente` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`empresas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`empresas` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`empresas` (
  `id_empresa` INT NOT NULL AUTO_INCREMENT,
  `cnpj` VARCHAR(18) NOT NULL,
  `razao` VARCHAR(50) NOT NULL,
  `endereco` VARCHAR(100) NULL,
  `cidade` VARCHAR(50) NULL,
  `estado` VARCHAR(2) NULL,
  `cep` VARCHAR(10) NULL,
  `telefone` VARCHAR(15) NULL,
  `nome_contato` VARCHAR(50) NULL,
  `telefone_contato` VARCHAR(15) NULL,
  `email` VARCHAR(50) NULL,
  `status_empresa` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_empresa`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`clientes_empresas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`clientes_empresas` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`clientes_empresas` (
  `id_cliente` INT NOT NULL,
  `id_empresa` INT NOT NULL,
  PRIMARY KEY (`id_cliente`, `id_empresa`),
  CONSTRAINT `fk_clientes_empresas_empresas1`
    FOREIGN KEY (`id_empresa`)
    REFERENCES `contavinculada`.`empresas` (`id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_clientes_empresas_clientes1`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `contavinculada`.`clientes` (`id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_clientes_empresas_empresas1_idx` ON `contavinculada`.`clientes_empresas` (`id_empresa` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_clientes_empresas_clientes1_idx` ON `contavinculada`.`clientes_empresas` (`id_cliente` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`contratos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`contratos` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`contratos` (
  `id_cliente` INT NOT NULL,
  `id_empresa` INT NOT NULL,
  `id_contrato` INT NOT NULL AUTO_INCREMENT,
  `nu_contrato` VARCHAR(10) NOT NULL,
  `dt_inicio` DATE NOT NULL,
  `dt_final` DATE NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `objeto_contrato` TEXT(2000) NOT NULL,
  `status_contrato` VARCHAR(10) NOT NULL,
  `imagem_contrato` LONGBLOB NULL,
  PRIMARY KEY (`id_cliente`, `id_empresa`, `id_contrato`),
  CONSTRAINT `fk_contratos_CLIENTES_EMPRESAS1`
    FOREIGN KEY (`id_cliente` , `id_empresa`)
    REFERENCES `contavinculada`.`clientes_empresas` (`id_cliente` , `id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`indices`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`indices` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`indices` (
  `id_cliente` INT NOT NULL,
  `id_indice` INT NOT NULL AUTO_INCREMENT,
  `nome_indice` VARCHAR(50) NOT NULL,
  `percentual_indice` DECIMAL(10,2) NULL,
  `status_indice` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_indice`, `id_cliente`),
  CONSTRAINT `fk_indices_clientes1`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `contavinculada`.`clientes` (`id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_indices_clientes1_idx` ON `contavinculada`.`indices` (`id_cliente` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`cargos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`cargos` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`cargos` (
  `id_cargo` INT NOT NULL AUTO_INCREMENT,
  `id_cliente` INT NOT NULL,
  `nome_cargo` VARCHAR(50) NOT NULL,
  `remuneracao_cargo` DECIMAL(10,2) NOT NULL,
  `status_cargo` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_cargo`, `id_cliente`),
  CONSTRAINT `fk_cargos_clientes1`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `contavinculada`.`clientes` (`id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_cargos_clientes1_idx` ON `contavinculada`.`cargos` (`id_cliente` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`empregados`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`empregados` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`empregados` (
  `id_empresa` INT NOT NULL,
  `id_empregado` INT NOT NULL AUTO_INCREMENT,
  `cpf` VARCHAR(14) NOT NULL,
  `nome` VARCHAR(50) NOT NULL,
  `turno` VARCHAR(15) NOT NULL,
  `horario` VARCHAR(45) NULL,
  `remuneracao` DECIMAL(10,2) NOT NULL,
  `dt_admissao` DATE NOT NULL,
  `dt_desligamento` DATE NULL,
  `status_empregado` VARCHAR(10) NOT NULL,
  `id_cliente` INT NULL,
  `id_cargo` INT NULL,
  PRIMARY KEY (`id_empregado`, `id_empresa`),
  CONSTRAINT `fk_empregados_empresas1`
    FOREIGN KEY (`id_empresa`)
    REFERENCES `contavinculada`.`empresas` (`id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_empregados_cargos1`
    FOREIGN KEY (`id_cargo` , `id_cliente`)
    REFERENCES `contavinculada`.`cargos` (`id_cargo` , `id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_empregados_empresas1_idx` ON `contavinculada`.`empregados` (`id_empresa` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_empregados_cargos1_idx` ON `contavinculada`.`empregados` (`id_cargo` ASC, `id_cliente` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`log_acesso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`log_acesso` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`log_acesso` (
  `id_cliente` INT NOT NULL,
  `id_empresa` INT NOT NULL,
  `dt_hr_acesso` DATETIME NOT NULL,
  `url_acessada` VARCHAR(50) NOT NULL,
  `acao` VARCHAR(50) NULL,
  PRIMARY KEY (`id_cliente`, `id_empresa`, `dt_hr_acesso`),
  CONSTRAINT `fk_log_acesso_CLIENTES_EMPRESAS1`
    FOREIGN KEY (`id_empresa`)
    REFERENCES `contavinculada`.`clientes_empresas` (`id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_log_acesso_CLIENTES_EMPRESAS1_idx` ON `contavinculada`.`log_acesso` (`id_cliente` ASC, `id_empresa` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`contratos_empregados`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`contratos_empregados` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`contratos_empregados` (
  `id_cliente` INT NOT NULL,
  `id_empresa` INT NOT NULL,
  `id_contrato` INT NOT NULL,
  `id_empregado` INT NOT NULL,
  PRIMARY KEY (`id_cliente`, `id_empresa`, `id_contrato`, `id_empregado`),
  CONSTRAINT `fk_empregados_contratos_contratos1`
    FOREIGN KEY (`id_contrato` , `id_cliente` , `id_empresa`)
    REFERENCES `contavinculada`.`contratos` (`id_contrato` , `id_cliente` , `id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_empregados_contratos_empregados1`
    FOREIGN KEY (`id_empregado`)
    REFERENCES `contavinculada`.`empregados` (`id_empregado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_empregados_contratos_empregados1_idx` ON `contavinculada`.`contratos_empregados` (`id_empregado` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`lancamentos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`lancamentos` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`lancamentos` (
  `id_cliente` INT NOT NULL,
  `id_empresa` INT NOT NULL,
  `id_contrato` INT NOT NULL,
  `id_empregado` INT NOT NULL,
  `remuneracao` DECIMAL(10,2) NOT NULL,
  `dias_trabalhados` INT NOT NULL,
  `decimo_terceiro` DECIMAL(10,2) NULL,
  `ferias_abono` DECIMAL(10,2) NULL,
  `multa_FGTS` DECIMAL(10,2) NULL,
  `impacto_encargos_13` DECIMAL(10,2) NULL,
  `impacto_ferias_abono` DECIMAL(10,2) NULL,
  `ano` CHAR(4) NOT NULL,
  `mes` CHAR(2) NOT NULL,
	`observacao_retencao` VARCHAR(1000) NOT NULL DEFAULT ' ' COLLATE 'utf16_bin',
	`observacao_liberacao` VARCHAR(1000) NOT NULL DEFAULT ' ' COLLATE 'utf16_bin',  
  PRIMARY KEY (`id_cliente`, `id_empresa`, `id_contrato`, `id_empregado`, `ano`, `mes`),
  CONSTRAINT `fk_lancamentos_empregados_contratos1`
    FOREIGN KEY (`id_cliente` , `id_empresa` , `id_contrato` , `id_empregado`)
    REFERENCES `contavinculada`.`contratos_empregados` (`id_cliente` , `id_empresa` , `id_contrato` , `id_empregado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`encargos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`encargos` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`encargos` (
  `id_cliente` INT NOT NULL,
  `id_encargo` INT NOT NULL AUTO_INCREMENT,
  `percentual_encargo` DECIMAL(10,2) NULL,
  `nome_encargo` VARCHAR(50) NOT NULL,
  `status_encargo` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_encargo`, `id_cliente`),
  CONSTRAINT `fk_encargos_clientes1`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `contavinculada`.`clientes` (`id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_encargos_clientes1_idx` ON `contavinculada`.`encargos` (`id_cliente` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`contratos_encargos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`contratos_encargos` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`contratos_encargos` (
  `id_cliente` INT NOT NULL,
  `id_empresa` INT NOT NULL,
  `id_contrato` INT NOT NULL,
  `id_encargo` INT NOT NULL,
  `percentual_encargo` DECIMAL(10,2) NOT NULL,
  CONSTRAINT `fk_contratos_encargos_contratos1`
    FOREIGN KEY (`id_cliente` , `id_empresa` , `id_contrato`)
    REFERENCES `contavinculada`.`contratos` (`id_cliente` , `id_empresa` , `id_contrato`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_encargos_encargos1`
    FOREIGN KEY (`id_encargo`)
    REFERENCES `contavinculada`.`encargos` (`id_encargo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_contratos_encargos_contratos1_idx` ON `contavinculada`.`contratos_encargos` (`id_cliente` ASC, `id_empresa` ASC, `id_contrato` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_contratos_encargos_encargos1_idx` ON `contavinculada`.`contratos_encargos` (`id_encargo` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`contratos_indices`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`contratos_indices` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`contratos_indices` (
  `id_cliente` INT NOT NULL,
  `id_empresa` INT NOT NULL,
  `id_contrato` INT NOT NULL,
  `id_indice` INT NOT NULL,
  `percentual_indice` DECIMAL(10,2) NOT NULL,
  CONSTRAINT `fk_contratos_indices_contratos1`
    FOREIGN KEY (`id_cliente` , `id_empresa` , `id_contrato`)
    REFERENCES `contavinculada`.`contratos` (`id_cliente` , `id_empresa` , `id_contrato`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contratos_indices_indices1`
    FOREIGN KEY (`id_indice`)
    REFERENCES `contavinculada`.`indices` (`id_indice`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;
CREATE INDEX `fk_contratos_indices_contratos1_idx` ON `contavinculada`.`contratos_indices` (`id_cliente` ASC, `id_empresa` ASC, `id_contrato` ASC) ;

SHOW WARNINGS;
CREATE INDEX `fk_contratos_indices_indices1_idx` ON `contavinculada`.`contratos_indices` (`id_indice` ASC) ;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`contrato_sistema`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`contrato_sistema` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`contrato_sistema` (
  `id_contrato` INT NOT NULL AUTO_INCREMENT,
  `id_cliente` INT NOT NULL,
  `nu_contrato_sisema` VARCHAR(10) NOT NULL,
  `dt_inicio` DATE NOT NULL,
  `dt_final` DATE NOT NULL,
  `tipo_pagamento` CHAR(1) NOT NULL DEFAULT 'M' COMMENT '\'Define se o contrato é Mensal ou Anual\'',
  `valor_contrato` DECIMAL(10,2) NOT NULL,
  `status_contrato_sistema` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_contrato`, `id_cliente`),
  CONSTRAINT `fk_contrato_sistema_clientes1`
    FOREIGN KEY (`id_cliente`)
    REFERENCES `contavinculada`.`clientes` (`id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `contavinculada`.`pagamentos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `contavinculada`.`pagamentos` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `contavinculada`.`pagamentos` (
  `id_contrato` INT NOT NULL,
  `id_cliente` INT NOT NULL,
  `id_parcela` INT NOT NULL,
  `data_vencimento` DATE NOT NULL,
  `data_pagamento` DATE NULL,
  `valor_parcela` DECIMAL(10) NOT NULL,
  `valor_pagamento` DECIMAL(10,2) NULL,
  `status_pagamento` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id_contrato`, `id_cliente`, `id_parcela`, `data_vencimento`),
  CONSTRAINT `fk_pagamentos_contrato_sistema1`
    FOREIGN KEY (`id_contrato` , `id_cliente`)
    REFERENCES `contavinculada`.`contrato_sistema` (`id_contrato` , `id_cliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;