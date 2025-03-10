<?php
define('BASEPATH','/');
$path = '/home/ruavtorr';
$root_path = $path.'/ruauthor.ru/';
$file_lib = $path.'/libs/DbSimple/Generic.php';
$file_config_site = $root_path.'application/config/config.php';
$file_helper_time=$root_path.'application/helpers/time_helper.php';
$file_helper_date=$root_path.'system/helpers/date_helper.php';
$file_helper_array=$root_path.'application/helpers/array_helper.php';
require_once($file_lib);
require_once($file_config_site);
require_once($file_helper_time);
require_once($file_helper_date);
require_once($file_helper_array);
$base_statistic = 'ruavtorr_ruauthor_statistic';
include_once($path.'/libs/Email.php');
class Email extends CI_Email{
	
	public function __construct($config = array())
	{
		if (count($config) > 0)
		{
			$this->initialize($config);
		}
		else
		{
			$this->_smtp_auth = ($this->smtp_user == '' AND $this->smtp_pass == '') ? FALSE : TRUE;
			$this->_safe_mode = ((boolean)@ini_get("safe_mode") === FALSE) ? FALSE : TRUE;
		}

//		log_message('debug', "Email Class Initialized");
	}
	

}


//$base = DbSimple_Generic::connect('mysql://'.$db['default']['username'].':'.$db['default']['password'].'@'.$db['default']['hostname'].'/'.$db['default']['database']);
$base_connect = mysql_connect($db['default']['hostname'],$db['default']['username'],$db['default']['password']);
$base = DbSimple_Generic::connect('mysql://ruavtorr:rcK%NQ4XFrZ#b9Nj*2lH7k2@9gD8nP5$aB7h@'.$db['default']['hostname'].'/'.$db['default']['database']);
mysql_select_db($db['default']['database']);
@mysql_query ("SET NAMES `utf8`");
if(!$base_connect)
	exit;
$hour = date('G'); 
$day = date('d');
$week_day = date('D');

if($day == 1){
	include('ruauthor/create_table_statistic.php');

}
 if($hour >= 0 and $hour < 2){
 	///////////////////////////////// Корректируем рейтинг /////////////////////////////////////
	include('ruauthor/reduce_rating.php');
 }
 elseif($hour >= 2 and $hour < 4){
 	///////////////////////////////// Работаем со статистикой сервера ///////////////////////////////////
	include('ruauthor/statistic.php');
	///////////////////////////////// Работаем с дохлыми ссылками на сайт ///////////////////////////////////

	include('ruauthor/checker_links.php');

 }
 
 elseif($hour >= 4 and $hour < 6){
 	///////////////////////////////// Подключаем скрипт, копирующий свежие бэкапы и подчищаюший старые /////////
//	echo 'copy backup <br>';
	include('ruauthor/top_authors.php');
//	include('ruauthor/copy_backup.php');
//	include('ruauthor/load_images.php');

	
 }
  elseif($hour >= 6 and $hour < 8 and $week_day == 'Sun'){
  	
 	///////////////////////////////// Подключаем скрипт, начисляющий финансы за статистику /////////
	include('ruauthor/statistic_finance.php');
	
 }
 
	///////////////////////////////// Подключаем скрипт, снимающий бронирование статей /////////
	include('ruauthor/unbooking.php');
	
	 ////////////////////////////////////////// Подключаем скрипт, помещающий статью в песочницу ///////////////////////////
 include('ruauthor/sandbox.php');
 is_quit_sandbox();
//include('ruauthor/statistic_finance.php');

?>