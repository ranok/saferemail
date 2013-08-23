
-- ---
-- Table 'user'
-- 
-- ---

DROP TABLE IF EXISTS `user`;
		
CREATE TABLE `user` (
  `id` TINYINT NULL AUTO_INCREMENT DEFAULT NULL,
  `email` MEDIUMTEXT NOT NULL,
  `name` MEDIUMTEXT NULL,
  `pubkey` MEDIUMTEXT NOT NULL,
  `encprivkey` MEDIUMTEXT NOT NULL,
  UNIQUE KEY (`id`),
  UNIQUE KEY (`email`)
);

-- ---
-- Table 'tag'
-- 
-- ---

DROP TABLE IF EXISTS `tag`;
		
CREATE TABLE `tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user` INT NOT NULL DEFAULT NULL,
  `name` MEDIUMTEXT NOT NULL DEFAULT 'NULL',
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'message'
-- 
-- ---

DROP TABLE IF EXISTS `message`;
		
CREATE TABLE `message` (
  `id` TINYINT NULL AUTO_INCREMENT DEFAULT NULL,
  `user` INT NOT NULL DEFAULT 0,
  `from` MEDIUMTEXT NOT NULL,
  `subject` MEDIUMTEXT NOT NULL,
  `timestamp` TIMESTAMP NOT NULL,
  `message` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'contact'
-- 
-- ---

DROP TABLE IF EXISTS `contact`;
		
CREATE TABLE `contact` (
  `id` TINYINT NULL AUTO_INCREMENT DEFAULT NULL,
  `user` INT NOT NULL DEFAULT 0,
  `name` MEDIUMTEXT NOT NULL,
  `email` MEDIUMTEXT NULL DEFAULT NULL,
  `phone` VARCHAR(16) NULL DEFAULT NULL,
  `pubkey` MEDIUMTEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'tag_mapping'
-- 
-- ---

DROP TABLE IF EXISTS `tag_mapping`;
		
CREATE TABLE `tag_mapping` (
  `tag` INT NOT NULL,
  `message` INT NOT NULL
);

-- ---
-- Foreign Keys 
-- ---

ALTER TABLE `tag` ADD FOREIGN KEY (user) REFERENCES `user` (`id`);
ALTER TABLE `message` ADD FOREIGN KEY (user) REFERENCES `user` (`id`);
ALTER TABLE `contact` ADD FOREIGN KEY (user) REFERENCES `user` (`id`);
ALTER TABLE `tag_mapping` ADD FOREIGN KEY (tag) REFERENCES `tag` (`id`);
ALTER TABLE `tag_mapping` ADD FOREIGN KEY (message) REFERENCES `message` (`id`);
