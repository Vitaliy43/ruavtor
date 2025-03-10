<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
	Данный контроллер предназначен для импорта проекта с wordpress и kandidat

*/

class Migration extends MX_Controller{

	protected $auxiliary_db = 'for_migration';
	protected $import_db = 'ruavtor';
	protected $prefix_table_imdb = 'usersruavtorru_';
	protected $project_name = 'articler';
	protected $authors_page = 'D:\server\www\projects\ruavtor.old\articles\avtory\main.dat';
	protected $begin_id = 3;
	protected $articles_dir = 'D:\server\www\projects\ruavtor.old\articles';
	protected $list_authors_file = 'F:\projects\articler\authors.csv';
	protected $all_headings = array();
	protected $exceptions_files = array('.htaccess','index.php','main.dat');
	protected $my_connection;
		
	function __construct(){
		parent::__construct();
		if(!defined('LOCAL'))
			exit('Допуск только для локальной версии!');
				
        $this->load->library('DX_Auth');
		$this->load->helper('my_string');
		$this->load->helper('time');
		$this->load->model('dx_auth/users');
		$this->load->model('dx_auth/articler_users');
		
		if(!$this->dx_auth->is_logged_in() || $this->dx_auth->get_role_name() != 'admin')
			exit('Допуск только для админа!');
			
		$query = $this->db->get('headings');
		$this->all_headings = $query->result_array();
		$this->my_connection = mysql_connect('localhost','root','385818');
		mysql_select_db('articler_img',$this->my_connection);

	}
	
	public function users(){
		
		
		$import_table = $this->import_db.'.'.$this->prefix_table_imdb.'users';
		
		$sql = "SELECT * FROM $import_table WHERE ID = 458";
		$query = $this->db->query($sql);
		foreach($query->result_array() as $user){
			$data = array(
			'id' => $user['ID'],
			'role_id' => 1,
			'username' => $user['user_login'],
			'password' => $user['user_pass'],
			'email' => $user['user_email'],
			'banned' => 0,
			'last_ip' => '127.0.0.1',
			'last_login' => $user['user_registered'],
			'created' => $user['user_registered'],
			'modified' => $user['user_registered']
			);
			$this->db->insert('users', $data);
		
		}

	}
	
	public function assign_passwords(){
		
		//$ids = array();
		$this->db->where('id',458);
		$query = $this->db->get('users');
		foreach($query->result_array() as $user){
			$password = generate_password(10,3);
			$uniq = $user['username'].'-'.$password;
			$data = array(
			'username' => $user['username'],
			'password' => $password,
			'project_name' => $this->project_name,
			'uniq' => $uniq
			);
			$passwords_table = $this->auxiliary_db.'.passwords';
			$res = $this->db->insert($passwords_table, $data);
			if($res){
				$new_password = crypt($this->dx_auth->_encode($password));
				$result = $this->users->change_password($user['id'], $new_password);
				if($result)
					echo 'Пароль для '.$user['username'].' был успешно сменен <br>';
			}
			
		}
		
		
	}
	
	public function assign_author_parameters(){
		
		$query = $this->db->get_where('users', array('id' => 458));
		foreach($query->result_array() as $user){
			if($this->articler_users->is_setted_author_parameters($user['id'])):
			$res = $this->articler_users->set_author_parameters($user['id']);
			if($res)
				echo 'Дополнительные параметры для '.$user['username'].' назначены <br>';
			endif;
		}

	}
	
	public function parse_rating_authors(){
		
		$buffer = file($this->list_authors_file);
		$headings = $this->db->get('headings');
		foreach($headings as $heading){
			$arr[] = $heading['name'];
		}
		foreach($buffer as $str){
			$author_data = explode(';', win2utf($str));
			$buffer_url = $author_data[7];
			$rating = ($author_data[8]*1) + ($author_data[9]*1);
			list($http,$rest) = split('http://',$buffer_url);
			if($rest):
			$a = explode('/',$rest);
			$domain = $a[0];
			array_shift($a);
			$temp = '/'.implode('/',$a);
			$arr_domain = explode('.',$domain);
			
			if(count($arr_domain) == 3){
				$url = '/'.$arr_domain[0].$temp;	
			}
			else{
				$url = $temp;
			}
			$url = str_replace('/www','',$url);
			echo 'url <b>'.$url.'</b> rating <b>'.$rating.'</b> <br>';
//			echo '<b>Final_url - '.$url.' <br>';
			$query = $this->db->get_where('articles', array('url' => $url));
			
			if($rating > 0){
				$this->db->where('url', $url);
				$res = $this->db->update('articles', array('rating' => $rating));
				if($res)
					echo 'Статья "'.$url.'" изменила рейтинг! <br>';
			}
			
			endif;					
			
		}
	}
	
	public function assign_purse(){
		
		$import_table = $this->import_db.'.'.$this->prefix_table_imdb.'users';	
		$begin_id = 3;
		$sql = "SELECT user_url,user_login FROM $import_table WHERE ID >= $begin_id AND user_url != '' ";
		$query = $this->db->query($sql);
		foreach($query->result_array() as $user){
			list($prefix,$purse) = split('//',$user['user_url']);
			$query_users = $this->db->get_where('users', array('username' => $user['user_login']));
			$res = $this->articler_users->update_purse($query_users->row()->id, $purse);
			if($res)
				echo 'Кошелек для '.$user['user_login'].' назначен <br>';
		}
	}
	
	public function assign_author_names(){
		
		$import_table = $this->import_db.'.'.$this->prefix_table_imdb.'usermeta';	
		$query = $this->db->get_where('users', array('role_id' => 1));
		foreach($query->result_array() as $user){
			$sql = "SELECT meta_value FROM $import_table WHERE user_id = ".$user['id']." AND meta_key = 'first_name'";
			$query_first_name = $this->db->query($sql);
			$first_name = $query_first_name->row()->meta_value;
			$sql = "SELECT meta_value FROM $import_table WHERE user_id = ".$user['id']." AND meta_key = 'last_name'";
			$query_last_name = $this->db->query($sql);
			$last_name = $query_last_name->row()->meta_value;
			$data = array(
			'id' => null,
			'user_id' => $user['id'],
			'name' => $first_name,
			'family' => $last_name
			);
			if($query_first_name->row() && $query_last_name->row()){
				$res = $this->db->insert('authors', $data);
				if($res)
					echo 'Имя и фамилии для  '.$user['username'].' назначены <br>';
			}
		}
	}
	
	public function parse_authors(){
		
		$screen = file_get_contents($this->authors_page);
		$screen = win2utf($screen);
		list($noneed,$need) = split('<table', $screen);
		list($need,$noneed) = split('</table', $need);
		$tr_array = explode('</tr>', $need);
		foreach($tr_array as $tr){
			$td_array = explode('</td>', $tr);
			$login = strip_tags($td_array[1]);
			$login = trim($login);
			
			if(mb_strlen($login) <= 1){
			
			
			}
			else{
				
				$payments = strip_tags($td_array[4]);
				$payments = trim($payments);
				$payouts = strip_tags($td_array[5]);
				$payouts = trim($payouts);
				$payouts = str_replace('(','',$payouts);
				$payouts = str_replace(')','',$payouts);
				$payouts = str_replace('+','',$payouts);
				$rating = strip_tags($td_array[6]);
				$rating = trim($rating);
				$login = str_replace('&nbsp;','',$login);
				$rating = str_replace('&nbsp;','',$rating);
				$payments = str_replace('&nbsp;','',$payments);
				$payouts = str_replace('&nbsp;','',$payouts);
				$query = $this->db->get_where('users', array('username' => $login));
				echo 'username '.$login.' user_id '.$query->row()->id.' rating "'.$rating.'" payments "'.$payments.'" payouts "'.$payouts.'"<br>';
				
				$date = date("Y-m-d H:i:s");
				if($query->row()->id){
					$data = array(
					'id' => null,
					'user_id' => $query->row()->id,
					'article_id' => 0,
					'payment' => $payments * 1,
					'balance' => 0,
					'data' => $date
					);
					if($payments > 0):
					$res = $this->db->insert('articler_payments', $data);
					if($res)
						echo 'Платежи для '.$login.' добавлены! <br>';
					else
						echo 'Платежи для '.$login.' <b>не</b> добавлены! <br>';
					endif;

					
					$data = array(
					'id' => null,
					'user_id' => $query->row()->id,
					'payout' => $payouts * 1,
					'balance' => 0,
					'data_order' => $date,
					'data_payout' => $date,
					'status' => 1
					);
					if($payouts > 0):
					$res = $this->db->insert('articler_payouts', $data);	
					if($res)
						echo 'Выплаты для '.$login.' добавлены! <br>';
					else
						echo 'Выплат для '.$login.' <b>не</b> добавлены! <br>';

					endif;
					
					if($rating > 0):
					$this->db->where('user_id',$query->row()->id);
					$res = $this->db->update('ratings', array('rating' => $rating * 1, 'data_modified' => $date));
					if($res)
						echo 'Рейтинг для '.$login.' обновлен! <br>';
					else
						echo 'Рейтинг для '.$login.' <b>не </b> обновлен! <br>';
					endif;

				}
			}
		}
	}
	
	
	
	protected function parse_article($content, $update = false){
		
		if(!$update):
		
		list($empty,$buffer_title,$end) = split('<!-- Kan_title -->',$content);
		$title = trim($buffer_title);
		list($empty,$buffer_annotation,$end) = split('<!-- Kan_description -->',$content);
		$annotation = trim($buffer_annotation);
		list($noneed,$need) = split('<p style="text-align: justify;"><em>',$content);
		list($date,$noneed) = split('</em>',$need);
		list($noneed,$need) = split('<p style="text-align: right;">',$content);
		list($need,$text) = split('<p style="text-align: left;">',$need);
		$buffer = explode('<strong>',$need);
		$author = str_replace('Рубрика:', '',$buffer[1]);
		$rubric = trim($buffer[2]);
		$buffer_text = explode('<!-- Kan_content -->', $content);
		$text = $buffer_text[1];
		$text = str_replace('<a id="a" name="a" target="_blank">Обсуждение</a>','',$text);
		$text = str_replace('<p style="text-align: justify;"><em>'.$date.'</em></p>','',$text);
		list($a,$b) = split('Автор:',$content);
		list($buffer_author,$shlock) = split('</strong>',$b);
		$author = strip_tags($buffer_author);
		$author = trim($author);
		$author = ltrim($author,'&nbsp;');
		preg_match('#<em>\d+\s+(января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря)\s+\d{4}</em>?#',$content,$matches);
		$date = strip_tags($matches[0]);
		$author = strip_tags($author);
		$author = trim($author);
		$result = array(
		'title' => $title,
		'date' => $date,
		'author' => $author,
		'rubric' => $rubric,
		'text' => $text,
		'annotation' => $annotation
		);
		
		else:
			$pattern_time = '#<em>\d+\s+(января|февраля|марта|апреля|мая|июня|июля|августа|сентября|октября|ноября|декабря)\s+\d{4}</em>?#';
			$buffer = explode('</p>',$content);
			if(mb_strpos($buffer[1],'Автор') && mb_strpos($buffer[1],'Рубрика') && preg_match($pattern_time,$buffer[0],$regs)){
				array_shift($buffer);
				array_shift($buffer);
			}
			elseif(mb_strpos($buffer[1],'Автор') && preg_match($pattern_time,$buffer[0],$regs)){
				array_shift($buffer);
				array_shift($buffer);
			}
			elseif(mb_strpos($buffer[1],'Автор') && mb_strpos($buffer[2],'Рубрика') && preg_match($pattern_time,$buffer[0],$regs)){
				array_shift($buffer);
				array_shift($buffer);
			}
		
			elseif(mb_strpos($buffer[0],'Автор') && mb_strpos($buffer[0],'Рубрика')){
				array_shift($buffer);
			}
			elseif(mb_strpos($buffer[0],'Автор') && mb_strpos($buffer[1],'Рубрика')){
				array_shift($buffer);
				array_shift($buffer);
			}
			else{
				return $content;
			}
		
		$result = implode('</p>',$buffer);
		if(mb_strpos($result,'script type="text/javascript"')){
			list($result,$javascript) = split('<script type="text/javascript"',$result);
		}
		
		endif;
		
	return $result;
		
	}
	
	
	
	public function update_articles(){
	
		$sql = "SELECT content,id,header FROM articles WHERE user_id < 480";
		$query = $this->db->query($sql);
		foreach($query->result_array() as $article){
			$content = $this->parse_article($article['content'], true);
			$old_length = mb_strlen($article['content']);
			$new_length = mb_strlen($content);
			$this->db->where('id', $article['id']);
			$res = $this->db->update('articles', array('content' => $content));
			if($res && $new_length < $old_length)
				echo '<p style="color:green;">Статья "'.$article['header'].'" успешно отредактирована!</p>';
			else
				echo '<p style="color:red;">Статью "'.$article['header'].'" с id '.$article['id'].' отредактировать не удалось</p>';
				
				
		}
	}
	
	public function get_articles(){
		

		foreach($this->all_headings as $heading){
			
			$dir_pointer = opendir($this->articles_dir.DIRECTORY_SEPARATOR.$heading['name']);

			while (($res = readdir($dir_pointer))!==FALSE)
			{
  			// вывод имен файлов и папок
			if(!in_array($res,$this->exceptions_files)):
				$buffer = explode('-',$res);
				$check_on_number_first_elem = preg_match('#\d+#',$buffer[0],$matches);
				if($check_on_number_first_elem){
					array_shift($buffer);
					$date = array_pop($buffer);
				}
				else{
					$date = array_pop($buffer);
				}
	
				
				if(strlen($res) > 2):
				
					$new_url = str_replace('.dat','.html',$res);
					$new_url = '/'.$heading['name'].'/'.$new_url;
					$filename = $this->articles_dir.DIRECTORY_SEPARATOR.$heading['name'].DIRECTORY_SEPARATOR.$res;
					$screen = file_get_contents($filename);
					$screen = win2utf($screen);
					$parse_result = $this->parse_article($screen);
					$date = time_text_data($parse_result['date'], true);
					$buffer_user = $this->articler_users->get_user_id_by_author($parse_result['author']);
					
					if($buffer_user and $parse_result['title']){
					
					$data = array(
					'id' => null,
					'heading_id' => $heading['id'],
					'user_id' => $buffer_user->user_id,
					'header' => $parse_result['title'],
					'url' => $new_url,
					'annotation' => $parse_result['annotation'],
					'content' => $parse_result['text'],
					'activity' => 2,
					'data_saved' => $date,
					'data_last_modified' => $date,
					'data_moderated' => null,
					'data_published' => $date,
					'rating' => 0
					);
				
				
					$result = $this->db->insert('articles', $data);
					if($result)
						echo '<p style="color:green;">Статья '.$parse_result['title'].' автора '.$parse_result['author'].' из раздела '.$heading['name_russian'].' успешно вставлена </p>';
					else
						echo '<p style="color:red;">Статья '.$parse_result['title'].' автора '.$parse_result['author'].' из раздела '.$heading['name_russian'].' не  вставлена! </p>';
				}
				else{
					
					
					echo '<p style="color:red;">Ошибка!Автор "'.$parse_result['author'].'" для файла '.$res.' не найден! </p>';
				}
				
				
				endif;
				
				endif;
				
			
			}
			// закрытие каталога
			closedir($dir_pointer);
		}

	}
	
	public function assign_heading_id(){
		
		foreach($this->all_headings as $heading){
			
			$dir_pointer = opendir($this->articles_dir.DIRECTORY_SEPARATOR.$heading['name']);
			echo $heading['name'].'<br>';
			echo $heading['id'].'<br>';
			while (($res = readdir($dir_pointer))!==FALSE)
			{
  			// вывод имен файлов и папок
				$buffer = explode('-',$res);
				$check_on_number_first_elem = preg_match('#\d+#',$buffer[0],$matches);
				if($check_on_number_first_elem){
					array_shift($buffer);
					array_pop($buffer);
				}
				else{
					array_pop($buffer);
				}
				$new_url = implode('-',$buffer);
				$new_url = str_replace('-video','',$new_url);
				$new_url = '/'.$new_url.'.html';
				if($new_url != '/.html'):
				$this->db->where('url',$new_url);
				$result = $this->db->update('articles', array('heading_id' => $heading['id']));
				if($result)
					echo 'heading_id для url '.$new_url.' назначен! <br>';
				endif;
				
			}
			// закрытие каталога
			closedir($dir_pointer);
		}
	}
	
}