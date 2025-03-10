<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/application/libraries/db_add.php');
DB_add::init();
$res = mysql_query("SELECT * FROM articler_img.headings");
$res_routes = mysql_query("SELECT name FROM headings");

for($i = 0; $i < mysql_num_rows($res_routes) ; $i++){
	$route[mysql_result($res_routes, $i, 'name')] = 'articler/articles';
}
DB_add::close();

?>