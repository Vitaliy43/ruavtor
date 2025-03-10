<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Statistic_model extends CI_Model{

	public $repository_statistic;
	protected $table_statistic;
	public $use_limit_visites;
	public $limit_visites;
	public $data_using_correct_rating = '2017-02';

	function __construct()
	{
		parent::__construct();
		$this->repository_statistic = $this->config->item('repository_statistic');
		$this->init();
	}
	
	function init()
	{

		$this->use_limit_visites = $this->settings_model->get_setting('statistic_settings','use_limit_visites','property');
		if($this->use_limit_visites)
			$this->limit_visites = $this->settings_model->get_setting('statistic_settings','limit_visites','property');
			
		$table = $this->repository_statistic.'.page_visites_'.date('Y').'_'.date('n');
		if(!$this->db->table_exists($table)){
			$res = $this->create_table_statistic();
			if($res)
				$this->table_statistic = $table;
		}
		else{
			$this->table_statistic = $table;
		}
	}
	
	function is_enabled_visit($data, $ip)
	{
		if($this->dx_auth->get_user_id())
			return false;
		$this->db->where('brief_data', $data);
		$this->db->where('ip', $ip);
		$this->db->from($this->table_statistic);
		$num_visites = $this->db->count_all_results();
		if($num_visites >= $this->limit_visites)
			return false;
		return true;
	}
	
	function set_visit($datetime, $article_id, $ip, $user_id)
	{
		if(strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))
				return false;
		if($this->isBot())
			return false;
		$buffer = explode(' ',$datetime);
		$data = $buffer[0];
		$time = $buffer[1];
		if($this->use_limit_visites){
			if(!$this->is_enabled_visit($data, $ip))
				return false;
		}
		$uniq = $data.' '.$ip;
		$insert_array = array(
		'id' => null,
		'data' => $datetime,
		'brief_data' => $data,
		'article_id' => $article_id,
		'ip' => $ip,
		'user_id' => $user_id,
		'uniq' => $uniq
		);
		if(!$this->table_statistic)
			return false;
		$query = $this->db->insert_string($this->table_statistic,$insert_array);
		$query = str_replace('INSERT INTO', 'INSERT IGNORE INTO',$query);
		$res = $this->db->query($query);
		return $res;
	}
	
	function create_table_statistic()
	{
		$table_prefix = date('Y').'_'.date('n');
		$sql = "
		CREATE TABLE IF NOT EXISTS $this->repository_statistic.`page_visites_$table_prefix` (
  	`id` int(11) NOT NULL auto_increment,
  	`data` datetime NOT NULL,
  	`brief_data` date NOT NULL,
  	`article_id` int(11) NOT NULL,
  	`ip` varchar(20) NOT NULL,
  	`user_id` int(11) default NULL,
  	`uniq` varchar(30) NOT NULL,
  	PRIMARY KEY  (`id`),
  	UNIQUE KEY `uniq` (`uniq`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

	";
		$res = $this->db->query($sql);
		return $res;

	}

	function average_num_visites($article_id = null,&$result_source = 0)
	{
		$corrective_q = $this->articler_model->corrective_q;
		$this->db->select_sum('num_all');
		$prev_data = date('Y-m-d',strtotime('-1 day'));
		if($article_id)
			$this->db->where('article_id', $article_id);
		$this->db->where('data', $prev_data);
		$query = $this->db->get($this->repository_statistic.'.statistic_day');
		$result = round($query->row()->num_all * $corrective_q);
		$result_source = round($query->row()->num_all);
		
//		$result=sprintf("%01.1f",$result);
		$result=number_format(round($result),0,',',' ');
;
		return $result;
	}
	
	function all_num_visites($article_id = null,&$result_source = 0){
		
		$corrective_q = $this->articler_model->corrective_q;
		if($article_id)
			$where = 'article_id = '.$article_id;
		else
			$where = 1;
		$sql1 = "SELECT SUM(num_all) AS 'num_all' FROM ".$this->repository_statistic.'.statistic_day'." WHERE $where AND `data` < '".$this->data_using_correct_rating."'";
		$query = $this->db->query($sql1);
		$result1 = $query->row()->num_all;
		$sql2 = "SELECT SUM(num_all) AS 'num_all' FROM ".$this->repository_statistic.'.statistic_day'." WHERE $where AND `data` >= '".$this->data_using_correct_rating."'";
		$query = $this->db->query($sql2);
		$result_source = $result1 + $query->row()->num_all;
		$result2 = round($query->row()->num_all*$corrective_q);
		$result = round($result1 + $result2);
		$result = number_format($result,0,',',' ');
		$result_source = number_format($result_source,0,',',' ');
		return $result;
	}
	
	function average_num_visites_correct($article_id = null)
	{
		$corrective_q = $this->articler_model->corrective_q;
		$this->db->select_sum('num_all');
		$prev_data = date('Y-m-d',strtotime('-1 day'));
		if($article_id)
			$this->db->where('article_id', $article_id);
		$this->db->where('data', $prev_data);
		$query = $this->db->get($this->repository_statistic.'.statistic_day');
		$result = round($query->row()->num_all * $corrective_q);
//		$result=sprintf("%01.1f",$result);
		$result=number_format(round($result),0,',',' ');
		return $result;
	}
	
	function all_num_visites_correct($article_id = null){
		
		$corrective_q = $this->articler_model->corrective_q;
		$this->db->select_sum('num_all');
		if($article_id)
			$this->db->where('article_id', $article_id);
		$query = $this->db->get($this->repository_statistic.'.statistic_day');
		$result = round($query->row()->num_all * $corrective_q);
//		$result=sprintf("%01.1f",$result);
		$result=number_format(round($result),0,',',' ');
		return $result;
	}
	
	function get_visites_by_user_id($user_id,$type = 'avg', $all = true, &$result_source = 0){
		$corrective_q = $this->articler_model->corrective_q;
		if($type == 'avg'){
			$sql = "SELECT AVG(num_all) AS 'num_all' FROM ".$this->repository_statistic.".statistic_day  WHERE article_id IN (SELECT id FROM ".$this->db->database.".articles WHERE user_id = $user_id) AND `data` < '".$this->data_using_correct_rating."'";
			$query = $this->db->query($sql);
			$result1 = $query->row()->num_all;
			$sql = "SELECT AVG(num_all) AS 'num_all' FROM ".$this->repository_statistic.".statistic_day  WHERE article_id IN (SELECT id FROM ".$this->db->database.".articles WHERE user_id = $user_id) AND `data` >= '".$this->data_using_correct_rating."'";
			$query = $this->db->query($sql);
			$result2 = round($query->row()->num_all*$corrective_q,1);
			$result = round($result1 + $result2,1);
		}
		else{
			if($all){
				$sql1 = "SELECT SUM(num_all) AS 'num_all' FROM ".$this->repository_statistic.'.statistic_day'." WHERE article_id IN (SELECT id FROM ".$this->db->database.".articles WHERE user_id = $user_id) AND `data` < '".$this->data_using_correct_rating."'";
				$query = $this->db->query($sql1);
				$result1 = $query->row()->num_all;
				$sql2 = "SELECT SUM(num_all) AS 'num_all' FROM ".$this->repository_statistic.'.statistic_day'." WHERE article_id IN (SELECT id FROM ".$this->db->database.".articles WHERE user_id = $user_id) AND `data` >= '".$this->data_using_correct_rating."'";
				$query = $this->db->query($sql2);
				$result_source = $result1 + $query->row()->num_all;
				$result2 = round($query->row()->num_all*$corrective_q);
				$result = round($result1 + $result2);

			}
			else{
				$prev_data = date('Y-m-d',strtotime('-1 day'));
				$sql = "SELECT SUM(num_all) AS 'num_all' FROM ".$this->repository_statistic.'.statistic_day'." WHERE article_id IN (SELECT id FROM ".$this->db->database.".articles WHERE user_id = $user_id) AND data = '$prev_data'";
				$query = $this->db->query($sql);
				$result_source = $query->row()->num_all;
				$result = round($query->row()->num_all*$corrective_q);
				
			}
		
		}
		
//		$result=sprintf("%01.1f",$result);
		return $result;
	}
	
	function isBot(&$botname = ''){
/* Эта функция будет проверять, является ли посетитель роботом поисковой системы */
  $bots = array(
    'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
    'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
    'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
    'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
    'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
    'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
    'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
    'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
    'Nigma.ru','bing.com','dotnetdotcom'
  );
  foreach($bots as $bot)
    if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
      $botname = $bot;
      return true;
    }
  return false;
}
	
}

?>