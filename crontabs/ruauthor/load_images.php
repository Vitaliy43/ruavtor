<?php

$res = mysql_query("SELECT content,id FROM articles");
//$res = mysql_query("SELECT content,id FROM ruauthor_old.articles");
if (mysql_num_rows($res) > 0)
{
	while ($arr = mysql_fetch_assoc($res))
    {
		$id = (int)$arr['id'];
		$content = $arr['content'];
    	preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $content, $matches);
		var_dump($matches[0]);
		if(count($matches[0]) > 0){
			$link = get_image_link($matches[0]);
			echo 'link '.$link.'<br>';
			if($link)
				mysql_query("UPDATE articles SET image = '$link' WHERE id = $id AND image IS NULL");
//				mysql_query("UPDATE ruauthor_old.articles SET image = '$link' WHERE id = $id AND image IS NULL");
		}
		
    }
}

mysql_free_result($res);


function get_image_link($arr){
	foreach($arr as $item){
		echo 'item <br>';
		var_dump($item);
		echo '<br>';
		if(strpos($item,'jpg') || strpos($item,'jpeg') || strpos($item,'png') || strpos($item,'gif')){
			return str_replace('src="','',$item);
		}
	}
	return false;
}

?>