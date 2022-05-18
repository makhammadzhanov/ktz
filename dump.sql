CREATE TABLE `structure` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `parent_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `structure` (`id`, `title`, `description`, `parent_id`) VALUES
(1, 'Запись #1', 'Описание #1', 0),
(2, 'Запись #2', 'Описание #2', 0),
(3, 'Запись #3', 'Описание #3', 0),
(4, 'Запись #1.1', 'Описание #1.1', 1),
(5, 'Запись #1.2', 'Описание #1.2', 1),
(6, 'Запись #1.1.1', 'Описание #1.1.1', 4),
(7, 'Запись #1.1.2', 'Описание #1.1.2', 4),
(8, 'Запись #2.1', 'Описание #2.1', 2),
(13, 'Запись #3.1.1', 'Описание #3.1.1', 12),
(12, 'Запись #3.1', 'Описание #3.1', 3);

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$aP63NTP5uadIdwcaPdHzDOpPu2u53Rzi57MEnYVp4hkbuL0c4mm4C', 10);

ALTER TABLE `structure`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `structure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
