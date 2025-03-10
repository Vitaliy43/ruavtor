<?php
set_time_limit(0);
//define('BASEPATH','');
//include('/var/www/ra/data/www/ruauthor.ru/application/config/config.php');
include($root_path.'application/config/auth.php');
include($root_path.'application/config/outer_links.php');


$keyword1 = 'http://'.$config['oul_site'];
$keyword2 = 'http://www.'.$config['oul_site'];
$links = file($root_path.'uploads/files/links.txt');
$num_links = count($links);

$db_prefix = $config['repository_statistic'];
$data = date("Y-m-d");
$data_begin = date("Y-m-d H:i:s");
$sql = "SELECT * FROM $db_prefix.`wrong_links` WHERE data = '$data'";
$res = mysql_query($sql);

$count = mysql_num_rows($res);
if($count > 0)
	exit;

$counter = 1;

foreach($links as $link){
	
	$link = trim($link);
	$buffer = parse_page($link);
	$time = date('H:i:s');
	if($buffer){
		if(is_array($buffer)){
			$answer = mysql_real_escape_string(serialize($buffer));
			$type_answer = 'array';
		}
		else{
			$answer = $buffer;
			$type_answer = 'var';
		}
			$url = mysql_real_escape_string($link);
			$sql = "INSERT INTO $db_prefix.`wrong_links` (`id`, `data`, `url`, `type_answer`, `answer`,`num`,`time`) VALUES (null,'$data','$url','$type_answer','$answer',$counter,'$time')";
//			echo 'sql '.$sql.'<br>';
			
			$res = mysql_query($sql);
			if($res)
				echo 'Inserted!';
	}
	
	$counter++;

}

$data_end = date("Y-m-d H:i:s");

$report = create_report($data,$num_links);
if(!$report)
	exit;
	
$email = new Email;
message_report($config['oul_email'],$config['DX_webmaster_email'],$data,$report);


function parse_page($url){

global $keyword1;
global $keyword2;
$links=array();

/////////////////////////////////////////////// Проверяем head //////////////////////////////////////////////

$content = my_file_get_contents($url);
if ( preg_match_all('/<meta[^>] *?(.*) *?\/?>/isU', $content, $meta) ) {
  foreach( $meta[1] as $attr ) {
    if (
      preg_match('/(http-equiv|name) *?= *?([\'"]{0,1})(.*?)\\2/i', $attr, $key) &&
      preg_match('/content *?= *?([\'"]{0,1})(.*?)\\1/i', $attr, $val)
    ) {
      $metas[$key[3]] = $val[2];
    }
  }
}
if(strstr($metas['robots'],'noindex') != '' || strstr($metas['robots'],'nofollow') != '')
	return 'Страница не индексирована!';
	
$buffer=explode('<a',$content);
	foreach($buffer as $elem){
		$buffer2=explode('>',$elem);
//		echo htmlspecialchars($buffer2[0]).'<br>';
		$a=strtolower($buffer2[0]);
		$res=preg_match('#href="[^"]+"#',$a,$regs);
		if(!$res)
			$res=preg_match("#href='[^']+#",$a,$regs);		//echo 'href <br>';
//		var_dump($regs);
		$buffer_href=explode('href=',$regs[0]);
		$link=$buffer_href[1];
		$res1 = preg_match('#('.$keyword1.'|'.$keyword2.')#',$regs[0],$matches);

		if($res1){
			
			if(strpos($a,'rel="nofollow"')){
				$links[$link]['result']='nofollow';
			}
			elseif(strpos($a,'?'.$keyword1) or strpos($a,'?'.$keyword2)){
				$links[$link]['result']='redirect';
			}
			else{
				$links[$link]['result']='success';
			}
		}
		else{
			continue;
		}
		
	}
	
	
	if(count($links)==0){
		return 'На странице не обнаружено ссылок на сайт';
	}
	else{
		
		foreach($links as $elem){
			if($elem['result'] == 'nofollow' || $elem['result'] == 'redirect')
				return $links;
		}
		return false;
	}

}

function create_report($data,$num_links){
	global $db_prefix;
	global $data_begin;
	global $data_end;
	$sql = "SELECT * FROM $db_prefix.`wrong_links` WHERE data = '$data'";
	$res = mysql_query($sql);
	$count = mysql_num_rows($res);
	if($count < 1)
		return false;
		
	$report = '<h1>Анализ ссылок для сайта "Руавтор" от '.$data.'</h1>';
	$report .= '<p>Проверка начата:'.$data_begin.', закончена '.$data_end.' </p>';
	$report .= '<p>Всего проверено ссылок: '.$num_links.'</p>';
	$report .= '<p>Битых ссылок выявлено: '.$count.'</p>';
	$report .= '<table><thead>Список битых ссылок</thead>';
	$report .= '<tr><th>№</th><th>URL</th><th>Проблема</th></tr>';
	for($i=0;$i<$count;$i++){
		$report .= '<tr>';
		$report .= '<td>'.mysql_result($res,$i,'num').'</td>';
		$report .= '<td>'.mysql_result($res,$i,'url').'</td>';
		$type_answer = mysql_result($res,$i,'type_answer');
		if($type_answer == 'array'){
			$report .= '<td>';
			$buffer = unserialize(mysql_result($res,$i,'answer'));
			$report .= '<table>';
			foreach($buffer as $key=>$value){
				$report .= '<tr>';
				$report .= '<td>'.$key.'</td><td>'.$value['result'].'</td>';
				$report .= '</tr>';
			}
			$report .= '</table>';
			$report .= '<td>';
		}
		else{
			$report .= '<td>'.mysql_result($res,$i,'answer').'</td>';
		}
		$report .= '</tr>';

	}
	$report .= '</table>';
	return $report;
}

function message_report($to,$from,$data,$report){
	global $email;
	$subject = 'Анализ ссылок на сайт "Руавтор" от '.$data;
	$email->mailtype = 'html';
	$email->from($from);
	$email->to($to);
	$email->subject($subject);
	$email->message($report);
	$res_email = $email->send();
//	echo 'from '.$from.' to '.$to.' subject '.$subject.' report '.$report;

	if($res_email)
		echo 'Sended!';
	else
		echo 'Error!';
}


function my_file_get_contents($link)
	{
		$ch = curl_init($link);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$screen = curl_exec($ch); 
    	curl_close($ch);  
		return $screen; 
	}

?>