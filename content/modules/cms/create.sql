CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author` int NOT NULL,
  `title` text NOT NULL,
  `permalink` text NOT NULL,
  `content` text NOT NULL,
  `status` smallint NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovak_ci;

INSERT INTO `posts` (`id`, `author`, `title`, `permalink`, `content`, `status`, `created`) VALUES ('', '27', 'Frames App released!', 'frames-app-released', 'Frames App was released for public testing!', '1', CURRENT_TIMESTAMP);