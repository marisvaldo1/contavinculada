-- MySQL Script generated by MySQL Workbench
-- Thu Jun 22 09:20:01 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema dbpphp
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema dbpphp
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `dbpphp` DEFAULT CHARACTER SET utf8 ;
USE `dbpphp` ;

-- -----------------------------------------------------
-- Table `dbpphp`.`pessoa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbpphp`.`pessoa` (
  `codigo` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(50) NOT NULL,
  `nascimento` DATE NOT NULL,
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `dbpphp`.`endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `dbpphp`.`endereco` (
  `codigo` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cod_pessoa` SMALLINT(5) UNSIGNED NOT NULL,
  `cep` CHAR(8) NOT NULL,
  `logradouro` VARCHAR(200) NOT NULL,
  `bairro` VARCHAR(50) NOT NULL,
  `cidade` VARCHAR(30) NOT NULL,
  `uf` CHAR(2) NOT NULL,
  `numero` VARCHAR(30) NULL DEFAULT NULL,
  `complemento` VARCHAR(30) NULL DEFAULT NULL,
  PRIMARY KEY (`codigo`),
  INDEX `endereco_ibfk_1` (`cod_pessoa` ASC),
  CONSTRAINT `endereco_ibfk_1`
    FOREIGN KEY (`cod_pessoa`)
    REFERENCES `dbpphp`.`pessoa` (`codigo`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
