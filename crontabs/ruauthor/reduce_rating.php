<?php

$unactivity_day = $base->selectCell('SELECT value FROM articler_settings WHERE type=? AND `key`=?','rating_activity','unactivity_day');
$lowest_rating = $base->selectCell('SELECT value FROM articler_settings WHERE type=? AND `key`=?','rating_activity','lowest_rating');
$unactivity_day = (int)$unactivity_day;
$lowest_rating = (int)$lowest_rating;
$prev_day = mysql_real_escape_string(prev_day().' 00:00:00');
$sql = "UPDATE ratings_activity SET rating = rating - $unactivity_day WHERE data_modified < '$prev_day' AND rating > $lowest_rating";
//$res = $base->query($sql);
$res = mysql_query($sql);
$sql = "UPDATE ratings_activity SET rating = $lowest_rating WHERE rating < $lowest_rating";
//$res = $base->query($sql);
$res = mysql_query($sql);

?>