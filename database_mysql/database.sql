SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP DATABASE IF EXISTS `aburisk` ;
CREATE DATABASE IF NOT EXISTS `aburisk` DEFAULT CHARACTER SET utf8 ;

DROP SCHEMA IF EXISTS `aburisk` ;
CREATE SCHEMA IF NOT EXISTS `aburisk` DEFAULT CHARACTER SET utf8 ;
USE `aburisk` ;

-- -----------------------------------------------------
-- Table `aburisk`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aburisk`.`users` ;

CREATE  TABLE IF NOT EXISTS `aburisk`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(20) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `password` VARCHAR(45) NOT NULL ,
  `played_games` INT NOT NULL DEFAULT 0 ,
  `won_games` INT NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aburisk`.`games`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aburisk`.`games` ;

CREATE  TABLE IF NOT EXISTS `aburisk`.`games` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `noplayers` TINYINT NOT NULL DEFAULT 2 ,
  `state` ENUM('WAITING_PLAYERS','PLANET_CLAIM','SHIP_PLACING','ATTACK','GAME_END') NULL DEFAULT NULL ,
  `current_player_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_games_users1_idx` (`current_player_id` ASC) ,
  CONSTRAINT `fk_games_users1`
    FOREIGN KEY (`current_player_id` )
    REFERENCES `aburisk`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aburisk`.`galaxies`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aburisk`.`galaxies` ;

CREATE  TABLE IF NOT EXISTS `aburisk`.`galaxies` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL DEFAULT 'Milky Way' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aburisk`.`planets`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aburisk`.`planets` ;

CREATE  TABLE IF NOT EXISTS `aburisk`.`planets` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL DEFAULT 'Tatooine' ,
  `containing_galaxy_id` INT NOT NULL ,
  `image` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_planets_galaxies1_idx` (`containing_galaxy_id` ASC) ,
  CONSTRAINT `fk_planets_galaxies1`
    FOREIGN KEY (`containing_galaxy_id` )
    REFERENCES `aburisk`.`galaxies` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aburisk`.`planets_neighbours`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aburisk`.`planets_neighbours` ;

CREATE  TABLE IF NOT EXISTS `aburisk`.`planets_neighbours` (
  `first_planet_id` INT NOT NULL ,
  `second_planet_id` INT NOT NULL ,
  PRIMARY KEY (`first_planet_id`, `second_planet_id`) ,
  INDEX `fk_planets_has_planets_planets2_idx` (`second_planet_id` ASC) ,
  INDEX `fk_planets_has_planets_planets1_idx` (`first_planet_id` ASC) ,
  CONSTRAINT `fk_planets_has_planets_planets1`
    FOREIGN KEY (`first_planet_id` )
    REFERENCES `aburisk`.`planets` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planets_has_planets_planets2`
    FOREIGN KEY (`second_planet_id` )
    REFERENCES `aburisk`.`planets` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aburisk`.`planets_games`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aburisk`.`planets_games` ;

CREATE  TABLE IF NOT EXISTS `aburisk`.`planets_games` (
  `planet_id` INT NOT NULL ,
  `owner_id` INT NOT NULL ,
  `game_id` INT NOT NULL ,
  `noships` INT NOT NULL ,
  `x_axis` INT NOT NULL ,
  `y_axis` INT NULL DEFAULT NULL ,
  `radius` INT NULL DEFAULT NULL ,
  INDEX `fk_planets_games_planets1_idx` (`planet_id` ASC) ,
  INDEX `fk_planets_games_users1_idx` (`owner_id` ASC) ,
  INDEX `fk_planets_games_games1_idx` (`game_id` ASC) ,
  CONSTRAINT `fk_planets_games_planets1`
    FOREIGN KEY (`planet_id` )
    REFERENCES `aburisk`.`planets` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planets_games_users1`
    FOREIGN KEY (`owner_id` )
    REFERENCES `aburisk`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planets_games_games1`
    FOREIGN KEY (`game_id` )
    REFERENCES `aburisk`.`games` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aburisk`.`users_games`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aburisk`.`users_games` ;

CREATE  TABLE IF NOT EXISTS `aburisk`.`users_games` (
  `user_id` INT NOT NULL ,
  `score` INT NULL DEFAULT 0 ,
  `game_id` INT NOT NULL ,
  INDEX `fk_users_games_users1_idx` (`user_id` ASC) ,
  INDEX `fk_users_games_games1_idx` (`game_id` ASC) ,
  CONSTRAINT `fk_users_games_users1`
    FOREIGN KEY (`user_id` )
    REFERENCES `aburisk`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_games_games1`
    FOREIGN KEY (`game_id` )
    REFERENCES `aburisk`.`games` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `aburisk` ;

SET SQL_MODE = '';
GRANT USAGE ON *.* TO aburghelea;
 DROP USER aburghelea;
SET SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';
CREATE USER 'aburghelea' IDENTIFIED BY 'aburisk';

GRANT ALL ON `aburisk`.* TO 'aburghelea';
GRANT SELECT, INSERT, TRIGGER ON TABLE `aburisk`.* TO 'aburghelea';
GRANT SELECT ON TABLE `aburisk`.* TO 'aburghelea';
GRANT SELECT, INSERT, TRIGGER, UPDATE, DELETE ON TABLE `aburisk`.* TO 'aburghelea';
FLUSH PRIVILEGES;


/*
-- Query: 
-- Date: 2013-03-01 21:25
*/
INSERT INTO `games` (`id`,`noplayers`,`state`,`current_player_id`) VALUES (1,2,'GAME_END',NULL);

/*
-- Query: 
-- Date: 2013-03-01 21:25
*/
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (1,'Celestis',1,'Celestis.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (2,'Ver Omesh',1,'Ver Omesh.jph');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (3,'Ver Isca',1,'Ver Isca.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (4,'Abydos',2,'Abydos.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (5,'Dakara',2,'Dakara.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (6,'P3X-888',2,'P3X-888.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (7,'Hebridan',2,'Hebridan.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (8,'Svoriin',2,'Svoriin.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (9,'Lantea',3,'Lantea.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (10,'Doranda',3,'Doranda.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (11,'Taranis',3,'Taranis.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (12,'Athos',3,'Athos.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (13,'Hala',4,'Halla.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (14,'Orila',4,'Orilla.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (16,'Vanir',5,'Vanir.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (17,'Othala',5,'Othala.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (18,'Omega Site',5,'Omega Site.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (19,'Tauri',5,'Tauri.jpg');
INSERT INTO `planets` (`id`,`name`,`containing_galaxy_id`,`image`) VALUES (15,'Freyr',4,'Freyr.jpg');

/*
-- Query: 
-- Date: 2013-03-01 21:25
*/
INSERT INTO `planets_games` (`planet_id`,`owner_id`,`game_id`,`noships`,`x_axis`,`y_axis`,`radius`) VALUES (1,1,1,3,1,1,5);

/*
-- Query: 
-- Date: 2013-03-01 21:25
*/
INSERT INTO `planets_neighbours` (`first_planet_id`,`second_planet_id`) VALUES (1,1);

/*
-- Query: 
-- Date: 2013-03-01 21:25
*/
INSERT INTO `users` (`id`,`username`,`email`,`password`,`played_games`,`won_games`) VALUES (1,'iceman','iceman.ftg@gmail.com','1',1,1);

/*
-- Query: 
-- Date: 2013-03-01 21:26
*/
INSERT INTO `users_games` (`user_id`,`score`,`game_id`) VALUES (1,10,1);

/*
-- Query: 
-- Date: 2013-03-01 21:25
*/
INSERT INTO `galaxies` (`id`,`name`) VALUES (1,'Alteran');
INSERT INTO `galaxies` (`id`,`name`) VALUES (2,'Milky Way');
INSERT INTO `galaxies` (`id`,`name`) VALUES (3,'Pegassus');
INSERT INTO `galaxies` (`id`,`name`) VALUES (4,'Othalla');
INSERT INTO `galaxies` (`id`,`name`) VALUES (5,'Kalien');


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
