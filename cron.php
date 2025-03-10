<?php

$hour=date('G');
$res = mysql_connect('localhost','root','385818');
if(!$res)
	exit;
mysql_select_db('bacauto');
@mysql_query ("SET NAMES `utf8`");
 
 if($hour>=0 and $hour<2){
 	///////////////////////////////// Подключаем скрипт, уменьшающий рейтинг /////////////////////////////////////
	include('scripts/reduce_rating.php');
 }
 elseif($hour>=22){
 	///////////////////////////////// Подключаем скрипт, парсящий логи сервера ///////////////////////////////////
	include('scripts/statistic.php');

 }

?>