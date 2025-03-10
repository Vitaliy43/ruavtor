<?php
$period_refer = 86400*60;
$add_payment_refer = 20;
$add_payment_from_refer = 10;
$db_main = 'ruavtorr_ruauthor';
$db_statistic = $base_statistic;
$buffer_refer = mysql_query("SELECT * FROM $db_main.articler_settings WHERE `type` = 'refer_system'");
//$buffer_refer = mysql_query("SELECT * FROM ruauthor.articler_settings");

if (mysql_num_rows($buffer_refer) > 0)
{
	while ($arr = mysql_fetch_assoc($buffer_refer))
    {
		if($arr['hidden'])
			$site_url = 'http://'.$MAIN_MIRROR;
		else
			$site_url = $BASEURL;
		if($arr['key'] == 'add')
			$period_refer = 86400 * $arr['value'];
		elseif($arr['key'] == 'add_user')
			$add_payment_refer = $arr['value'];
		elseif($arr['key'] == 'add_refer')
			$add_payment_from_refer = $arr['value'];
	
    }
}

echo 'period_refer '.$period_refer.' add_payment_refer '.$add_payment_refer.' add_payment_from_refer '.$add_payment_from_refer.'<br>';
$buffer = mysql_query("SELECT * FROM $db_main.articler_settings WHERE `key` = 'num_visites_for_pay'");
$num_visites_for_pay = mysql_result($buffer,0,'value');
mysql_free_result($buffer);
$buffer = mysql_query("SELECT * FROM $db_main.articler_settings WHERE `key` = 'pay_for_visit'");
$pay_for_visit = mysql_result($buffer,0,'value');
mysql_free_result($buffer);
$buffer = mysql_query("SELECT * FROM $db_main.articler_settings WHERE `key` = 'corrective_q'");
$corrective_q = mysql_result($buffer,0,'value');
mysql_free_result($buffer);
$data_using_correct_rating = '2017-02';
$buffer_pay_for_visit = $pay_for_visit;


$res_uids = mysql_query("SELECT DISTINCT user_id 'uid' FROM $db_main.articles WHERE id IN (SELECT article_id FROM $db_statistic.`statistic_day`)");
//echo 'num uids '.mysql_num_rows($res_uids).'<br>';
for($i=0;$i<mysql_num_rows($res_uids);$i++){
	$uid = mysql_result($res_uids,$i,'uid');
	
	$res_data = mysql_query("SELECT data FROM articler_payments WHERE user_id = $uid AND is_statistic = 1 ORDER BY data DESC LIMIT 1");
	$buffer_data = mysql_result($res_data,0,'data');
	list($data,$time) = explode(' ',$buffer_data);
//	echo 'uid '.$uid.'<br>';
	echo 'data '.$data.'<br>';
	$res_sum = mysql_query("SELECT SUM(num_all) 'num' FROM $db_statistic.`statistic_day` WHERE article_id IN (SELECT id FROM $db_main.articles WHERE user_id = $uid) AND data >=  '$data'");
//	$res_sum1 = mysql_query("SELECT SUM(num_all) 'num' FROM $db_statistic.`statistic_day` WHERE article_id IN (SELECT id FROM $db_main.articles WHERE user_id = $uid) AND `data` < '$data_using_correct_rating'");
//	$res_sum2 = mysql_query("SELECT SUM(num_all) 'num' FROM $db_statistic.`statistic_day` WHERE article_id IN (SELECT id FROM $db_main.articles WHERE user_id = $uid) AND `data` >= '$data_using_correct_rating'");
/*
	if(mysql_num_rows($res_sum1) && mysql_num_rows($res_sum2)){
		$num_all = mysql_result($res_sum1,0,'num') + round(mysql_result($res_sum2,0,'num') * $corrective_q);
	}
	elseif(mysql_num_rows($res_sum1)){
		$num_all = mysql_result($res_sum1,0,'num');
	}
	elseif(mysql_num_rows($res_sum2)){
		$num_all = round(mysql_result($res_sum2,0,'num') * $corrective_q);
	}
	*/
	echo 'corrective_q '.$corrective_q.'<br>';
	$num_all = round(mysql_result($res_sum,0,'num') * $corrective_q);
	echo 'num_all '.$num_all.'<br>';

	$res_payments = mysql_query("SELECT SUM(payment) 'payments' FROM $db_main.articler_payments WHERE is_statistic = 1 AND user_id = $uid");
	$add_payment = 0;
	////////////////////////////////////// Начисляем рефералу ////////////////////////////
	$res_refer = mysql_query("SELECT UNIX_TIMESTAMP(data) AS data,refer_id FROM $db_main.user_refers WHERE user_id = $uid");
	if(mysql_num_rows($res_refer) > 0){
		$data_refer = mysql_result($res_refer,0,'data');
		if(($data_refer + $period_refer) < time())
			$add_payment += $add_payment_refer;
		$partner_id = mysql_result($res_refer,0,'refer_id');
//		echo 'refer !!!!';
	}
	mysql_free_result($res_refer);
	
	$buffer_payments = mysql_result($res_payments,0,'payments');
//	if($add_payment)
//		$pay_for_visit = $buffer_pay_for_visit * (1 + $add_payment/100);
	/*	
	if($buffer_payments)
//		$rest = mysql_result($res_sum,0,'num') - round($buffer_payments/$pay_for_visit);
		$rest = $num_all - round($buffer_payments/$pay_for_visit);
	else
		$rest = $num_all;
	*/	
	$rest = $num_all;

//		echo 'pay_for_visit '.$pay_for_visit.'<br>';
//		if($add_payment)
//			echo 'add_payment '.$add_payment.'<br>';
//	echo 'rest '.$rest.'<br>';
	
//	echo 'pay_for_visit '.$pay_for_visit.'<br>';
	$payment = round($rest*$corrective_q*$pay_for_visit);
//	$payment = round($rest*$pay_for_visit);
	echo 'payment '.$payment.'<br>';
	if(isset($partner_id)){
		$partner_payment = $payment * ($add_payment_from_refer/100);
		$partner_payment = round($partner_payment);
		$buffer_score = mysql_query("SELECT score FROM $db_main.articler_score WHERE user_id = $partner_id LIMIT 1");
//		echo 'partner_payment '.$partner_payment.'<br>';
		$score = mysql_result($buffer_score,0,'score') + $partner_payment;
		$date = date("Y-m-d H:i:s");
		if($partner_payment > 0){
			mysql_query("INSERT INTO $db_main.articler_payments (id,user_id,article_id,payment,balance,data,is_statistic,is_partner,rest,pay_for_visit,num_views) VALUES (null,$partner_id,0,$partner_payment,$score,'$date',1,1,$rest,$pay_for_visit,$num_all)");
			mysql_query("UPDATE $db_main.articler_score SET score = $score WHERE  user_id = $partner_id");
		}
		
		mysql_free_result($buffer_score);
	}
//	echo 'rest '.$rest.' num_visites_for_pay '.$num_visites_for_pay.'<br>';
	if($rest >= $num_visites_for_pay){
//		if(!$payment)
//			continue;
		$buffer_score = mysql_query("SELECT score FROM $db_main.articler_score WHERE user_id = $uid LIMIT 1");
		$score = mysql_result($buffer_score,0,'score') + $payment;
		$date = date("Y-m-d H:i:s");
//		echo 'Payment !!! <br>';
//		$sql = "INSERT INTO $db_main.articler_payments (id,user_id,article_id,payment,balance,data,is_statistic) VALUES (null,$uid,0,$payment,$score,'$date',1)";
//		echo $sql.'<br>';
		echo 'payment '.$payment;
		$payment = (int)$payment;
		if($payment > 0){
			mysql_query("INSERT INTO $db_main.articler_payments (id,user_id,article_id,payment,balance,data,is_statistic,is_partner,rest,pay_for_visit,num_views) VALUES (null,$uid,0,$payment,$score,'$date',1,0,$rest,$pay_for_visit,$num_all)");
			mysql_query("UPDATE $db_main.articler_score SET score = $score WHERE  user_id = $uid");
		}
		
		
	}
}

?>