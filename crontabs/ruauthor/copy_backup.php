<?php

////////////////////////////////////////////// Запасной сервер ///////////////////////////////////////////////////
$ftp['server'] = 'wdc-backup3.ispsystem.net';
//$ftp['server'] = '188.120.247.35';
$ftp['login'] = 'ftp2524155';
$ftp['password'] = 'yV1Ro71N';

/*
$ftp_add['server'] = '144.76.17.104';
$ftp_add['login'] = 'ftp2524155';
$ftp_add['password'] = 'hW0LdYOx';
*/

$dump_name = date('Y-m-d');
$buffer_date = strtotime("-1 month");
$buffer_month_ago = unix_to_human($buffer_date,true);
$buffer = explode(' ',$buffer_month_ago);
$month_ago = $buffer[0];

//$buffer_date_avatars = strtotime("-7 days");
$buffer_date_avatars = strtotime("-2 days");
$buffer_week_ago = unix_to_human($buffer_date_avatars,true);
$buffer = explode(' ',$buffer_week_ago);
$week_ago = $buffer[0];

$buffer_date = strtotime("-1 day");
$buffer_day_ago = unix_to_human($buffer_date,true);
$buffer = explode(' ',$buffer_day_ago);
$day_ago = $buffer[0];

$bases = array('ruauthor','ruauthor_statistic');

//$conn_id = ftp_connect($ftp['server']) or die("Не удалось установить соединение с ".$ftp['server']); 
$conn_id = ftp_connect($ftp['server']); 
if(!$conn_id){
	echo("Don't connected with' ".$ftp['server']);
//	return;

}
/////////////////////////////////////////// Соединение с запасным vds ////////////////////////////////////////////

if (@ftp_login($conn_id, $ftp['login'], $ftp['password'])) {
    echo "You are come to ".$ftp['server']." by name ".$ftp['login']." <br>";
} 
else {
    echo("Unable to enter the name ".$ftp['login']."\n");
//	return;
}



// пытаемся сменить текущую директорию на somedir
if (ftp_chdir($conn_id, 'db')) {
   	echo "New current folder: " . ftp_pwd($conn_id) . "<br>";
}
 else
{ 
    echo("Unable change folder\n");
//	return;
}


$counter = 0;
foreach($bases as $base){
	$files[$counter]['source'] = '/var/www/backup/data/'.$base.'/'.$dump_name.'.sql.gz';
	$files[$counter]['destiny'] = $base.'.'.$dump_name.'.sql.gz';
	if($base == 'ruauthor_statistic')
		$files[$counter]['destiny_deleted'] = $base.'.'.$week_ago.'.sql.gz';
	else
		$files[$counter]['destiny_deleted'] = $base.'.'.$week_ago.'.sql.gz';
	upload_dump($conn_id,$files[$counter]);
	if($base == 'ruauthor_statistic')
		unlink('/var/www/backup/data/ruauthor/'.$week_ago.'.sql.gz');
	else
		unlink('/var/www/backup/data/ruauthor/'.$month_ago.'.sql.gz');
	
	$counter++;
}

if (ftp_cdup($conn_id)) { 
  echo "cdup выполнена success\n";
} else {
  echo "cdup error\n";
}


if (ftp_chdir($conn_id, 'files')) {
   	echo "New current folder: " . ftp_pwd($conn_id) . "<br>";
}
 else
{ 
    echo("Unable change folder\n");
//	return;
}


$images_source = '/var/www/backup/data/ruauthor/'.$dump_name.'.ruauthor.images.publications.tar.gz';
$images_destiny = $dump_name.'.ruauthor.images.publications.tar.gz';
$images_destiny_deleted = $week_ago.'.ruauthor.images.publications.tar.gz';
unlink('/var/www/backup/data/ruauthor/'.$week_ago.'.ruauthor.images.publications.tar.gz');

$images_source2 = '/var/www/backup/data/ruauthor/'.$dump_name.'.ruauthor.images.avatars.tar.gz';
$images_destiny2 = $dump_name.'.ruauthor.images.avatars.tar.gz';
$images_destiny_deleted2 = $week_ago.'.ruauthor.images.avatars.tar.gz';
unlink('/var/www/backup/data/ruauthor/'.$week_ago.'.ruauthor.images.avatars.tar.gz');


if (ftp_put($conn_id, $images_destiny, $images_source, FTP_ASCII)) {
    	echo "File ".$images_destiny." successfully uploaded <br>";
	}
 	else
	{
		ftp_close($conn_id);  
    	echo("Error uploading ".$images_destiny." \n");
//		return;
	}
	
// попытка удалить файл
	if (ftp_delete($conn_id, $images_destiny_deleted)) {
 		echo "File ".$images_destiny_deleted." successfully deleted\n";
	}
	else {
 		echo "Don't' deleted ".$images_destiny_deleted."\n";
	}
	

if (ftp_put($conn_id, $images_destiny2, $images_source2, FTP_ASCII)) {
    	echo "File ".$images_destiny2." successfully uploaded <br>";
	}
 	else
	{
		ftp_close($conn_id);  
    	echo("Error uploading ".$images_destiny2." \n");
//		return;
	}
	
// попытка удалить файл
	if (ftp_delete($conn_id, $images_destiny_deleted2)) {
 		echo "File ".$images_destiny_deleted2." successfully deleted\n";
	}
	else {
 		echo "Don't' deleted ".$images_destiny_deleted2."\n";
	}

	
ftp_close($conn_id);

/*	
/////////////////////////////////////////////// Соединение с запасным хостингом /////////////////////////////////
$conn_id = ftp_connect($ftp_add['server']) or die("Не удалось установить соединение с ".$ftp_add['server']); 


if (@ftp_login($conn_id, $ftp_add['login'], $ftp_add['password'])) {
    echo "You are come to ".$ftp_add['server']." by name ".$ftp_add['login']." <br>";
} 
else {
    echo("Unable to enter the name ".$ftp_add['login']."\n");
	return;
}
$root_directory = ftp_pwd($conn_id);


// пытаемся сменить текущую директорию на somedir
if (ftp_chdir($conn_id, 'backup')) {
   	echo "New current folder: " . ftp_pwd($conn_id) . "<br>";
}
 else
{ 
    echo("Unable change folder\n");
	return;
	
}

// пытаемся сменить текущую директорию на somedir
if (ftp_chdir($conn_id, 'db')) {
   	echo "New current folder: " . ftp_pwd($conn_id) . "<br>";
}
 else
{ 
    echo("Unable change folder\n");
	return;
}

$counter = 0;
foreach($bases as $base){
	$files[$counter]['source'] = '/var/www/backup/data/'.$base.'/'.$dump_name.'.sql.gz';
//	$files[$counter]['destiny'] = '/var/www/'.$ftp_add['login'].'/data/backup/db/'.$base.'.'.$dump_name.'.sql.gz';
	$files[$counter]['destiny'] = $base.'.'.$dump_name.'.sql.gz';
	$files[$counter]['destiny_deleted'] = $base.'.'.$month_ago.'.sql.gz';
	upload_dump($conn_id,$files[$counter]);
//	unlink('/var/www/backup/data/'.$base.'/'.$month_ago.'.sql.gz');
	
	$counter++;
}
if (ftp_cdup($conn_id)) { 
  echo "command cdup executed successfully\n";
} else {
  echo "command cdup has failed\n";
}

// пытаемся сменить текущую директорию на somedir
if (ftp_chdir($conn_id, 'files')) {
   	echo "New current folder: " . ftp_pwd($conn_id) . "<br>";
}
 else
{ 
    echo("Unable change folder\n");
	return;
}

$images_source = '/var/www/backup/data/ruauthor/'.$dump_name.'.ruauthor.publications.images.tar.gz';
$images_destiny = $dump_name.'.ruauthor.images.publications.tar.gz';
$images_destiny_deleted = $month_ago.'.ruauthor.images.publications.tar.gz';

$images_source2 = '/var/www/backup/data/ruauthor/'.$dump_name.'.ruauthor.avatars.images.tar.gz';
$images_destiny2 = $dump_name.'.ruauthor.images.avatars.tar.gz';
$images_destiny_deleted2 = $week_ago.'.ruauthor.images.avatars.tar.gz';
//unlink('/var/www/backup/data/files/'.$month_ago.'.ruauthor.images.tar.gz');

if (ftp_put($conn_id, $images_destiny, $images_source, FTP_ASCII)) {
    	echo "File ".$images_destiny." successfully uploaded <br>";
	}
 	else
	{
		ftp_close($conn_id);  
    	echo("Error uploading ".$images_destiny." \n");
		return;
}	

// попытка удалить файл
	if (ftp_delete($conn_id, $images_destiny_deleted)) {
 		echo "File ".$images_destiny_deleted." successfully deleted\n";
	}
	else {
 		echo "Don't' deleted ".$images_destiny_deleted."\n";
	}
	
	
if (ftp_put($conn_id, $images_destiny2, $images_source2, FTP_ASCII)) {
    	echo "File ".$images_destiny2." successfully uploaded <br>";
	}
 	else
	{
		ftp_close($conn_id);  
    	echo("Error uploading ".$images_destiny2." \n");
		return;
}	

// попытка удалить файл
	if (ftp_delete($conn_id, $images_destiny_deleted2)) {
 		echo "File ".$images_destiny_deleted2." successfully deleted\n";
	}
	else {
 		echo "Don't' deleted ".$images_destiny_deleted2."\n";
	}
*/

function upload_dump($conn_id,$files){
	// попытка закачивания файла
//	var_dump($files);
	if (ftp_put($conn_id, $files['destiny'], $files['source'], FTP_ASCII)) {
    	echo "File ".$files['destiny']." successfully uploaded <br>";
	}
 	else
	{
		ftp_close($conn_id);  
    	echo("Error uploading ".$files['destiny']." \n");
		return;
	}
	
	// попытка удалить файл
	if (ftp_delete($conn_id, $files['destiny_deleted'])) {
 		echo "File ".$files['destiny_deleted']." successfully deleted\n";
	}
	else {
 		echo "Don't' deleted ".$files['destiny_deleted']."\n";
	}
	
	
}


?>