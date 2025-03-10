<?php
$currtime = mktime();
$email = new Email;
$res_select = mysql_query("SELECT giv.*,u.username,u.email FROM given_topics giv JOIN users u ON giv.user_id = u.id WHERE giv.booking = 1 AND UNIX_TIMESTAMP(giv.end_booking) < $currtime AND giv.used = 0");
//echo "SELECT giv.*,u.username,u.email FROM given_topics giv JOIN users u ON giv.user_id = u.id WHERE giv.booking = 1 AND UNIX_TIMESTAMP(giv.end_booking) < $currtime)";
echo 'num_rows '.mysql_num_rows($res_select);
$arr = array();
for($i=0;$i<mysql_num_rows($res_select);$i++){
	$arr[$i]['id'] = mysql_result($res_select,$i,'id');
	$arr[$i]['header'] = mysql_result($res_select,$i,'header');
	$arr[$i]['username'] = mysql_result($res_select,$i,'username');
	$arr[$i]['email'] = mysql_result($res_select,$i,'email');
}

	if(count($arr)>0){
	
		$res = mysql_query("DELETE FROM articles WHERE giventopic_id = (SELECT id FROM given_topics WHERE booking = 1 AND UNIX_TIMESTAMP(end_booking) < $currtime) AND activity = 0");
	mysql_query("UPDATE given_topics SET booking = 0, end_booking = '0000-00-00 00:00:00', user_id = null WHERE booking = 1 AND UNIX_TIMESTAMP(end_booking) < $currtime");
		foreach($arr as $elem){
		echo 'id '.$elem['id'].'<br>';
//		send_message($elem['email'],'mail@ruauthor.ru',$elem);
		}
	}
	

	
function send_message($to,$from,$data){
	global $email;
	$subject = 'Уведомление об окончании бронирования статьи "'.$data['header'].'"';
	$report = 'Уважаемый "'.$data['username'].'", уведомляем вас о том, что срок для подачи на модерацию статьи "'.$data['header'].'" вышел. Черновик вашей статьи удален, статья может быть забронирована другим автором';
	$email->mailtype = 'html';
	$email->from($from);
	$email->to($to);
	$email->subject($subject);
	$email->message($report);
	$res_email = $email->send();
	echo 'from '.$from.' to '.$to.' subject '.$subject.' report '.$report;

	if($res_email)
		echo 'Sended!';
	else
		echo 'Error!';
	
}


?>