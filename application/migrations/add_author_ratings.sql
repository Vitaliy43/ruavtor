
-- Заполнение таблицы необходимо для того, чтобы при плюсовании не происходило начисление баллов за выход из песочницы
INSERT INTO `add_author_ratings`(`id`, `article_id`, `add`, `data_change`) SELECT null,id,10,'2013-07-22 13:00:00' FROM articles WHERE rating >= 5 AND activity = 2 AND data_published < '2013-07-19 00:00:00';