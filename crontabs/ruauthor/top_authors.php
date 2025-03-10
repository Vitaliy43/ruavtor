<?php
$period_authors = 86400*30;
$data = date('Y-m-d');

$res = mysql_query("SELECT u.id AS user_id,COUNT(a.id) AS num_articles FROM users u JOIN authors au ON u.id = au.user_id JOIN articles a ON u.id = a.user_id GROUP BY u.id");
if (mysql_num_rows($res) > 0)
{
	while ($arr = mysql_fetch_assoc($res))
    {
		$user_id = (int)$arr['user_id'];
    	$sql = "SELECT IFNULL(SUM(sd.num_all),0) AS num FROM ".$base_statistic.".`statistic_day` sd WHERE sd.article_id IN (SELECT id FROM ".$db['default']['database'].".articles a WHERE a.user_id = $user_id) AND (UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(sd.data)) < $period_authors";
//		echo $sql.'<br>';
		$res_stat = mysql_query($sql);
		while ($arr_stat = mysql_fetch_assoc($res_stat))
    	{
			$num = (int)$arr_stat['num'];
			$uniq = md5($user_id.'-'.$data);
			if($num){
				mysql_query("INSERT IGNORE INTO ".$db['default']['database'].".`top_authors`(`id`, `data`, `user_id`, `num_views`,`uniq`) VALUES (null,'$data',$user_id,$num,'$uniq')");
			}
    		
    	}
    }
}

mysql_free_result($res_stat);
mysql_free_result($res);

//$sql = "INSERT IGNORE INTO ruauthor_old.`top_authors`(`id`, `data`, `user_id`, `num_views`,`uniq`) SELECT null,data,user_id,num_views,uniq FROM ".$db['default']['database'].".top_authors WHERE data = '$data'";
//$sql = "INSERT IGNORE INTO ruauthor_old.top_authors(`id`, `data`, `user_id`, `num_views`,`uniq`) SELECT null,data,user_id,num_views,uniq FROM ".$db['default']['database'].".top_authors";
//echo $sql;

//$res = mysql_query($sql);
//if($res)
//	echo 'Inserted!!';
//else
//	echo 'Not inserted!!!';



//$sql = "SELECT IFNULL(SUM(sd.num_all),0) AS num FROM ".$base_statistic.".`statistic_day` sd WHERE sd.article_id IN (SELECT id FROM ".$db['default']['database'].".articles a WHERE a.user_id = $user_id) AND (UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(sd.data)) > $period_authors";

?>