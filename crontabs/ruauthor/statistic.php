<?php
$prev_day = prev_day();
$buffer = explode('-',$prev_day);
$buffer_day = $buffer[2];
$buffer_month = $buffer[1];
if($buffer_day == last_day($buffer_month)){
	$curr_month = date('n');
	$curr_year = date('Y');
	if(($curr_month-1) == 0 ){
		$curr_year --;
		$curr_month = 12;
	}
	else{
		$curr_month --;
	}
	
	$table_data = $curr_year.'_'.$curr_month;
	$table_statistic = $base_statistic.'.page_visites_'.$table_data;
}
else
	$table_statistic = $base_statistic.'.page_visites_'.date('Y').'_'.date('n');
$sql = "SELECT `article_id`,`user_id` FROM $table_statistic WHERE `brief_data`='$prev_day' ORDER BY `article_id`";
//echo $sql;
//$buffer = $base->select($sql,$prev_day);
$res = mysql_query($sql);
$buffer = array();
while($arr=mysql_fetch_assoc($res)){
	$buffer[] = $arr;
}
//$buffer = mysql_query($sql);
$new_arr = array();
$article_id = 0;
$counter = 1;
if(count($buffer) == 0)
	exit;
$ids = array_complex_unique($buffer, 'article_id');

foreach($buffer as $elem){
	if($elem['user_id'])
		$user_list[] = $elem;
	else
		$guest_list[] = $elem;
}



if(count($user_list) > 0){
	
	foreach($user_list as $user){
		$buffer_article_ids[] = $user['article_id'];
	}
	
	$user_article_ids = array_count_values($buffer_article_ids);
}


if(count($guest_list) > 0){
	
	foreach($guest_list as $guest){
		$buffer_article_ids[] = $guest['article_id'];
	}
	
	$guest_article_ids = array_count_values($buffer_article_ids);
}

//var_dump($ids);
//exit;

foreach($ids as $elem){

	if(empty($user_article_ids[$elem]))
		$num_users = 0;
	else
		$num_users = $user_article_ids[$elem];
		
	if(empty($guest_article_ids[$elem]))
		$num_guests = 0;
	else
		$num_guests = $guest_article_ids[$elem];
		
	
	$prev_day = mysql_real_escape_string($prev_day);
	$elem = (int)$elem;
	$uniq = $prev_day.' '.$elem;
	$num_users = (int)$num_users;
	$num_guests = (int)$num_guests;
	$num_all = $num_users + $num_guests;
		
//	$base->query("INSERT IGNORE INTO $base_statistic.statistic_day VALUES(null,'$prev_day',$elem,$num_all,$num_users,$num_guests,'$uniq')");
	$sql = "INSERT IGNORE INTO $base_statistic.statistic_day VALUES(null,'$prev_day',$elem,$num_all,$num_users,$num_guests,'$uniq')";
//	echo $sql.'<br>';
	mysql_query($sql);

	
}


?>