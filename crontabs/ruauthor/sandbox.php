<?php
function is_quit_sandbox(){

	$res = mysql_query("SELECT value,`key` FROM articler_settings");
	
	if (mysql_num_rows($res) > 0)
   	{
       	while ($arr = mysql_fetch_assoc($res))
       	{
       		$value = $arr["value"];
       		$key = $arr["key"];
			if($key == 'rating_for_homefeed_1')
				$rating_for_homefeed = $value;
			if($key == 'hold_for_homefeed')
				$hold_for_homefeed = $value;
			
       	}
   	}
	mysql_free_result($res);
	$hold_for_homefeed *= 86400;
	$time = time();
	if(isset($rating_for_homefeed) && isset($hold_for_homefeed)){
		$res = mysql_query("SELECT id,UNIX_TIMESTAMP(data_published) AS data,rating FROM articles WHERE activity = 2");
		while ($arr = mysql_fetch_assoc($res))
       	{
       		$id = (int)$arr["id"];
       		$data = $arr["data"];
       		$rating = (int)$arr["rating"];
			if($rating <= $rating_for_homefeed || ($data + $hold_for_homefeed) > $time){
				mysql_query("UPDATE articles SET in_sandbox = 1 WHERE id = $id");

			}
			else{
				mysql_query("UPDATE articles SET in_sandbox = null WHERE id = $id");

			}
      	 }
	}
	
}

?>