CREATE TABLE IF NOT EXISTS `users` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `email` VARCHAR(255) NOT NULL,
    `hash` VARCHAR(60) NOT NULL,
    `role` ENUM('user', 'editor', 'admin') NOT NULL DEFAULT 'user',
    `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(255) NOT NULL , 
    `author` VARCHAR(255) NULL , 
    `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `perex` VARCHAR(255) NULL,
    `content` TEXT NULL , 
    `status` ENUM('public', 'private') NOT NULL DEFAULT 'private',
    
    PRIMARY KEY (`id`)

) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `webinfo` (
    `id` INT NOT NULL AUTO_INCREMENT , 
    `key` VARCHAR(255) NOT NULL , 
    `value` TEXT,

    PRIMARY KEY (`id`, `key`)
) ENGINE = InnoDB;