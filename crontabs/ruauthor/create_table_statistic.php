<?php

$table_prefix = date('Y').'_'.date('n');

$sql = "
CREATE TABLE IF NOT EXISTS $base_statistic.`page_visites_$table_prefix` (
  `id` int(11) NOT NULL auto_increment,
  `data` datetime NOT NULL,
  `brief_data` date NOT NULL,
  `article_id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `user_id` int(11) default NULL,
  `uniq` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uniq` (`uniq`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

";

//$base->query($sql);
mysql_query($sql);

?>