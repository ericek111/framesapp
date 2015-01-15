CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `reason` varchar(300) DEFAULT NULL,
  `age` int(10) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `emailcheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `reason` varchar(300) DEFAULT NULL,
  `age` int(10) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `requestid` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `whitelist` (
  `name` varchar(255) NOT NULL,
  UNIQUE KEY `name` (`name`)
)