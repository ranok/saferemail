
-- ---
-- Table 'user'
-- 
-- ---

DROP TABLE IF EXISTS `user`;
		
CREATE TABLE `user` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `name` MEDIUMTEXT NULL,
  `pubkey` MEDIUMTEXT NOT NULL,
  `encprivkey` MEDIUMTEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`email`)
);

-- ---
-- Table 'tag'
-- 
-- ---

DROP TABLE IF EXISTS `tag`;
		
CREATE TABLE `tag` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user` INT NOT NULL DEFAULT 0,
  `name` MEDIUMTEXT NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
);

-- ---
-- Table 'message'
-- 
-- ---

DROP TABLE IF EXISTS `message`;
		
CREATE TABLE `message` (
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
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
  `id` INT NULL AUTO_INCREMENT DEFAULT NULL,
  `user` INT NOT NULL DEFAULT 0,
  `name` MEDIUMTEXT NOT NULL,
  `email` MEDIUMTEXT NULL DEFAULT '',
  `phone` VARCHAR(16) NULL DEFAULT '',
  `pubkey` MEDIUMTEXT NULL DEFAULT '',
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

CREATE INDEX ix_id ON user (id);
CREATE INDEX ix_id ON tag (id);
CREATE INDEX ix_id ON message (id);

ALTER TABLE `tag` ADD FOREIGN KEY (user) REFERENCES `user` (`id`);
ALTER TABLE `message` ADD FOREIGN KEY (user) REFERENCES `user` (`id`);
ALTER TABLE `contact` ADD FOREIGN KEY (user) REFERENCES `user` (`id`);
ALTER TABLE `tag_mapping` ADD FOREIGN KEY (tag) REFERENCES `tag` (`id`);
ALTER TABLE `tag_mapping` ADD FOREIGN KEY (message) REFERENCES `message` (`id`);
