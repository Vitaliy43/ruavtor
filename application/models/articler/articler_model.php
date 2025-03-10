<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Articler_model extends CI_Model {

	public $message_error= 0;
	public $blocked_time = 7200;
	public $rating_for_show = array();
	public $no_sandbox;
	public $sandbox_hold = 30;
	public $rating_by_publish = 10;
	public $site_mail = 'mail@ruavtor.ru';
//	public $site_mail = 'ruavtor.ru@gmail.com';
	public $add_plus = 1;
	public $add_comment = 2;
	public $num_last_articles_sandbox = 5;
	public $num_last_comments = 10;
	public $lowest_rating_activity;
	public $lowest_sum_for_pay;
	public $highest_rating_activity;
	public $quantity_articles_for_assess;
	public $site_url_without_slash;
	public $result_scan_outer_link = array();
	public $settings;
	public $bonus_for_homefeed;
	public $last_ruavtor_article;
	public $num_visites_for_pay;
	public $pay_for_visit;
	public $corrective_q;
	public $modal_box_for_user;
	public $modal_box_for_guest;
	public $period_booking = 172800;
	public $use_divided_rating_show = true;
	public $refer_expire = 86400;
	public $ban_after_register;
//	public $refer_add = 5184000;
	
	protected $author_statuses = array(
	'Публицист' => 'publicist',
	'Журналист' => 'journalist',
	'Эксперт' => 'expert'
	);
	
	public $forbidden_headings = array('novosti','auth','admin','feedback');
	
	function __construct()
	{
		parent::__construct();
		$this->init();
	}
	
	////////////////////////////////////// Получение текущих настроек модуля ////////////////////////////////
	
	function init(){
		
		$this->last_ruavtor_article = strtotime("29-07-2013");
		$this->settings = $this->settings_model->get_all();
		$rating_activity = array_group($this->settings,'rating_activity','type');
		$rating_author = array_group($this->settings,'rating_author','type');
		$comments_system = array_group($this->settings,'comments_system','type');
		$finance_system = array_group($this->settings,'finance_system','type');
		$refer_system = array_group($this->settings,'refer_system','type');
		$interface_settings = array_group($this->settings,'interface_settings','type');
		$statistic_settings = array_group($this->settings,'statistic_settings','type');
		$user_settings = array_group($this->settings,'user_settings','type');
	
		$this->add_comment = $this->settings_model->get_setting_from_array($rating_activity, 'activity_day');
		$this->lowest_sum_for_pay = $this->settings_model->get_setting_from_array($finance_system, 'lowest_sum_for_pay');
		$this->lowest_rating_activity = $this->settings_model->get_setting_from_array($rating_activity, 'lowest_rating');
		$this->highest_rating_activity = $this->settings_model->get_setting_from_array($rating_activity, 'highest_rating');
		$this->num_visites_for_pay = $this->settings_model->get_setting_from_array($finance_system, 'num_visites_for_pay');
		$this->pay_for_visit = $this->settings_model->get_setting_from_array($finance_system, 'pay_for_visit');
		$this->corrective_q = $this->settings_model->get_setting_from_array($finance_system, 'corrective_q');
		foreach($this->settings_model->type_headings as $type_heading){
			$this->rating_for_show[$type_heading['id']] = $this->settings_model->get_setting_from_array($rating_author, 'rating_for_homefeed_'.$type_heading['id']);

		}
		$buffer = array_unique($this->rating_for_show);
		if(count($buffer) == 1)
			$this->use_divided_rating_show = false;
		
		$this->sandbox_hold = $this->settings_model->get_setting_from_array($rating_author, 'hold_for_homefeed');
		$this->rating_by_publish = $this->settings_model->get_setting_from_array($rating_author, 'add_for_homefeed');
		$this->bonus_for_homefeed = $this->settings_model->get_setting_from_array($rating_author, 'bonus_for_homefeed');
		$this->num_last_comments = $this->settings_model->get_setting_from_array($comments_system, 'num_last_comments');
		$this->quantity_articles_for_assess = $this->settings_model->get_setting_from_array($comments_system, 'quantity_articles_for_assess');
		$this->num_last_articles_sandbox = $this->settings_model->get_setting_from_array($interface_settings, 'num_last_articles_sandbox');
		$this->modal_box_for_user = $this->settings_model->get_setting_from_array($interface_settings, 'modal_box_for_user');
		$this->modal_box_for_guest = $this->settings_model->get_setting_from_array($interface_settings, 'modal_box_for_guest');
		$this->refer_add = $this->settings_model->get_setting_from_array($refer_system, 'add') * 86400;
		$buffer_url = site_url();
		if(mb_substr($buffer_url,-1,1)=='/'){
			$this->site_url_without_slash = mb_substr($buffer_url,0,-1);
		}
		$this->ban_after_register = $this->settings_model->get_setting_from_array($user_settings, 'ban_after_registration');

	
	}
	
	////////////////////////////////////// Работа со внешней ссылкой на сайт: добавление редактирование///////////////
	
	function outer_link($article_id, $link, $data){
		
		$query = $this->db->get_where('links_to_site',array('article_id' => $article_id));
		
		if($query->row()){
		
			$update_array = array(
			'link' => $link,
			'domain' => $data['success']['domain'],
			'type' => $data['success']['type']
			);
			if(isset($data['success']['articles']))
				$update_array['articles'] = $data['success']['articles'];
			$this->db->where('article_id',$article_id);
			$res = $this->db->update('links_to_site',$update_array);
		}
		else{
			
			$insert_array = array(
			'id' => null,
			'article_id' => $article_id,
			'link' => $link,
			'domain' => $data['success']['domain'],
			'type' => $data['success']['type']
			);
			if(isset($data['success']['articles']))
				$insert_array['articles'] = $data['success']['articles'];
			$res = $this->db->insert('links_to_site',$insert_array);
		}
		return $res;
	}
	
	//////////////////////////////////// Возвращаем список авторов и юзеров в виде js-файла ///////////////////////////
	function get_users_and_authors_js(){
		$js = 'var authors = [';
		$sql = "SELECT users.username,authors.name,authors.family,COUNT(articles.id) AS num_articles FROM users JOIN authors ON users.id = authors.user_id JOIN articles ON articles.user_id = users.id GROUP BY users.username";
		$query = $this->db->query($sql);
		foreach($query->result_array() as $row){
			$js .= '"'.$row['username'].'"'.',';
			if($row['name'] && $row['family']){
				$name = $row['name'].' '.$row['family'];
				$js .= '"'.$name.'"'.',';
			}
		}
		$js = mb_substr($js,0,-1);
		$js .= '];';
		return $js;
	}
	
	//////////////////////////////////// Проверка указанной ссылки на соответствие определенным требованиям ///////////
	
	function scanner_outer_link($link){
		
		$query = $this->db->get_where('links_to_site',array('link' => $link));
		if($query->row()){
			$result['error'] = 'Ссылка не является уникальной!';
			return $result;

		}
		
		$screen = my_file_get_contents($link);

		if(strpos($screen,'http://ruavtor.ru') || strpos($screen,'http://www.ruavtor.ru')){
		
		
			
		}
		else{
			
		
			$result['error'] = 'Страница не содержит ссылки на сайт Руавтор!';
			return $result;

		}
		
		$buffer = preg_match('#http:\/\/[a-z0-9-]+#',$link,$matches);
		$buffer1 = explode('ttp://',$link);
		$buffer2 = explode('/',$buffer1[1]);
		if(!$buffer2[0]){
			$result['error'] = 'Некорректный URL!';
			return $result;
		}
		$result['success']['domain'] = 'http://'.$buffer2[0];
		
		$pattern = '#ruavtor.ru'.'\/[a-z0-9-]+\/[a-z0-9-]+\.html#';

		$res = preg_match_all($pattern, $screen, $matches);
		if($res){
			$result['success']['type'] = 'article';
			$result['success']['articles'] = serialize($matches[0]);
		}
		else{
			$result['success']['type'] = 'site';
		}
				
		return $result;
		
	}
    
    ////////////////////////////////////// Проверка возможности удаления комментария юзером //////////////////
    
    function is_possible_delete_comment($comment,$user_id){
        
        if($comment['user_id'] != $user_id)
            return false;
        if($comment['answered'])
            return false;
        if(mktime() - strtotime($comment['data']) > 86400)
            return false;
		return true;
        
    }
	
	////////////////////////////////////// Редактирование статьи юзером //////////////////////////////////////
	
	function update_article($id){
	
		$heading = $this->get_heading_by_id($this->input->post('headings'));
		$buffer_headings = $this->get_heading_by_id($_REQUEST['headings']);
		$user_id = $this->dx_auth->get_user_id();

		$url = $this->input->post('url');	
		if(isset($_POST['url'])){
			$new_url = '/'.$buffer_headings->name.'/'.$this->input->post('url').'_'.$id.'.html';

		}
		
		$query = $this->db->get_where('articles',array('id' => $id));
			
		if(empty($_REQUEST['is_published'])):
			
			$errors = $this->validate_publication($heading, 1);
		
			if(count($errors) > 0)
				return $errors;	
					
		
		if($query->row()->activity == 2){
			$arr['errors']['is_published'] = 'Не допускается редактирование опубликованной статьи!';
			return $arr;
		}
		elseif($this->is_blocked($id)){
			$arr['errors']['is_blocked'] = 'Не допускается редактирование статьи находящейся на модерации!';
			return $arr;
		}
		
		$buffer = explode('/',$query->row()->url);
		$curr_url = $buffer[1];
		$curr_article = $buffer[2];
		
		endif;


		if(isset($_REQUEST['headings'])){
			$data['heading_id'] = $this->input->post('headings');
			if(isset($new_url) && strtotime($query->row()->data_published) > $this->last_ruavtor_article)
				$data['url'] = $new_url;

		}
		if(isset($_REQUEST['header']))
			$data['header'] = $this->input->post('header');
			
		$data['content'] = $this->input->post('editor');
		
		if(mb_strlen($this->input->post('annotation')) > 300){
			$annotation = mb_substr($this->input->post('annotation'),0,300);
		}
		else{
			$annotation = $this->input->post('annotation');
		}

        if(mb_strlen($this->input->post('description')) > 200){
            $description = mb_substr($this->input->post('description'),0,200);
        }
        else{
            $description = $this->input->post('description');
        }

        if(mb_strlen($this->input->post('keywords')) > 100){
            $keywords = mb_substr($this->input->post('keywords'),0,100);
        }
        else{
            $keywords = $this->input->post('keywords');
        }

		$data['annotation'] = $annotation;
        $data['description'] = $description;
        $data['keywords'] = $keywords;

		$data['data_last_modified'] = date("Y-m-d H:i:s");
		
		$response_upload = array();
		$res_upload = $this->load_image_article($user_id, $response_upload);
		if($res_upload){
			if($response_upload['error']){
				$res['errors']['upload_image'] = $response_upload['error'];
				return $res;
			}
			else{
				$data['image'] = $response_upload['file'];
			}
		}
		
		$this->db->where('id',$id);	
		$res = $this->db->update('articles',$data);
		
		return $res;
		
	}
	
	function get_article_owner_user_id($key,$value){
		
		$this->db->select('user_id');
		$this->db->from('articles');
		$this->db->where($key,$value);
		$query = $this->db->get();
		return $query->row()->user_id;
	}
	
	function get_author_profile($user_id){
		
		$query = $this->db->get_where('authors', array('user_id' => $user_id));
		return $query->row();
	}
	
	function change_author_profile($user_id){
		
		$name = $this->input->post('name');
		$family = $this->input->post('family');
		if(!$name)
			return false;
		if(!$family)
			return false;
		
		$query = $this->db->get_where('authors', array('user_id' => $user_id));
		if($query->row()){
			$data = array(
			'name' => $name,
			'family' => $family
			);
			$this->db->where('user_id', $user_id);
			$res = $this->db->update('authors', $data);
		}
		else{
			
			$data = array(
			'id' => null,
			'user_id' => $user_id,
			'name' => mb_ucfirst($name),
			'family' => mb_ucfirst($family),
			'fullname' => mb_ucfirst($name).' '.mb_ucfirst($family)
			);
			$res = $this->db->insert('authors', $data);
		}
		
		return $res;
	}
	
	/////////////////////////////////////////////// Анонсы последних статей //////////////////////////////////////////
	function last_articles(){
		
		$rating = array_shift($this->rating_for_show);
		$time_hold = $this->sandbox_hold * 86400;
		$mktime = mktime();
		$this->db->select('header,url,in_sandbox,rating,UNIX_TIMESTAMP(data_published) AS data');
		$this->db->from('articles');
		$this->db->where('activity',2);
		$this->db->order_by('data_published','desc');
		$this->db->limit($this->num_last_articles_sandbox);
		$query = $this->db->get();
		$arr = $query->result_array();
		foreach($arr as $key=>$value){
			if($value['rating'] < $rating || ($value['data'] + $time_hold) > $mktime)
				$arr[$key]['in_sandbox'] = "(статья в <a href=\"".site_url('pesochnica')."\" target=\"_blank\" style=\"color:#FF3000;\">Песочнице</a>) ";
			else
				$arr[$key]['in_sandbox'] = '';

		}
		return $arr;
	}
	
	
	///////////////////////// Назначение или смена кошелька через модальное окно//////////////////////////////////
	
	function change_purse($user_id, $finance){
		
		if($finance->purse != null){
			$data = array(
			'purse' => $this->input->post('new_purse')
			);
			$this->db->where('user_id', $user_id);
			$res = $this->db->update('articler_score', $data);
		}
		else{
			$data = array(
			'purse' => $this->input->post('purse')
			);
			$this->db->where('user_id', $user_id);
			$res = $this->db->update('articler_score', $data);
		}
		
		return $res;
	}
	
	/////////////////////////////////////////////// Удаление коммента ////////////////////////////////////////////
	
	function delete_comment($id,$private=false){
		
		if($private)
			$res = $this->db->delete('private_comments', array('id' => $id));
		else
			$res = $this->db->delete('article_comments', array('id' => $id));
		return $res;
	}
	
	////////////////////////////////////////////// Редактирование коммента //////////////////////////////////////////////
	
	function edit_comment($id,$user_id){
		
		$data = array(
		'comment' => strip_tags($this->input->post('comment')),
		'data_modified' => date("Y-m-d H:i:s"),
		'editor_id' => $user_id
		);
		$this->db->where('id', $id);
		if(isset($_POST['private']) && $_POST['private'])
			$res = $this->db->update('private_comments',$data);		
		else
			$res = $this->db->update('article_comments',$data);
		if($res)
			return true;
		return false;
	}
	
	////////////////////////////////////////////// Получение коммента по его id ////////////////////////////////////////
	
	function get_comment_by_id($id,$private=false){
		if($private)
			$query = $this->db->get_where('private_comments',array('id' => $id));
		else
			$query = $this->db->get_where('article_comments',array('id' => $id));
		return $query->row();
	}
	
	/////////////////////////////////////////////// Удаление плюса ///////////////////////////////////////////////
	
	function delete_plus($article, $user_id = null){
		
		if($user_id == null)
			$user_id = $this->dx_auth->get_user_id();
		
			$res = $this->db->delete('comment_pluses', array('article_id' => $article->id, 'user_id' => $user_id));
			$this->change_rating($article->user_id, 1, 'author','-');
			$this->change_rating_article($article->id, 1, '-');
			return true;

			
	}
	/////////////////////////////////////////// Рассмотрение жалобы ////////////////////////////////////////////
	
	function consider_plea($plea_id, $answer){
		
		$data = array(
		'data_considered' => date("Y-m-d H:i:s"),
		'considered' => 1,
		'answer' => $answer
		);
		
		$this->db->where('id', $plea_id);
		$res = $this->db->update('user_pleas', $data);
		if(!$res)
			return false;
		$query = $this->db->get_where('user_pleas', array('id' => $plea_id));
        $to = $this->articler_users->get_email($query->row()->user_id);
        $subject = 'Рассмотрение жалобы';
        $email = $this->email;
		if(defined('USE_SENDMAIL'))
        	$email->protocol = 'sendmail';
		if(!defined('LOCAL')):
        	$email->from($this->site_mail);
       	 $email->to($to);
        	$email->subject($subject);
        	$email->message($answer);
        	$res_email = $email->send();
        	return $res_email;
		else:
			return true;
			
		endif;
		
	}
	
	function get_avatar_src($user_id){
		
		$query = $this->db->get_where('user_avatars', array('user_id' => $user_id));
		if(count($query->result_array()) == 0)
			return false;
		return $query->row()->avatar_src;
	}
	
	////////////////////////////////////////////////// Список последних комментов //////////////////////////////////
		
	function last_comments($select = 'all'){
		
		if($select == 'all')
			$where = '';
		else
			$where = 'AND article_comments.'.$select;
			
		$sql = "SELECT article_comments.id 'id',article_comments.comment 'comment',article_comments.data 'data',articles.url 'article_url', users.username 'username',authors.name,authors.family FROM article_comments,users,articles,authors WHERE users.id = article_comments.user_id AND articles.id = article_comments.article_id AND users.id = authors.user_id $where ORDER BY data DESC LIMIT $this->num_last_comments";
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}
	
	////////////////////////////////////////////////// Список последних комментов к публикациям автора//////////////////////
		
	function last_comments_for_author($user_id){
				
		$sql = "SELECT article_comments.id 'id',article_comments.comment 'comment', article_comments.data, articles.url 'article_url' FROM article_comments,articles WHERE articles.id = article_comments.article_id AND article_comments.article_id IN (SELECT id FROM articles WHERE user_id = $user_id) $where ORDER BY data DESC LIMIT $this->num_last_comments";
        //echo 'sql last_comments_for_author '.$sql.'<br>';
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}
	
	///////////////////////////////////////////////// Проверяем, вышла ли публикация из песочницы ///////////////////
	function is_quit_sandbox($article, $mktime){
		
		$time_hold = $this->sandbox_hold * 86400;
		if($this->use_divided_rating_show):
			$type_heading = $this->settings_model->get_type_heading($article->heading_id);
			$sql = "SELECT articles.id FROM articles WHERE articles.rating >=".$this->rating_for_show[$type_heading]." AND articles.id = ".$article->id." AND UNIX_TIMESTAMP(articles.data_published) < $mktime";
		else:
			$value = array_shift($this->rating_for_show);
			$sql = "SELECT articles.id FROM articles WHERE articles.activity = 2 AND (articles.rating < $value OR (UNIX_TIMESTAMP(articles.data_published) + $time_hold) > $mktime) AND articles.id = ".$article->id;
		endif;
		$query = $this->db->query($sql);
		if(count($query->result_array()) > 0)
			return false;
		return true;
	}
	
	////////////////////////////////////////////// Бронирование темы ////////////////////////////////////////////////
	function booking($user_id,&$article_id){
		
		$this->db->where('id',$this->input->post('giventopics'));
		$res = $this->db->update('given_topics',array('booking' => 1, 'user_id' => $user_id, 'end_booking' => date('Y-m-d H:i:s',strtotime("+$this->period_booking seconds"))));
		if($res){
			$giventopic = $this->get_giventopic_by_id($this->input->post('giventopics'));
			$url = '/'.$giventopic['heading_name'].'/'.$giventopic['url'].'.html';
			
			$data = array(
			'id' => null,
			'heading_id' => $giventopic['heading_id'],
			'user_id' => $user_id,
			'header' => $giventopic['header'],
			'url' => $url,
			'description' => '',
			'keywords' => '',
			'annotation' => '',
			'content' => '',
			'activity' => 0,
			'data_saved' => date("Y-m-d H:i:s"),
			'data_last_modified' => '0000-00-00 00:00:00',
			'data_moderated' => '0000-00-00 00:00:00',
			'data_published' => '0000-00-00 00:00:00',
			'rating' => 0,
			'is_special' => 0,
			'giventopic_id' => $this->input->post('giventopics')
			);
			$res_insert = $this->db->insert('articles',$data);
			$article_id = $this->db->insert_id();
		}
		return $res_insert;
		
	}
	
	//////////////////////////////////////////////  Получаем список заданных тем ////////////////////////////////////
	function get_giventopics($all = false){
		$this->db->select("given_topics.*,headings.name_russian,authors.name 'name',authors.family 'family',users.username 'username'");
		$this->db->from('given_topics');
		if(!$all){
			$this->db->where('booking','0');
			$this->db->where('used','0');
		}
		$this->db->join('headings','given_topics.heading_id = headings.id');
		$this->db->join('users','given_topics.user_id = users.id','left');
		$this->db->join('authors','given_topics.user_id = authors.user_id','left');
		$this->db->order_by('header');	
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/////////////////////////////////////////////// Получаем заданную тему по id //////////////////////////////////////
	function get_giventopic_by_id($id){
		
		$this->db->select("given_topics.*,headings.name_russian,headings.name 'heading_name',authors.name 'name',authors.family 'family',users.username 'username'");
		$this->db->from('given_topics');
		$this->db->where('given_topics.id',$id);
		$this->db->join('headings','given_topics.heading_id = headings.id');
		$this->db->join('users','given_topics.user_id = users.id','left');
		$this->db->join('authors','given_topics.user_id = authors.user_id','left');
		$this->db->order_by('header');	
		$query = $this->db->get();
		
		return $query->row_array();
	}
	
	////////////////////////////////////////////// Добавление авторского рейтинга  привязкой к id статьи ////////////
	function add_author_rating($article, $date){
		
		$query = $this->db->get_where('add_author_ratings', array('article_id' => $article->id));
		if(count($query->result_array()) > 0)
			return false;
		if($article->is_special == 1)
			$add_rating = $this->rating_by_publish + $this->bonus_for_homefeed;
		else
			$add_rating = $this->rating_by_publish;
		$data = array(
		'id' => null,
		'article_id' => $article->id,
		'add' => $add_rating,
		'data_change' => $date
		);
		$res = $this->db->insert('add_author_ratings', $data);
        $this->change_rating_article($article->id, $add_rating);
		$this->change_rating($article->user_id, $add_rating);
		$res = $this->add_payment_author($article, $date);
		return $res;
	}
	
	/////////////////////////////////////////////// Получаем кол-во WMR, начисленных за прошедщие сутки //////////////////
	function get_payments_daily($user_id){
		$period = time() - 86400;
		$sql = "SELECT SUM(payment) AS sum FROM articler_payments WHERE user_id = $user_id AND UNIX_TIMESTAMP(data) > $period";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0){
			if(!$res->row()->sum)
				return 0;
//			return round($res->row()->sum * $this->pay_for_visit);
			return $res->row()->sum;
		}
		else{
			return 0;
		}
	}	
	
	/////////////////////////////////////////////// Получаем кол-во WMR, начисленных за прошедщую неделю //////////////////
	function get_payments_weekly($user_id){
		$period = time() - 86400 * 7;
		$sql = "SELECT SUM(payment) AS sum FROM articler_payments WHERE user_id = $user_id AND UNIX_TIMESTAMP(data) > $period";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0){
			if(!$res->row()->sum)
				return 0;
//			return round($res->row()->sum * $this->pay_for_visit);
			return $res->row()->sum;
		}
		else{
			return 0;
		}
	}	
	
	/////////////////////////////////////////////// Получаем сколько всего WMR, начислено юзеру //////////////////

	function get_payments_all($user_id){
		$sql = "SELECT SUM(payment) AS sum FROM articler_payments WHERE user_id = $user_id";
		$res = $this->db->query($sql);
		if($res->num_rows() > 0){
			if(!$res->row()->sum)
				return 0;
//			return round($res->row()->sum * $this->pay_for_visit);
			return $res->row()->sum;
		}
		else{
			return 0;
		}
	}
	
	////////////////////////////////////////////// Начисление средств при выходе статьи из песочницы ////////////////
	
	function add_payment_author($article, $date){
		
		$author_rating = $this->get_rating($article->user_id);
		$num_articles = $this->get_num_articles_by_user_id($article->user_id);
		$author_group = $this->get_author_group($author_rating, $num_articles);
		$property = 'pay_'.$this->author_statuses[$author_group];
		if(!$property)
			return false;
		$payment = $this->settings_model->get_setting('finance_system', $property, 'property');
		$this->db->select_sum('add_score');
		$this->db->where('user_id', $article->user_id);
		$this->db->where('article_id', $article->id);
		$query = $this->db->get('add_scores');
		if($query->row()->add_score){
			$payment += $query->row()->add_score;
		}
		$buffer_score = $this->db->get_where('articler_score', array('user_id' => $article->user_id));
		$score = $buffer_score->row()->score;
		$data = array(
		'id' => null,
		'user_id' => $article->user_id,
		'article_id' => $article->id,
		'payment' => $payment,
		'balance' => $score + $payment,
		'data' => $date
		);
		$res = $this->db->insert('articler_payments', $data);
		if($res){
			$this->db->where('user_id',$article->user_id);
			$res = $this->db->update('articler_score',array('score' => $score + $payment));
		}
		return $res;
	}
	
	////////////////////////////////////////////// Получаем все заработанные средства /////////////////////////////////
	
	function get_all_earn($user_id = null){
		
		if($user_id == null){
			$this->db->select_sum('payment');
			$query = $this->db->get('articler_payments');
			if(!$query->row()->payment)
				return 0;
			return $query->row()->payment;
		}
		else{
			$this->db->select_sum('payment');
			$this->db->where('user_id', $user_id);
			$query = $this->db->get('articler_payments');
			if(!$query->row()->payment)
				return 0;
			return $query->row()->payment;
		}
	}
	
	////////////////////////////////////////////// Получаем список выплат /////////////////////////////////
	
	function get_payouts($status_id = -1, $user_id = null, $limit = ''){
		
		if($user_id)
			$user = "articler_payouts.user_id =".$user_id;
		else
			$user = '';
			
		if($status_id != -1)
			$status = 'AND status = '.$status_id;
		else
			$status = '';
			
		if($status_id == 1 && $status_id == -1)	
			$order = 'ORDER BY articler_payouts.data_payouts DESC';
		else
			$order = 'ORDER BY articler_payouts.data_order DESC';
			
		if($limit != '')
			$limit = 'LIMIT '.$limit;
		
			$sql = "SELECT articler_payouts.*, articler_score.purse, users.username FROM articler_payouts,articler_score,users WHERE articler_payouts.user_id = articler_score.user_id AND users.id = articler_payouts.user_id $user $status $order $limit";
		
		$query = $this->db->query($sql);
		return $query->result_array();

	}
	
	////////////////////////////////////////////// Получаем сумму выплат юзера по его id /////////////////
	
	function get_sum_payout_by_user_id($user_id){
		
		$this->db->select_sum('payout','num');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get('articler_payouts');
		if($query->row()->num)
			return $query->row()->num;
		return 0;
	}
	
	////////////////////////////////////////////// Получаем сумму плтежей юзера по его id /////////////////
	
	function get_sum_payment_by_user_id($user_id){
		
		$this->db->select_sum('payment','num');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get('articler_payments');
		if($query->row()->num)
			return $query->row()->num;
		return 0;
	}
	
	////////////////////////////////////////////// Заказ выплаты /////////////////////////////////
	
	function add_payout($user_id, $payout){
		
		$query = $this->db->get_where('articler_payouts', array('user_id' => $user_id, 'status' => 0));
		if($query->row())
			return false;
		$query = $this->db->get_where('articler_score', array('user_id' => $user_id));
		$data = array(
		'id' => null,
		'user_id' => $user_id,
		'payout' => $payout,
		'balance' => $query->row()->score,
		'data_order' => date("Y-m-d H:i:s"),
		'data_payout' => null,
		'status' => 0
		);
		$res = $this->db->insert('articler_payouts', $data);
		return $res;
	}
	
	////////////////////////////////////////////// Процедура выплаты /////////////////////////////////
	
	function make_payout($payout_id){
		
		$query = $this->db->get_where('articler_payouts',array('id' => $payout_id));
		if(count($query->result_array()) < 1)
			return false;
		
		$this->db->where('id', $payout_id);
		$data = array(
		'data_payout' => date("Y-m-d H:i:s"),
		'status' => 1
		);
		$res = $this->db->update('articler_payouts', $data);
		if($res){
			$this->db->set('score',"score - ".$query->row()->payout,false);
			$this->db->where('user_id',$query->row()->user_id);
			$res = $this->db->update('articler_score');
		}
		return $res;
	}
	
	////////////////////////////////////////////// Получаем все выданные средства /////////////////////////////////
	
	function get_all_paid($user_id = null){
		
		if($user_id == null){
			$this->db->select_sum('payout');
			$query = $this->db->get('articler_payouts');
			if(!$query->row()->payout)
				return 0;
			return $query->row()->payout;
		}
		else{
			$this->db->select_sum('payout');
			$this->db->where('user_id', $user_id);
			$this->db->where('status', 1);
			$query = $this->db->get('articler_payouts');
			if(!$query->row()->payout)
				return 0;
			return $query->row()->payout;
		}
	}
	
	///////////////////////////////////// Получаем информацию о финансах пользователя (баланс + кошелек)////////////////////
	
	function get_user_finance($user_id = null){
		
		$query = $this->db->get_where('articler_score', array('user_id' => $user_id));
		return $query->row();
	}
	
	////////////////////////////////////////////// Получаем остаток в системе (баланс) /////////////////////////////////
	
	function get_balance($user_id = null){
		
		if($user_id == null){
			$this->db->select_sum('score');
			$query = $this->db->get('articler_score');
			return round($query->row()->score * $this->pay_for_visit);
		}
		else{
			$query = $this->db->get_where('articler_score', array('user_id' => $user_id));
			return $query->row()->score;
		}
	}
	
	////////////////////////////////////////////// Получаем доступные к выдаче средства /////////////////////////////////
	
	function get_available_for_pay($user_id = null){
		
		if($user_id){
			$query = $this->db->get_where('articler_payouts', array('user_id' => $user_id, 'status' => 0));
			if($query->row())
				return 0;
		}
		$date_end = dates_in_prev_month(date('n'), date('Y'), 'end').' 23:59:59';
		if($user_id != null)
			$user = 'AND user_id = '.$user_id;
		else
			$user = '';
		$sql = "SELECT SUM(payment) AS 'payment' FROM `articler_payments` WHERE `data` <= '$date_end' $user";
		$query = $this->db->query($sql);
		$all_earn = $query->row()->payment;
		$sql = "SELECT SUM(payout) AS 'payout' FROM `articler_payouts` WHERE `data_payout` <= '$date_end' AND status = 1 $user";
		$query = $this->db->query($sql);
		$all_paid = $query->row()->payout;
		$sum = $all_earn - $all_paid;
		if(!$sum || $sum < $this->lowest_sum_for_pay)
			return 0;
		else
			return $sum;
			
			
	}
	
	/////////////////////////////////////////////// Список статей для топа ///////////////////////////////////////////
	function top_authors(){
		
		$curr_data = date('Y-m-d');
		$this->db->select('username,user_id,num_views');
		$this->db->from('top_authors');
		$this->db->join('users','users.id = top_authors.user_id');
		$this->db->where('data',$curr_data);
		$this->db->order_by('num_views','desc');
		$this->db->limit(5);
		$res = $this->db->get();
		
		if(!$res || $res->num_rows() < 1)
			return array();
		$result = $res->result_array();
		foreach($result as $key=>$value){
			$result[$key]['num_views'] = round($value['num_views'] * $this->corrective_q);
		}
		return $result;
	}

	
	////////////////////////////////////////////// Последние статьи в песочнице //////////////////////////////////////
	function last_articles_in_sandbox(){
		
		$sql = '';
		$time_hold = $this->sandbox_hold * 86400;
		$mktime = mktime();
		if($this->use_divided_rating_show):
		foreach($this->rating_for_show as $key=>$value)	:
//			$sql .= "SELECT articles.id,articles.header,articles.data_published,articles.url FROM articles,headings,type_headings WHERE articles.activity = 2 AND articles.heading_id = headings.id AND headings.type_heading = type_headings.id AND type_headings.id = $key AND (articles.rating < $value OR (UNIX_TIMESTAMP(data_published) + $time_hold) > $mktime)";
			$sql .= "SELECT articles.id,articles.header,articles.data_published,articles.url FROM articles,headings,type_headings WHERE articles.activity = 2 AND articles.heading_id = headings.id AND headings.type_heading = type_headings.id AND type_headings.id = $key AND articles.in_sandbox = 1";
			
			
			if($key != $this->settings_model->last_type_heading)
				$sql .= ' UNION ';
		endforeach;
		else:
			$value = array_shift($this->rating_for_show);
			$sql .= "SELECT id,header,data_published,url FROM articles WHERE activity = 2 AND in_sandbox = 1";
		endif;
		$sql .= " ORDER BY data_published DESC LIMIT $this->num_last_articles_sandbox";

		$query = $this->db->query($sql);
		
		return $query->result_array();
	}
	
	////////////////////////////////////////////// Последние статьи юзера //////////////////////////////////////
	function last_articles_user_id($user_id){
			
		$sql = "SELECT articles.id,articles.header,articles.annotation,articles.rating,articles.data_published,articles.url,headings.name 'heading_name', headings.name_russian 'heading_name_russian' FROM articles,headings WHERE articles.user_id = $user_id AND articles.heading_id = headings.id ORDER BY data_published DESC LIMIT $this->num_last_articles_sandbox";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	////////////////////////////////////////////// Получить статьи юзера //////////////////////////////////////
	function get_articles_author($user_id,$limit){
			
		$sql = "SELECT a.id,a.header,a.annotation,a.rating,a.data_published,a.url,h.name AS 'heading_name', h.name_russian AS 'heading_name_russian',COUNT(ac.id) AS comments FROM articles a JOIN headings h ON a.heading_id = h.id LEFT JOIN article_comments ac ON ac.article_id = a.id WHERE a.user_id = $user_id AND a.activity = 2 GROUP BY a.id ORDER BY data_published DESC LIMIT $limit";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	////////////////////////////////////////////// Проверяем возможность плюсования/минусования /////////////////////
	
	function is_possible_assess($user_id = null, $type = 'plus'){
		
		if($user_id == null)
			$user_id = $this->dx_auth->get_user_id();
        $limit_on_add_plus = $this->settings_model->get_setting('comments_system', 'limit_on_add_plus', 'property');
		$query = $this->db->get_where('articles', array('user_id' => $user_id, 'activity' => 2));
		if($type == 'plus'){
			if(count($query->result_array()) >= $this->quantity_articles_for_assess && $this->quantity_pluses_in_current_day_by_user_id($user_id) <= $limit_on_add_plus)
				return true;
		}
		else{
			if(count($query->result_array()) >= $this->quantity_articles_for_assess && $this->quantity_minuses_in_current_day_by_user_id($user_id) <= $limit_on_add_plus)
				return true;
		}
		
		return false;
	}
	
	function quantity_pluses_in_current_day_by_user_id($user_id){
		
		$begin_date = date('Y').'-'.date('m').'-'.date('d').' 00:00:00';
		$end_date = date('Y').'-'.date('m').'-'.date('d').' 23:59:59';
		$sql = "SELECT COUNT(id) AS 'num' FROM comment_pluses WHERE user_id = $user_id AND data BETWEEN '$begin_date' AND '$end_date'";
		$query = $this->db->query($sql);
		if($query->row())
			return $query->row()->num;
		else
			return 0;
	}
	
	function quantity_minuses_in_current_day_by_user_id($user_id){
		
		$begin_date = date('Y').'-'.date('m').'-'.date('d').' 00:00:00';
		$end_date = date('Y').'-'.date('m').'-'.date('d').' 23:59:59';
		$sql = "SELECT COUNT(id) AS 'num' FROM comment_minuses WHERE user_id = $user_id AND data BETWEEN '$begin_date' AND '$end_date'";
		$query = $this->db->query($sql);
		if($query->row())
			return $query->row()->num;
		else
			return 0;
	}
	
	////////////////////////////////////////////// Подача жалобы модератору ////////////////////////////////////////
	
	function add_plea(){
		
		$data = array(
		'id' => null,
		'user_id' => $this->dx_auth->get_user_id(),
		'article_id' => $this->input->post('article_id'),
		'comment_id' => $this->input->post('comment_id'),
		'plea' => $this->input->post('plea'),
		'data' => date("Y-m-d H:i:s"),
		'considered' => 0
		);
		
		$res = $this->db->insert('user_pleas',$data);
		$login = $this->dx_auth->get_username();
		
		$sql = "SELECT * FROM users WHERE role_id = (SELECT id FROM roles WHERE name = 'moderator') LIMIT 1";
		$moderator = $this->db->query($sql);
		$to = $this->articler_users->get_email($moderator->row()->id);
		$subject = 'Сообщение о нарушении';
		$email = $this->email;
		if(defined('USE_SENDMAIL'))
        	$email->protocol = 'sendmail';
		$email->mailtype = 'html';
      	$message = $this->settings_model->get_mail_template('add_plea','property','text');
		$username = '<a href="'.$this->site_url_without_slash.'/avtory/'.$login.'">'.ucfirst($login).'</a>';
		$message = str_replace('%пользователь%',$username,$message);
		if(!defined('LOCAL')):
			$email->from($this->site_mail);
			$email->to($to);
			$email->subject($subject);
			$email->message($message);
			$res_email = $email->send();
		endif;
		
		return $res;
	}
	
	////////////////////////////////////////////// Плюсование и возможный выход из песочницы ////////////////////////////////
	
	function add_plus($article, $date, $user_id = null, &$response){
		
		if($user_id == null)
			$user_id = $this->dx_auth->get_user_id();
		
		$query = $this->db->get_where('comment_pluses', array('article_id' => $article->id, 'user_id' => $user_id));
		if($query->row()){
			$response['error'] = 'Вы уже скорректировали рейтинг по данной статье';
			return false;
		}
			
			
		$data = array(
		'id' => null,
		'article_id' => $article->id,
		'user_id' => $user_id,
		'data' => $date
		);
		
		$res = $this->db->insert('comment_pluses',$data);
		if($res)
			$this->db->delete('comment_minuses', array('article_id' => $article->id, 'user_id' => $user_id));
		$this->change_rating($article->user_id, $this->add_plus, 'author');
		$this->change_rating_article($article->id, $this->add_plus);
		if($this->is_quit_sandbox($article, mktime())){
			$this->add_author_rating($article, $date);
		}
		return true;
			
	}
	
	////////////////////////////////////////////// Снижение рейтинга ////////////////////////////////
	
	function add_minus($article, $date, $user_id = null, &$response){
		
		if($user_id == null)
			$user_id = $this->dx_auth->get_user_id();
		
		$query = $this->db->get_where('comment_minuses', array('article_id' => $article->id, 'user_id' => $user_id));
		if($query->row()){
			$response['error'] = 'Вы уже скорректировали рейтинг по данной статье';
			return false;
		}
			
		$data = array(
		'id' => null,
		'article_id' => $article->id,
		'user_id' => $user_id,
		'data' => $date
		);
		
		$res = $this->db->insert('comment_minuses',$data);
		if($res)
			$this->db->delete('comment_pluses', array('article_id' => $article->id, 'user_id' => $user_id));
		$this->change_rating($article->user_id, $this->add_plus, 'author','-');
		$this->change_rating_article($article->id, $this->add_plus, '-');
		if($this->is_quit_sandbox($article, mktime())){
			$this->add_author_rating($article, $date);
		}
		return true;
			
	}
	
	/////////////////////////////////////////// Проверка возможности комментирования //////////////////////////////
	function allow_comments($article,$user_id){
	
		$query = $this->db->get_where('article_comments', array('article_id' => $article->id, 'user_id' => $user_id));
		if(count($query->result_array()) > 0)
			return false;
		return true;
	}
	
	/////////////////////////////////////////// Проверка возможности плюсования конкретной статьи /////////////////
	function allow_plus($article, $user_id){
		
		$query = $this->db->get_where('comment_pluses', array('article_id' => $article->id, 'user_id' => $user_id));
		if(count($query->result_array()) > 0)
			return false;
		return true;
	}
	
	/////////////////////////////////////////// Валидация статьи /////////////////////////////////////////////////
	function validation_article($article_id){
	
		$query = $this->db->get_where('articles',array('id' => $article_id));
		
		if(!$query->row()->content)
			return 'Статья не содержит текста!';
		
		if(mb_strlen($query->row()->annotation) < 150){
			return 'В поле "Аннотация" должно быть не менее 150 символов!';
		}
		
		if(mb_strlen($query->row()->annotation) > 300){
			return 'В поле "Аннотация" должно быть не более 300 символов!';
		}
		
        if(mb_strlen($query->row()->description) < 100){
			return 'В поле "Описание" должно быть не менее 100 символов!';
		}
		
		if(mb_strlen($query->row()->description) > 200){
			return 'В поле "Описание" должно быть не более 200 символов!';
		}
        
		if(mb_strlen($query->row()->keywords) < 40){
			return 'В поле "Ключевые слова" должно быть не менее 40 символов!';
		}
		
		if(mb_strlen($query->row()->keywords) > 100){
			return 'В поле "Ключевые слова" должно быть не более 100 символов!';
		}
		return 1;
	}
	
	/////////////////////////////////////////// Отправка на модерацию ////////////////////////////////////////////
	function send_moderate($id){
	
		$query = $this->db->get_where('articles',array('id' => $id));
		if(count($query->result_array()) < 1 || $query->row()->activity == 2){
			$arr['errors']['moderate'] = 'Материал не удалось отправить на модерацию!';
			return $arr;
		}
		if($query->row()->giventopic_id){
			$validating = $this->validation_article($id);
			if($validating != 1){
				$arr['errors']['moderate'] = 'Материал не удалось отправить на модерацию! '.$validating;
				return $arr;

			}
		}
		
		$this->db->where('id',$id);
		$res = $this->db->update('articles',array('activity' => 1, 'data_moderated' => date("Y-m-d H:i:s")));
		$to = $this->articler_users->get_moderator_email();
		if($to){
			$subject = 'Новая статья для модерации';
        	$email = $this->email;
			if(defined('USE_SENDMAIL'))
        		$email->protocol = 'sendmail';
//			if(!defined('LOCAL')):
        		$email->from($this->site_mail);
       	 		$email->to($to);
        		$email->subject($subject);
				$message = 'На модерацию поступила статья от пользователя "'.$query->row()->username.'"';
        		$email->message($message);
        		$res_email = $email->send();
        		return $res_email;
//			else:
//				return true;
			
//			endif;
		}
		return $res;
	}
	////////////////////////////////////////// Отмена отправки на модерацию /////////////////////////////////////
	function cancel_moderate($id){
		
		$query = $this->db->get_where('articles',array('id' => $id, 'activity' => 2));
		if(count($query->result_array())>0):
			$arr['errors']['moderate'] = 'Материал уже опубликован!';
			return $arr;
		endif;
		$query = $this->db->get_where('article_blockings',array('article_id' => $id));
		if($query->num_rows()>0){
			if(time_expire_period($query->row()->data_blocked,mktime(),$this->blocked_time)):
				$this->db->delete('article_blockings',array('article_id' => $id));
			else:
				$arr['errors']['moderate'] = 'Материал сейчас рассматривается модератором!';
				return $arr;		
			endif;
		}
	
		$this->db->where('id',$id);
		$res = $this->db->update('articles',array('activity' => 0, 'data_moderated' => null));
		return $res;
	}
	
	function set_visit($article_id, $user_id, $article_user_id){
		
		if(!$article_id)
			return false;
		if($article_user_id != $user_id):
		
		$dats = array(
		'id' => null,
		'article_id' => $article_id,
		'user_id' => $user_id,
		'data_visited' => date("Y-m-d H:i:s")
		);
		
		return $this->db->insert('user_visites',$dats);
		
		endif;
	}
	
	function get_visit($article_id, $user_id){
		
		$query = $this->db->get_where('user_visites', array('article_id' => $article_id, 'user_id' => $user_id));
		return $query->row();
	}
	
	function get_activity_by_article_id($id){
		
		$this->db->select('activity');
		$this->db->from('articles');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row()->activity;
	}
	
	///////////////////////////////////////// Проверка, является ли текущий юзер автором статьи //////////////////////
	function is_author_article($id){
		
		$this->db->select('user_id');
		$this->db->from('articles');
		$this->db->where('id',$id);
		$query = $this->db->get();
		if($query->row()->user_id == $this->dx_auth->get_user_id())
			return true;
		return false;
	}
	
	public function check_private_url($url,$id = null)	{
		
		if(!$id)
			$query = $this->db->get_where('private_articles',array('url'=>$url));
		else
			$query = $this->db->query("SELECT id FROM private_articles WHERE url = '$url' AND id != $id");
		if($query->num_rows() > 0)
			return false;	
		return true;
	}
	
	///////////////////////////////////////////////// Получаем приватную статью по ее урлу ////////////////////////////
	function get_private_article($url){
		
		$query = $this->db->get_where('private_articles',array('url' => $url));
		return $query->row();
	}
	
	///////////////////////////////////////////////// Добавление приватной статьи /////////////////////////////////////
	function add_private_article(){
		
		$data = array(
		'id' => null,
		'header' => $this->input->post('header'),
		'url' => strip_tags($this->input->post('url')),
		'annotation' => $this->input->post('annotation'),
        'content' => $_POST['editor'],
		'data_added' => date("Y-m-d H:i:s"),
		'data_last_modified' => date("0000-00-00 00:00:00")
		);
		$res = $this->db->insert('private_articles',$data);
		return $res;
	}
	
	/////////////////////////////////////////////// Получение рубрик //////////////////////////////////////////////////
	
	function get_headings(){
		
		$this->db->order_by('name_russian','asc');
		$query = $this->db->get('headings');
		return $query->result_array();
	}
	
	/////////////////////////////////////////////// Редактирование привязку рекламных блоков к рубрике ///////////////////
	
	function update_advert_heading($id){
		$this->db->delete('advert_headings', array('heading_id' => $id));
		foreach($_POST as $key=>$value){
			list($type,$advert_id) = explode('-',$key);
			if($type != 'advert')
				continue;
			$data = array(
			'id' => null,
			'advert_id' => $advert_id,
			'heading_id' => $id
			);
			$res = $this->db->insert('advert_headings',$data);
		}
		return $res;
		
	}
	
//	function 
	
	/////////////////////////////////////////////// Редактирование рубрики ///////////////////////////////////////////////
	
	function update_rubric($id){
		
		$data = array(
		'name' => $this->input->post('name'),
		'name_russian' => $this->input->post('name_russian'),
		'type_heading' => $this->input->post('type_heading')
		);
		$this->db->where('id',$id);
		$res = $this->db->update('headings',$data);
		if($res)
			$this->update_advert_heading($id);
		return $res;
	}
	
	///////////////////////////////////////////// Редактирование заданной темы //////////////////////////////////////////
	function update_giventopic($id){
		
		$data = array(
		'header' => $this->input->post('header'),
		'url' => $this->input->post('url'),
		'heading_id' => $this->input->post('headings')
		);
		$this->db->where('id',$id);
		$res = $this->db->update('given_topics',$data);
		return $res;
	}	
	///////////////////////////////////////////// Добавление заданной темы //////////////////////////////////////////////
	function add_giventopic(){
		
		$data = array(
		'id' => null,
		'header' => $this->input->post('header'),
		'url' => $this->input->post('url'),
		'heading_id' => $this->input->post('headings'),
		'booking' => 0,
		'user_id' => null,
		'end_booking' => '0000-00-00 00:00:00'
		);
		$res = $this->db->insert('given_topics',$data);
		return $res;
	}
	
	/////////////////////////////////////////// Проверка уникальности при добавлении заданной темы ///////////////////////
	function is_giventopic_exists($url,$heading_id,$id=null){
		
	
		if(!$id){
			$query = $this->db->get_where('given_topics',array('url' => $url, 'heading_id' => $heading_id));
		}
		else{
			$sql = "SELECT * FROM given_topics WHERE url = '$url' AND id != $id AND heading_id = $heading_id";
			$query = $this->db->query($sql);

		}
		if(count($query->result_array()) > 0)
			return true;
		return false;
	}
	
	/////////////////////////////////////////////// Добавление рубрики ////////////////////////////////////////////////
	
	function add_rubric(){
		
		$data = array(
		'id' => null,
		'name' => $this->input->post('name'),
		'name_russian' => $this->input->post('name_russian'),
		'type_heading' => $this->input->post('type_heading')
		);
		
		$res = $this->db->insert('headings',$data);
		return $res;
	}
	
	/////////////////////////////////////////////// Добавление рекламного блока ////////////////////////////////////////////////
	
	function add_advert(){
		
		$data = array(
		'id' => null,
		'name' => trim($this->input->post('name')),
		'code' => $this->input->post('code'),
		'type' => $this->input->post('type')
		);
		
		$res = $this->db->insert('advert_blocks',$data);
		return $res;
	}
	
	/////////////////////////////////////////////// Редактирование рекламного блока ////////////////////////////////////////////////
	
	function update_advert($id){
		
		$data = array(
		'name' => trim($this->input->post('name')),
		'code' => $this->input->post('code'),
		'type' => $this->input->post('type')
		);
		$this->db->where('id',$id);
		$res = $this->db->update('advert_blocks',$data);
		return $res;
	}
	
	/////////////////////////////////////////////// Редактирование рекламного блока по умолчанию ////////////////////////////////////////////////
	
	function update_default_advert($id){
		
		$data = array(
		'code' => $this->input->post('code')
		);
		$this->db->where('id',$id);
		$res = $this->db->update('advert_default_blocks',$data);
		return $res;
	}
	
	/////////////////////////////////////////////// Удаление рекламного блока ////////////////////////////////////////////////
	
	function delete_advert($id){
		
		$res = $this->db->delete('advert_blocks',array('id' => $id));
		return $res;
	}
	
	/////////////////////////////////////////////// Получение списка рекламных блоков ///////////////////////////////////////////////////
	function get_advert_blocks(){
		
		$this->db->from('advert_blocks');
		$this->db->select('*');
		$this->db->order_by('name');
		$query = $this->db->get();
		$result = $query->result_array();
		foreach($result as $key=>$value){
			if($value['type'] == 'over_content')
				$result[$key]['type_russian'] = 'Над контентом';
			elseif($value['type'] == 'under_content')
				$result[$key]['type_russian'] = 'Под контентом';
			elseif($value['type'] == 'under_image')
				$result[$key]['type_russian'] = 'Под изображением';
		}
		return $result;
	}
	

	/////////////////////////////////////////////// Получение списка рекламных блоков по умолчанию///////////////////////////////////////////////////
	function get_advert_default_blocks(){
		
		$this->db->from('advert_default_blocks');
		$this->db->select('*');
		$this->db->order_by('name');
		$query = $this->db->get();
		$result = $query->result_array();
		foreach($result as $key=>$value){
			if($value['type'] == 'over_content')
				$result[$key]['type_russian'] = 'Над контентом';
			elseif($value['type'] == 'under_content')
				$result[$key]['type_russian'] = 'Под контентом';
			elseif($value['type'] == 'under_image')
				$result[$key]['type_russian'] = 'Под изображением';
		}
		return $result;
	}
	

	
	function get_article_advert_blocks($article_id,$heading_id){
		
		$arr = array();
		$sql = "SELECT code FROM advert_blocks WHERE id IN (SELECT advert_id FROM advert_articles WHERE article_id = $article_id) AND type = 'over_content' ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		if($query && count($query->row_array())){
			$buffer = $query->row_array();
			$arr['over_content'] = $buffer['code'];
		}
	
		
			$sql = "SELECT code FROM advert_blocks WHERE id IN (SELECT advert_id FROM advert_articles WHERE article_id = $article_id) AND type = 'under_content' ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['under_content'] = $buffer['code'];
			}
		
			$sql = "SELECT code FROM advert_blocks WHERE id IN (SELECT advert_id FROM advert_articles WHERE article_id = $article_id) AND type = 'under_image' ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['under_image'] = $buffer['code'];
			}
			
		if(empty($arr['over_content']) || !$arr['over_content']){
			$sql = "SELECT code FROM advert_blocks WHERE id IN (SELECT advert_id FROM advert_headings WHERE heading_id = $heading_id) AND type = 'over_content' ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['over_content'] = $buffer['code'];

			}
		}
		if(empty($arr['under_content']) || !$arr['under_content']){

			$sql = "SELECT code FROM advert_blocks WHERE id IN (SELECT advert_id FROM advert_headings WHERE heading_id = $heading_id) AND type = 'under_content' ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['under_content'] = $buffer['code'];
			}
		}
		if(empty($arr['under_image']) || !$arr['under_image']){

			$sql = "SELECT code FROM advert_blocks WHERE id IN (SELECT advert_id FROM advert_headings WHERE heading_id = $heading_id) AND type = 'under_image' ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['under_image'] = $buffer['code'];
			}
		}
		
		
		if(empty($arr['over_content']) || !$arr['over_content']){
			$sql = "SELECT code FROM advert_default_blocks WHERE type = 'over_content'";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['over_content'] = $buffer['code'];
			}
		}
		
		if(empty($arr['under_content']) || !$arr['under_content']){
			$sql = "SELECT code FROM advert_default_blocks WHERE type = 'under_content'";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['under_content'] = $buffer['code'];
			}
		}
		
		if(empty($arr['under_image']) || !$arr['under_image']){
			$sql = "SELECT code FROM advert_default_blocks WHERE type = 'under_image'";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['under_image'] = $buffer['code'];
			}
		}
		
		return $arr;
	}
	
	function get_comment_advert_blocks($article_id,$heading_id){
		$arr = array();
		$sql = "SELECT code FROM advert_blocks WHERE id IN (SELECT advert_id FROM advert_headings WHERE heading_id = $heading_id) AND type = 'under_image' ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		if($query && count($query->row_array())){
			$buffer = $query->row_array();
			$arr['under_image'] = $buffer['code'];
		}
		
		if(empty($arr['under_image'])){
			$sql = "SELECT code FROM advert_blocks WHERE id IN (SELECT advert_id FROM advert_articles WHERE article_id = $article_id) AND type = 'under_image' ORDER BY id DESC LIMIT 1";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['under_image'] = $buffer['code'];
			}
		}
		
		if(empty($arr['under_image'])){
			$sql = "SELECT code FROM advert_default_blocks WHERE type = 'under_image'";
			$query = $this->db->query($sql);
			if($query && count($query->row_array())){
				$buffer = $query->row_array();
				$arr['under_image'] = $buffer['code'];
			}
		}
		
		return $arr;
		
	}
	
	
	///////////////////////////////////////////// Проверяем уникальность рубрики ///////////////////////////////////////
	
	function is_rubric_exists($rubric, $id = null){
		
		if(!$id){
			$query = $this->db->get_where('headings',array('name' => $rubric));
		}
		else{
			$sql = "SELECT * FROM headings WHERE name = '$rubric' AND id != $id";
			$query = $this->db->query($sql);

		}
		if(count($query->result_array()) > 0)
			return true;
		return false;
	}
	
	///////////////////////////////////////////// Проверяем уникальность названия рекламного блока ///////////////////////////////////////
	
	function is_advert_exists($advert_name,$advert_id=null){
		
		if($advert_id){
			$sql = "SELECT id FROM advert_blocks WHERE id != $advert_id AND name = '$advert_name'";
			$query = $this->db->query($sql);
		}
		else{
			$query = $this->db->get_where('advert_blocks',array('name' => $advert_name));

		}
		
		if(count($query->result_array()) > 0)
			return true;
		return false;
	}
	
	
	
	///////////////////////////////////////////////// Редактирование приватной статьи ////////////////////////////////////
	function update_private_article($id){
		
		$data = array(
		'header' => $this->input->post('header'),
		'url' => strip_tags($this->input->post('url')),
		'annotation' => $this->input->post('annotation'),
        'content' => $_POST['editor'],
		'data_last_modified' => date("Y-m-d H:i:s")
		);
		$this->db->where('id',$id);
		$res = $this->db->update('private_articles',$data);
		return $res;
	}
	
	/////////////////////////////////////////////// Получение списка приватных статей ///////////////////////////////////////////////////
	function get_private_articles($limit = ''){
		
		$this->db->from('private_articles');
		$this->db->select('id,header,url,annotation,data_added,data_last_modified');
		$this->db->order_by('data_added');
		if($limit)
			$this->db->limit($limit);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	///////////////////////////////////////////////// Публикование статьи /////////////////////////////////////////////	
	function publish_article($id){
		
		$this->db->delete('rejection_reasons',array('article_id' => $id));
		
		$sql = "SELECT articles.header 'header', articles.activity 'activity', articles.url 'url', articles.user_id 'user_id', articles.giventopic_id, headings.name_russian 'heading_name', headings.name 'heading_url',users.username 'username' FROM articles,headings,users WHERE headings.id = articles.heading_id AND users.id = articles.user_id AND articles.id =".$id;
		$query = $this->db->query($sql);
		
		$curr_activity = $query->row()->activity;
		$buffer = explode('/',$query->row()->url);
		$curr_url = $buffer[1];
		$curr_article = $buffer[2];
		

		$buffer_headings = $this->get_heading_by_id($_REQUEST['headings']);
		$new_url = '/'.$buffer_headings->name.'/'.$this->input->post('url').'_'.$id.'.html';
		if(isset($_POST['initial_rating']) && $_POST['initial_rating'] > 0){
            $max_rating = $this->input->post('rating_for_homefeed');
			$rating = $this->input->post('initial_rating');
            if($rating >= $max_rating)
                $rating = $max_rating - 1;
		}
		else{
			$rating = 0;
		}
		
		if(mb_strlen($this->input->post('annotation')) > 300){
			$annotation = mb_substr($this->input->post('annotation'),0,300);
		}
		else{
			$annotation = $this->input->post('annotation');
		}
        
        if(mb_strlen($this->input->post('description')) > 200){
            $description = mb_substr($this->input->post('description'),0,200);
        }
        else{
            $description = $this->input->post('description');
        }
        
         if(mb_strlen($this->input->post('keywords')) > 100){
            $keywords = mb_substr($this->input->post('keywords'),0,100);
        }
        else{
            $keywords = $this->input->post('keywords');
        }
		
		
		
		if($curr_activity == 2){
			$data = array(
			'data_last_modified' => date("Y-m-d H:i:s"),
			'content' => $this->input->post('editor'),
			'annotation' => $annotation,
            'description' => $description,
            'keywords' => $keywords,
			'heading_id' => $_REQUEST['headings'],
			'header' => $this->input->post('header'),
			'url' => $new_url
			);
		}
		else{
			$data = array(
			'activity' => 2,
			'heading_id' => $_REQUEST['headings'],
			'data_published' => date("Y-m-d H:i:s"),
			'content' => $this->input->post('editor'),
			'annotation' => $annotation,
            'description' => $description,
            'keywords' => $keywords,
			'header' => $this->input->post('header'),
			'url' => $new_url,
			'rating' => $rating,
			'in_sandbox' => 1
			);
			if(isset($_POST['is_special']))
				$data['is_special'] = 1;
			if($query->row()->giventopic_id){
				$this->db->where('id',$query->row()->giventopic_id);
				$this->db->update('given_topics',array('used' => 1));
		}
				
		}
		
		$response_upload = array();
		$res_upload = $this->load_image_article($user_id, $response_upload);
		if($res_upload){
			if($response_upload['error']){
				$res['errors']['upload_image'] = $response_upload['error'];
				return $res;
			}
			else{
				$data['image'] = $response_upload['file'];
			}
		}
		
		$this->db->where('id',$id);
		$res = $this->db->update('articles',$data);
		
		if($rating > 0)
			$this->change_rating($query->row()->user_id, $rating, 'author');
		
		if($res){
			
			if(isset($_POST['add_score']) && $_POST['add_score'] > 0){
				$data = array(
				'id' => null,
				'user_id' => $query->row()->user_id,
				'article_id' => $id,
				'add_score' => $this->input->post('add_score'),
                'type' => 'link',
				'data' => date("Y-m-d H:i:s")
				);
				$res = $this->db->insert('add_scores',$data);
				
			}
		}
		if(!defined('LOCAL'))
			$res = $this->message_publish($query);
		
		return $res;
	}
	

	
	////////// Отправляет сообщение об изменении статуса автора и делает запись в messages_change_statuses //////////////////

    function message_change_status($user_id, $status){

        $query = $this->db->get_where('users', array('id' => $user_id));
        $to = $this->articler_users->get_email($user_id);
        $subject = 'Изменение статуса автора';
        $message = $this->settings_model->get_mail_template('change_status','property','text');
        $message = str_replace('%автор%',ucfirst($query->row()->username),$message);
        $message = str_replace('%статус%',$status,$message);
        $email = $this->email;
		if(defined('USE_SENDMAIL'))
        	$email->protocol = 'sendmail';
        $email->mailtype = 'html';

        $email->from($this->site_mail);
        $email->to($to);
        $email->subject($subject);
        $email->message($message);
        $res_email = $email->send();
        return $res_email;
    }
	
	///////////////////////////////////////////// Отправляет сообщение о публикации статьи /////////////////////////////
	
	function message_publish($result){
		
			$to = $this->articler_users->get_email($result->row()->user_id);
			$subject = 'Публикация статьи';
			$message = $this->settings_model->get_mail_template('publish_article','property','text');
			$header = '<a href="'.$this->site_url_without_slash.$result->row()->url.'">'.ucfirst($result->row()->header).'</a>';
			$heading_name = '<a href="'.site_url().$result->row()->heading_url.'">'.ucfirst($result->row()->heading_name).'</a>';
			$message = str_replace('%автор%',ucfirst($result->row()->username),$message);
			$message = str_replace('%статья%',$header,$message);
			$message = str_replace('%рубрика%',$heading_name,$message);
			$email = $this->email;
			if(defined('USE_SENDMAIL'))
            	$email->protocol = 'sendmail';
			$email->mailtype = 'html';
      		
		$email->from($this->site_mail);
		$email->to($to);
		$email->subject($subject);
		$email->message($message);
		$res_email = $email->send();
		return $res_email;
	}
	
	function add_date($article_id){
		
		$query = $this->db->get_where('articles_dates',array('article_id' => $article_id));
		if(count($query->result_array()) > 0)
			return false;
			$data_published = mktime() + $this->sandbox_hold * (60*60*24);
			$res = $this->db->insert('articles_dates', array('article_id' => $article_id, 'data_published' => $data_published));
			return $res;
	}
	
	////////////////////////////////////////// Изменение рейтинга статьи /////////////////////////////////
	
	function change_rating_article($article_id, $num, $operation = '+'){
		
		if($operation == '+'){
			$this->db->set("rating","rating + $num", false);
		}
		else{
			$this->db->set("rating","rating - $num", false);
			$this->db->where('rating !=',0);
		}
		$this->db->where('id', $article_id);
		$this->db->update('articles');

	}
	
	///////////////////////////////////////// Отклонение статьи/////////////////////////////////////////////
	
	function reject_article($id, $reason){
		
		$this->db->where('id',$id);
		$res = $this->db->update('articles',array('activity' => -1));
		$reason = str_replace($this->settings_model->symbol_escaped,'',$reason);
		
		$data = array(
		'article_id' => $id,
		'reason' => $reason,
		'data_rejected' => date("Y-m-d H:i:s"),
		);
		
		$this->db->delete('rejection_reasons',array('article_id' => $id));
		$res = $this->db->insert('rejection_reasons',$data);	
		if($res){
			$this->db->delete('article_blockings',array('article_id' => $id));

		}
		return $res;
	}
	////////////////////////////////////////// Удаление статьи //////////////////////////////////////////////
	function delete_article($id){
		
		$this->db->select('activity');
		$query = $this->db->get_where('articles',array('id' => $id));
		if($query->row()->activity == 2)
			$errors['errors']['activity'] = 'Невозможно удалить опубликованную статью!';
		elseif($this->is_blocked($id))
			$errors['errors']['activity'] = 'Статью невозможно удалить, так как она в данный момент находится на модерации!';
		
		if(count($errors) > 0)
				return $errors;	
		
		$this->db->where('id', $id);
		$res = $this->db->delete('articles');
		return $res;
	}
	
	//////////////////////////////////////// Проверяем, находится ли статья на модерации ////////////////////
	function is_blocked($id){
		
		$query = $this->db->get_where('article_blockings',array('article_id' => $id));
			if($query->num_rows()>0){
				$row = $query->row();
					if(time_expire_period($row->data_blocked,mktime(),$this->blocked_time))
						return false;
					else
						return true;
			}
			else{
				$solve_insert = false;
			}
			
		return $solve_insert;
	}
	
	function block_article($id, $type = 'moderate'){
		
		if($type == 'moderate'):
				
			if(!$this->is_blocked($id)){
				$this->db->delete('article_blockings',array('article_id' => $id));
				$this->db->insert('article_blockings',array('article_id' => $id, 'type_block' => $type, 'data_blocked' => mktime()));
			}
				
		
		endif;
		
	}
	
	///////////////////////////////////////// Получаем спиоск авторов ////////////////////////////////////////////
	function list_authors($limit, $sorting = false){
		
		$sql = "SELECT users.username,users.created 'data_created', ratings.rating 'author_rating',ratings_activity.rating 'rating_activity',COUNT(articles.id) 'num_articles',authors.name,authors.family FROM users,ratings,ratings_activity,articles,authors WHERE users.id = ratings.user_id AND users.id = authors.user_id AND users.id = articles.user_id AND users.id = ratings_activity.user_id AND articles.activity = 2 GROUP BY users.username ORDER BY author_rating DESC,rating_activity DESC,data_created ASC LIMIT $limit";
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}
	
	///////////////////////////////////////// Получаем кол-во авторов ////////////////////////////////////////////

	function num_authors(){
	
		//$this->db->where('role_id',1);
		//$this->db->from('users');
        $sql = "SELECT DISTINCT users.username FROM users,ratings,ratings_activity,articles,authors WHERE users.id = ratings.user_id AND users.id = authors.user_id AND users.id = articles.user_id AND users.id = ratings_activity.user_id AND articles.activity = 2";
        $query = $this->db->query($sql);
		return $query->num_rows();
	}
	
	/////////////////////////////////////////////// Проверяем, менялся ли в такой-то день рейтинг активности //////
	function is_changed_rating_activity($user_id, $data){
		
		$query = $this->db->get_where('ratings_activity', array('user_id' => $user_id));
		if($query->row()->rating == $this->highest_rating_activity)
			return true;
		$data_modified = $query->row()->data_modified;
		return compare_date($data, $data_modified, 'day');
				
	}
	
	
	///////////////////////////////////// Ручное изменение рейтинга модератором или админом //////////////////
	function change_rating_manually($type, $rating, $user_id, $article_id = null, &$is_quit_sandbox){

		if($type == 'author'){
		
			$query = $this->db->get_where('ratings',array('user_id' => $user_id));
			$current_rating = $query->row()->rating;

			$this->db->set('rating',$rating);
            $this->db->where('user_id', $user_id);
			$res = $this->db->update('ratings');
			if(!$res)
				return false;
			
		}
		elseif($type == 'article' && $article_id){
		
			$this->db->where('id', $article_id);
			$this->db->set('rating', $rating);
			$res = $this->db->update('articles');
			if(!$res)
				return false;
			
			$query = $this->db->get_where('ratings',array('user_id' => $user_id));
			$current_rating = $query->row()->rating;
			$old_rating = $this->input->post('old_rating');
			$add = $rating - $old_rating;

			$this->db->set('rating',"rating + $add", false);
            $this->db->where('user_id', $user_id);
			$res = $this->db->update('ratings');
			if(!$res)
				return false;
			$this->db->select('id,heading_id,user_id,is_special');
			$this->db->from('articles');
			$this->db->where('id',$article_id);
			$article = $this->db->get();
			
			if($this->is_quit_sandbox($article->row(), mktime())){
				$is_quit_sandbox = true;
				$this->add_author_rating($article->row(), date("Y-m-d H:i:s"));
			}
			
		}
		else{
			
			$query = $this->db->get_where('ratings_activity',array('user_id' => $user_id));
			$current_rating = $query->row()->rating;
			
			$this->db->set('rating',$rating);
            $this->db->where('user_id', $user_id);
			$res = $this->db->update('ratings_activity');
			if(!$res)
				return false;
		}
		
		$change_rating = $rating - $current_rating;
		
		$data = array(
		'id' => null,
		'user_id' => $user_id,
		'change_rating' => $change_rating,
		'type' => $type,
		'data' => date("Y-m-d H:i:s")
		);
		
		$res = $this->db->insert('change_rating',$data);
		if(!$res)
			return false;
		else
			return $rating;
		
	}
	
	////////////////////////////////////////////// Удалить исключение //////////////////////////////////////////////////////////////
	
	function delete_refer_exception($id){
		$res = $this->db->delete('refer_page_exceptions', array('id' => $id));
		return $res;
	}
	
	///////////////////////////////////////////// Добавить исключение страницы, где не будет реферирования /////////////////////////
	
	function add_refer_exception(){
		$url = $this->input->post('url');
		$description = $this->input->post('description');
		$res = $this->db->get_where('refer_page_exceptions',array('page' => $url));
		if(!$res || $res->num_rows() < 1){
			$data = array(
			'id' => null,
			'page' => $url,
			'description' => $description
			);
			$res_insert = $this->db->insert('refer_page_exceptions',$data);
			if($res_insert)
				return true;
			else
				return false;
		}
		else{
			return false;
		}	

	}
	
	/////////////////////////////////// Получаем рефералы по uid ////////////////////////////////////////////////
	
	function get_refers_by_user_id($user_id,$limit = null){
		$sql = "SELECT ur.id,ur.user_id,ur.data,u.username, COUNT(a.id) AS articles_num FROM user_refers ur JOIN users u ON u.id = ur.user_id LEFT JOIN articles a ON a.user_id = ur.user_id WHERE ur.refer_id = $user_id GROUP BY ur.id";
//		$sql = "SELECT DISTINCT count(a.id) as articles, SUM(sa.num_all) as sa_num, ur.id,ur.user_id,ur.data,u.username  FROM articler_work.user_refers ur JOIN articler_work.users u ON u.id = ur.user_id LEFT JOIN articler_work.articles a ON a.user_id = ur.user_id LEFT JOIN articler_statistic.statistic_day sa ON a.id = sa.article_id WHERE ur.refer_id = 3 GROUP BY ur.user_id";
		$res = $this->db->query($sql);
		if(!$res)
			return false;
		return $res->result_array();
	}
	
	////////////////////////////////////// Если юзера является приглашенным проверяем, когда закончится льготный период ////////////////////////////////////
	
	function get_refer_period_by_user_id($user_id){
		$this->db->where('user_id',$user_id);
		$this->db->order_by('data','DESC');
		$sql = "SELECT UNIX_TIMESTAMP(data) - UNIX_TIMESTAMP(NOW()) AS time FROM user_refers WHERE user_id = $user_id ORDER BY data DESC LIMIT 1";
		$res = $this->db->query($sql);
		if(!$res)
			return false;
		if($res->row()->time < 0)
			return false;
		$time = (int)$res->row()->time;
		return ceil($time/86400);
	}
	
	function is_refer_exceptions($uriSegments){
		
		$page = implode('/',$uriSegments);
		$exceptions = $this->get_refer_pages_exceptions(true);
		if(in_array($page,$exceptions))
			return true;
		return false;
	}
	
	/////////////////////////////////// Получаем страницы исключений для рефералов //////////////////////////////////////
	function get_refer_pages_exceptions($autoload=false){
		$this->db->order_by('page');
		$res = $this->db->get('refer_page_exceptions');
		if(!$res)
			return array();
		if($autoload){
			$arr = array();
			foreach($res->result_array() as $item){
				$arr[] = $item['page'];
			}	
			return $arr;
		}
		else
			return $res->result_array();
	}
	
	/////////////////////////////////// Получаем все рефералы //////////////////////////////////////////////////
	function get_all_refers($limit = null){
		if($limit)
//			$sql = "SELECT ur.id,ur.user_id,UNIX_TIMESTAMP(ur.data) + $this->refer_add AS data_end,u.username,( SELECT username FROM users WHERE id = ur.refer_id) AS refername,a.fullname FROM user_refers ur JOIN users u ON u.id = ur.user_id JOIN authors a ON a.user_id = ur.refer_id";
			$sql = "SELECT DISTINCT ur.refer_id, u.username FROM user_refers ur JOIN users u ON u.id = ur.refer_id ";
		else
//			$sql = "SELECT ur.id,ur.user_id,UNIX_TIMESTAMP(ur.data) + $this->refer_add AS data_end,u.username,( SELECT username FROM users WHERE id = ur.refer_id) AS refername,a.fullname FROM user_refers ur JOIN users u ON u.id = ur.user_id JOIN authors a ON a.refer_id = ur.user_id$limit";
			$sql = "SELECT DISTINCT ur.refer_id, u.username FROM user_refers ur JOIN users u ON u.id = ur.refer_id $limit ";

		$res = $this->db->query($sql);
		if(!$res)
			return false;

		return $res->result_array();
	}
	
	
	////////////////////////////////// Получаем список статусов авторов ////////////////////////////////////////
	
	function get_author_statuses($get = 'points', $value = null){
		
		$sql = "SELECT * FROM `articler_settings` WHERE `key` LIKE '%minimum_num_$get%' ORDER BY value" ;
		$query = $this->db->query($sql);
        $buffer = $query->result_array();
        foreach($buffer as $elem){
            if($value == null)
                $new_arr[] = $elem['show_key'];
            else
                $new_arr[$elem['show_key']] = $elem['value'];
            
        }
		return $new_arr;
	}
	
	/////////////////////////////////////////////////// Получаем индивидуальный коэффициент, корректирующий кол-во просмотров //////////////////////
	function get_author_corrective_q($user_id){
		/*
		$this->db->select('corrective_q');
		$this->db->from('authors');
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			$result = $query->result_array();
			return $result[0]['corrective_q'];
		}
		*/
		return 1;
	}
	
	/*
		На основании рейтинга автора получаем группу, к которой он относится
	*/
	
	function get_author_group($author_rating, $num_articles, $statuses_points = null, $statuses_articles = null){

        if($statuses_points == null && $statuses_articles == null):
		$sql = "SELECT * FROM `articler_settings` WHERE `key` LIKE '%minimum_num_points%' ORDER BY value" ;
		$query = $this->db->query($sql);
        $buffer = $query->result_array();
        foreach($buffer as $elem){
            $new_arr[] = $elem['show_key'];
            
        }
        arsort($buffer);
		foreach($buffer as $elem){
            
			if($author_rating >= $elem['value']*1){
				$rating_group = $elem['show_key'];
				break;
			}
		}
      
		if(empty($rating_group))
			return false;
			
		$sql = "SELECT * FROM `articler_settings` WHERE `key` LIKE '%minimum_num_articles%' ORDER BY value" ;
		$query = $this->db->query($sql);
        $buffer = $query->result_array();
        arsort($buffer);
		foreach($buffer as $elem){
			if($num_articles >= $elem['value']*1){
				$num_group = $elem['show_key'];
				break;
			}
		}

        else:
		  $new_arr = array_keys($statuses_points);
		  arsort($statuses_points);
		  arsort($statuses_articles);
          foreach($statuses_points as $key=>$value) {
		  	
			if($author_rating >= $value){
				$rating_group = $key;
				break;
			}
			
		  }
		  
		  if(empty($rating_group))
			return false;
			
			foreach($statuses_articles as $key=>$value) {
		  	
			if($num_articles >= $value){
				$num_group = $key;
				break;
			}
			
		  }

        endif;
		
        
        for($i = 0; $i < count($new_arr); ++$i){
            
            if($rating_group == $new_arr[$i] && $num_group == $new_arr[$i]){
                return $rating_group;
            }
            elseif($rating_group == $new_arr[$i] && $num_group == $new_arr[$i-1]){
                return $num_group;
            }
            elseif($rating_group == $new_arr[$i] && $num_group == $new_arr[$i+1]){
                return $rating_group;
            }
            else{
                return $rating_group;
            }
        }
           
	}
	
	///////////////////////////////////// Изменения авторского рейтинга или рейтинга активности //////////////////
	function change_rating($user_id, $num = 10, $type = 'author', $operation = '+'){
		
		if($type == 'author')
			$query = $this->db->get_where('ratings',array('user_id' => $user_id));
		else
			$query = $this->db->get_where('ratings_activity',array('user_id' => $user_id));
		$curr_date = date("Y-m-d H:i:s");
		
		if($type == 'comment'){
			if($this->is_changed_rating_activity($user_id, $curr_date))
				return false;
		}
			
		
		if($type == 'author'){
			$type_last_change = 'article';
		}
		else{
			$type_last_change = 'comment';
		}
		if(count($query->result_array())>0){
		
		if($type == 'author'){
			
		$old_status = $this->get_author_group($query->row()->rating, $this->get_num_articles_by_user_id($user_id));
		
        if($operation == '+')
		    $this->db->set('rating',"rating+$num",false);
        else
            $this->db->set('rating',"rating-$num",false);

		    $this->db->set('data_modified',$curr_date);
            $this->db->where('user_id', $user_id);
		$res = $this->db->update('ratings');
		
		$new_status = $this->get_author_group($this->get_rating($user_id),$this->get_num_articles_by_user_id($user_id));
		if($new_status != $old_status){
			$this->message_change_status($user_id, $new_status);

		}
		}
		
		else{
			
		
            if($operation == '+')
                $this->db->set('rating',"rating+$num",false);
            else
                $this->db->set('rating',"rating-$num",false);
		$this->db->set('data_modified',$curr_date);
		$this->db->set('type_last_change',$type_last_change);
        $this->db->where('user_id', $user_id);
		$res = $this->db->update('ratings_activity');		
		
		}
		
		}
		else{
		
		if($type == 'author'):		
			$data = array(
			'user_id' => $user_id,
			'rating' => $num,
			'data_modified' => $curr_date,
			);
			$res = $this->db->insert('ratings',$data);
			
		else:
		
		$data = array(
			'user_id' => $user_id,
			'rating' => $num,
			'data_modified' => $curr_date,
			'type_last_change' => $type_last_change
			);
			$res = $this->db->insert('ratings_activity',$data);
		
		endif;
		
		}
	}
	
	function comment_exists($user_id, $article_id){
		
		$query = $this->db->get_where('article_comments',array('user_id' => $user_id, 'article_id' => $article_id));
		if($query->row())
			return true;
		else
			return false;
	}
	
	function get_avatar_by_user_id($user_id){
		
		$query = $this->db->get_where('user_avatars',array('user_id' => $user_id));
		return $query->row();
	}
	
	function insert_comment($user_id, $reply_id = null){
		
			$article_owner_user_id = (int)$_POST['article_owner_user_id'];
			if(isset($_POST['private']) && $_POST['private']){
				$comments_table = 'private_comments';
				$article_table = 'private_articles';
			}
			else{
				$comments_table = 'article_comments';
				$article_table = 'articles';

			}
			
			if($reply_id != null){
				
				$sql = "SELECT $comments_table.id 'id',users.username 'username', users.id 'user_id' FROM $comments_table,users WHERE users.id = $comments_table.user_id AND $comments_table.id = $reply_id";
				$query = $this->db->query($sql);
				$reply_user_id = $query->row()->user_id;
				$reply_username = $query->row()->username;
				$answered = 1;
			
			}
			else{
				$answered = null;
			}
			
		$user_id = (int)$this->input->post('user_id');
		if(!$user_id)
			return false;
		$data = array(
			'id' => null,
			'article_id' => $this->input->post('article_id'),
			'user_id' => $this->input->post('user_id'),
			'reply_id' => $reply_id,
			'comment' => strip_tags($this->input->post('comment_text')),
			'data' => $date = date("Y-m-d H:i:s")
		);
		
		$res = $this->db->insert($comments_table,$data);
		if($res && $reply_id != null){
			$this->db->where('id',$reply_id);
			$this->db->update($comments_table, array('answered' => 1));
		}
		
		if($res){
			$query = $this->db->get_where($comments_table, array('article_id' => $this->input->post('article_id'), 'user_id' => $this->input->post('user_id')));
				$sql = "SELECT $comments_table.* ,users.username 'username', $article_table.header 'header', $article_table.url 'article_url', authors.name, authors.family FROM $comments_table,users,$article_table,authors WHERE users.id = $comments_table.user_id AND authors.user_id = users.id AND $article_table.id = $comments_table.article_id AND $comments_table.user_id = ".$this->input->post('user_id')." AND $comments_table.article_id = ".$this->input->post('article_id')." ORDER BY $comments_table.data DESC LIMIT 1";
		$query = $this->db->query($sql);
		$result = $query->row();
		$query2 = $this->db->get_where('users', array('id' => $article_owner_user_id));
		if($reply_id != null)
        	$to = $this->articler_users->get_email($reply_user_id);
		else
			$to = $this->articler_users->get_email($article_owner_user_id);

		$subject = 'Добавление комментария';
		
		if($reply_id == null)
			$to_username = $query2->row()->username;
		else
			$to_username = $reply_username;
		$header = $result->header;
		$data = time_change_show_data($result->data);
		$from_username = $this->dx_auth->get_username();
		
		if($reply_id == null){
			$message = $this->settings_model->get_mail_template('add_comment','property','text');
			$message = str_replace('%автор%',ucfirst($to_username),$message);
			$header = '<a href="'.$this->site_url_without_slash.$result->article_url.'">'.ucfirst($header).'</a>';
			$message = str_replace('%статья%',$header,$message);
			$from_username = '<a href="'.$this->site_url_without_slash.'/avtory/'.$from_username.'">'.ucfirst($from_username).'</a>';
			$message = str_replace('%пользователь%',$from_username,$message);
		}
		else{
			$message = $this->settings_model->get_mail_template('reply_comment','property','text');
			$message = str_replace('%автор%',ucfirst($to_username),$message);
			$header = '<a href="'.$this->site_url_without_slash.$result->article_url.'">'.ucfirst($header).'</a>';
			$message = str_replace('%статья%',$header,$message);
			$from_username = '<a href="'.$this->site_url_without_slash.'/avtory/'.$from_username.'">'.ucfirst($from_username).'</a>';
			$message = str_replace('%пользователь%',$from_username,$message);
			$message = str_replace('%дата%',$data,$message);
			
		}

		$email = $this->email;
		if(defined('USE_SENDMAIL'))
			$email->protocol = 'sendmail';
		$email->mailtype = 'html';
		$email->from($this->site_mail);
		$email->to($to);
		$email->subject($subject);
		$email->message($message);
		if(!defined('LOCAL'))
			$res_email = $email->send();
			if($result)
				return $result;
			else
				return false;
		}
		else{
			return false;
		}
			
	}
	
	
	function count($table, $where = 1){
			
		$sql = "SELECT COUNT(*) AS 'num' FROM $table WHERE $where";
		$query = $this->db->query($sql);
		return $query->row()->num;
	}
	
	function get_num_comments_by_article_id($article_id, $private=false){
		if($private)
			return $this->count('private_comments', "article_id = $article_id AND user_id > 0");
		else
			return $this->count('article_comments', "article_id = $article_id AND user_id > 0");
		
	}
	
	function get_num_comments_to_user_id($user_id){
		
		return $this->count('article_comments', "article_id IN (SELECT id FROM articles WHERE user_id = $user_id)");

	}
	

	
	function get_num_payouts_by_status_id($status_id){
	
		return $this->count('articler_payouts', "status = $status_id");
	}
	
	function get_num_comments_by_user_id($user_id,$private=false){
		
		if($private)
			return $this->count('private_comments', "user_id = $user_id");
		else
			return $this->count('article_comments', "user_id = $user_id");
	}
	
	function get_num_articles_by_activity($activity){
		
		return $this->count('articles', "activity = $activity");
		
	}
	
	function get_num_articles_for_home_feed($mktime){
		
		$sql = '';
		$count = 0;
		$time_hold = $this->sandbox_hold * 86400;
		$mktime = mktime();
		if($this->use_divided_rating_show):
		foreach($this->rating_for_show as $key=>$value):
			$sql .= "SELECT COUNT(*) AS 'num' FROM articles,headings,type_headings WHERE articles.activity = 2 AND articles.heading_id = headings.id AND headings.type_heading = type_headings.id AND type_headings.id = $key AND articles.rating >= $value AND (UNIX_TIMESTAMP(data_published) + $time_hold) < $mktime";
			if($key != $this->settings_model->last_type_heading)
				$sql .= ' UNION ';
		endforeach;
		else:
			$value = array_shift($this->rating_for_show);
			$sql .= "SELECT COUNT(*) AS 'num' FROM articles WHERE articles.activity = 2  AND rating >= $value AND (UNIX_TIMESTAMP(data_published) + $time_hold) < $mktime";
		endif;
		$sql = "SELECT COUNT(*) AS 'num' FROM articles WHERE articles.activity = 2";
		$query = $this->db->query($sql);
		foreach($query->result_array() as $row){
			$count += $row['num'];
		}

		return $count;
	}
	
	function get_num_articles_for_sandbox($mktime){

		$sql = '';
		$count = 0;
		$sql = '';
		$time_hold = $this->sandbox_hold * 86400;
		$mktime = mktime();
		if($this->use_divided_rating_show):
		foreach($this->rating_for_show as $key=>$value):
			$sql .= "SELECT COUNT(*) AS 'num' FROM articles,headings,type_headings WHERE articles.activity = 2  AND articles.heading_id = headings.id AND headings.type_heading = type_headings.id AND type_headings.id = $key AND (articles.rating < $value OR (UNIX_TIMESTAMP(data_published) + $time_hold) > $mktime)";
		if($key != $this->settings_model->last_type_heading)
				$sql .= ' UNION ';
		endforeach;
		else:
			$value = array_shift($this->rating_for_show);
			$sql .= "SELECT COUNT(*) AS 'num' FROM articles WHERE articles.activity = 2  AND (articles.rating < $value OR (UNIX_TIMESTAMP(data_published) + $time_hold) > $mktime)";
		endif;
		$query = $this->db->query($sql);
		foreach($query->result_array() as $row){
			$count += $row['num'];
		}

		return $count;	
	}
	
	function get_num_articles_by_heading_id($heading_id, $activity = 2){
		
		if($activity != 'all')
			$activity = 'AND activity = '.$activity;
		return $this->count('articles', "heading_id = $heading_id $activity");
		
	}
	
	function get_num_article_comments_by_user_id($user_id = null){
		
		if($user_id == null )
			$user_id = $this->dx_auth->get_user_id();
			
			return $this->count('article_comments', "user_id = $user_id");

	}
	
	function get_all_num_articles($activity = 'all'){
			
			if($activity == 'all'){
				return $this->count('articles');

			}
			else{
				return $this->count('articles', "activity = $activity");

			}
		
	}
	
	function get_num_refers_by_user_id($user_id = null){
		if($user_id == null )
			$user_id = $this->dx_auth->get_user_id();
			return $this->count('user_refers', "refer_id = $user_id");
		
	}
	
	function get_num_articles_by_user_id($user_id = null, $activity = 2){
		
		if($user_id == null )
			$user_id = $this->dx_auth->get_user_id();
			
			if($activity == 'all'){
				return $this->count('articles', "user_id = $user_id");

			}
			else{
				return $this->count('articles', "user_id = $user_id AND activity = $activity");

			}
		
	}
	
	///////////////////////////////////////////// Получить массив инфы по некоторым аватарам //////////////////////////
	function get_selected_avatars($arr){
		$user_ids = array_complex_unique($arr,'user_id');
		if(count($user_ids) == 0)
			return false;
		$this->db->where_in('user_id', $user_ids);
		$query = $this->db->get('user_avatars');
		if($query->num_rows == 0)
			return false;
		foreach($query->result_array() as $elem){
			$avatars[$elem['user_id']] = $elem;
		}
		return $avatars;
	}
	
	///////////////////////////////////////////// Получить аватар автора //////////////////////////////////////////////
	function get_avatar($user_id){
		$query = $this->db->get_where('user_avatars', array('user_id' => $user_id));
		if(count($query->result_array()) > 0)
			return $query->row();
		return false;
	}
	
	///////////////////////////////////////////// Функция, проверяющая существование аватара //////////////////////////
	function exists_avatar($user_id){
		$path = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/avatars/';
		if(file_exists($path.$user_id.'.jpg')){
			$result['path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/avatars/'.$user_id.'.jpg';
			$result['ext'] = 'jpg';
			return $result;
		}
		elseif(file_exists($path.$username.'.png')){
			$result['path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/avatars/'.$username.'.png';
			$result['ext'] = 'png';
			return $result;
		}
		elseif(file_exists($path.$username.'.gif')){
			$result['path'] = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/avatars/'.$username.'.gif';
			$result['ext'] = 'gif';
		}
		return false;
	}
	
	function get_rating($user_id, $type = 'author'){
		
		if($type == 'author')
			$query = $this->db->get_where('ratings', array('user_id' => $user_id));
		else
			$query = $this->db->get_where('ratings_activity', array('user_id' => $user_id));

		return $query->row()->rating;
	}
	
	function select_private_comments($article_id, $limit = ''){
		if($limit != '')
			$limit = 'LIMIT '.$limit;
		$sql = "SELECT private_comments.*,users.username 'username',users.id 'user_id', authors.name,authors.family FROM private_comments,users,authors WHERE users.id = private_comments.user_id AND private_comments.article_id = $article_id AND authors.user_id = users.id ORDER BY private_comments.data DESC $limit " ;
		$query = $this->db->query($sql);
		return $query;
	}
	
	function select_comments($article_id, $limit = ''){
		
		if($limit != '')
			$limit = 'LIMIT '.$limit;
		$sql = "SELECT c.id,c.comment,c.data,c.answered,u.username,a.fullname,a.name,a.family,ua.user_id AS avatar_userid,ua.small_width,ua.small_height,ua.ext,c.user_id AS user_id FROM article_comments c  JOIN users u ON u.id = c.user_id LEFT JOIN authors a ON c.user_id = a.user_id LEFT JOIN user_avatars ua ON ua.user_id = c.user_id WHERE c.article_id = $article_id ORDER BY c.data DESC $limit";
		$query = $this->db->query($sql);
		return $query;
	}
	
	function more_comments($article_id, $limit = ''){
		
		if($limit != '')
			$limit = 'LIMIT '.$limit;
		$sql = "SELECT c.id,c.comment,c.data,c.answered,u.username,a.fullname,ua.user_id AS avatar_userid,ua.small_width,ua.small_height FROM article_comments c  JOIN users u ON u.id = c.user_id LEFT JOIN authors a ON c.user_id = a.user_id LEFT JOIN user_avatars ua ON ua.user_id = c.user_id WHERE c.article_id = $article_id ORDER BY c.data DESC $limit";
		$query = $this->db->query($sql);
		return $query;
	}
	
	////////////////////////////////////// Загрузка картинки к статье //////////////////////////////////
	function load_image_article($user_id, &$response){
	
	if(empty($_FILES['image']['name']))	
		return false;
	$file_name = $_FILES['image']['name'];
	$file_name_tmp = $_FILES['image']['tmp_name'];
	$upload_dir = date('Y').'/'.date('m');
	if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.date('Y'))){
		mkdir($_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.date('Y'));
	}
	
	if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.$upload_dir)){
		mkdir($_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.$upload_dir);
	}
		
	$file_new_name = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.$upload_dir.'/'; 
	if($this->dx_auth->get_role_name() == 'moderator')
		$hash_picture = 'private_'.date('d').date('h').date('i');
	else
		$hash_picture = date('d').date('h').date('i').'_'.$user_id;
		
	$buffer_name = explode('.',$file_name);
	$ext = '.'.array_pop($buffer_name);
	if(count($buffer_name) == 1)
		$temp_file_name = $buffer_name[0];
	else
		$temp_file_name = implode('.',$buffer_name);
	
	$file_name = $temp_file_name.'_'.$hash_picture.$ext;
	$full_path = $file_new_name.$file_name;

	$http_path = '/uploads/images/publications/'.$upload_dir.'/'.$file_name; // адрес изображения для обращения через http
	
	if( move_uploaded_file($file_name_tmp, $full_path) )
	{
		// можно добавить код при успешном выполнение загрузки файла
		$response['error'] = '';
		$response['file'] = $http_path;
	}
	 else
	{
		$response['error'] = 'Ошибка загрузки картинки'; // эта ошибка появится в браузере если скрипт не смог загрузить файл
	}
		return true;
	
	}
	
	function insert_article($user_id = null){
		
		$heading = $this->get_heading_by_id($this->input->post('headings'));	
		$errors = $this->validate_publication($heading);
		
		if(count($errors) > 0)
			return $errors;	
		
		if($user_id == null)
			$user_id = $this->dx_auth->get_user_id();
		
		$url = strip_tags($this->input->post('url'));
		$header = $this->input->post('header');
		$header = strip_tags($header);
		$response_upload = array();
		$res_upload = $this->load_image_article($user_id, $response_upload);
		if($res_upload){
			if($response_upload['error']){
				$res['errors']['upload_image'] = $response_upload['error'];
				return $res;
			}
			else{
				$image = $response_upload['file'];
			}
		}
		else
		{
			$image = null;
		}
		
				
		$data = array(
		'id' => null,
		'heading_id' => $this->input->post('headings'),
		'user_id' => $user_id,
		'header' => $header,
		'url' => '/'.$heading->name.'/'.$url.'.html',
        'description' => $this->input->post('description'),
        'keywords' => $this->input->post('keywords'),
		'annotation' => $this->input->post('annotation'),
		'content' => $this->input->post('editor'),
		'activity' => 0,
		'data_saved' => date("Y-m-d H:i:s"),
		'data_last_modified' => '0000-00-00 00:00:00',
		'data_published' => '0000-00-00 00:00:00',
		'rating' => 0,
		'is_special' => 0,
		'giventopic_id' => null,
		'in_sandbox' => null,
		'image' => $image
		);
		
		$res = $this->db->insert('articles',$data);
		
		return $res;
		
	}
	
	function get_outer_link($article_id, $get = '*'){
		
		if($get == '*'){
			$query = $this->db->get_where('links_to_site', array('article_id' => $article_id));
			if(count($query->row_array()) > 0)
				return $query->row_array();
			else
				return false;
		}
		else{
			$this->db->select($get);
			$this->db->from('links_to_site');
			$this->db->where('article_id',$article_id);
			$query = $this->db->get();
			if(count($query->row_array()) > 0)
				return $query->row()->$get;
			else
				return false;
		}
	}
	
	function count_moderate_articles(){
		
		$this->db->where('activity',1);
		$this->db->from('articles');
		return $this->db->count_all_results();
		
	}
	
	
	
	function all_list_articles($activity, $limit = '', $count = false){
	
		if($limit != '')
			$limit = "LIMIT $limit";
			
			
			if($activity == 2)
				$order = 'articles.data_published DESC';
			elseif($activity == 1)
				$order = 'articles.data_moderated ASC';
			elseif($activity == 0)
				$order = 'articles.data_saved DESC';
			else 
				$order = 'articles.header';
			$and = '';
			if(isset($_REQUEST['search'])){
				if(isset($_REQUEST['headings']) && $_REQUEST['headings'] != '0')
					$and .= ' AND articles.heading_id = '.$this->input->get_post('headings');
					
				if(($_REQUEST['data1'] == '00.00.0000' || empty($_REQUEST['data1'])) && ($_REQUEST['data2'] == '00.00.0000' || empty($_REQUEST['data2']))){
				
				}
				elseif($_REQUEST['data1'] == '00.00.0000'){
					$and .= " AND articles.data_published <= '".data_to_db($this->input->get_post('data2'))."'";
				}
				elseif($_REQUEST['data2'] == '00.00.0000'){
					$and .= " AND articles.data_published >= '".data_to_db($this->input->get_post('data1'))."'";

				}
				else{
					$and .= " AND articles.data_published BETWEEN '".data_to_db($this->input->get_post('data1'))."' AND '".data_to_db($this->input->get_post('data2'))."'";
				}
				
				if(!$_REQUEST['rating_from'] && !$_REQUEST['rating_to']){
					
				}
				elseif(!$_REQUEST['rating_from']){
					$and .= " AND articles.rating <= ".$this->input->get_post('rating_to');
				}
				elseif(!$_REQUEST['rating_to']){
					$and .= " AND articles.rating >= '".$this->input->get_post('rating_from')."'";

				}
				else{
					$and .= " AND rating BETWEEN ".$this->input->get_post('rating_from')." AND ".$this->input->get_post('rating_to');
				}
				
				if(isset($_REQUEST['is_special'])){
					$and .= ' AND articles.is_special = 1';
				}
				
				if($_REQUEST['header']){
					$and .= " AND articles.header LIKE '%".$this->input->get_post('header')."%'";
				}
				
				if($_REQUEST['article']){
					$and .= " AND (articles.annotation LIKE '%".$this->input->get_post('article')."%' OR articles.content LIKE '%".$this->input->get_post('article')."%')";
				}
				
				if(isset($_REQUEST['outer_link'])){
					$and .= " AND articles.id IN (SELECT article_id FROM links_to_site)";
				}
				
				if($_REQUEST['author']){
					$buffer = explode(' ',trim($this->input->get_post('author')));
					if(count($buffer) == 2){
						$and .= " AND (authors.name = '".$buffer[0]."' AND authors.family = '".$buffer[1]."')";
					}
					else{
						$and .= " AND users.username = '".trim($this->input->get_post('author'))."'";
					}
				}
							
			}
			
		if(!$count){
			$sql = "SELECT articles.id 'id',articles.header 'header', articles.annotation,articles.activity 'activity', articles.url 'url', articles.data_published 'data_published', articles.rating,articles.data_saved 'data_saved', articles.data_moderated 'data_moderated', articles.user_id 'user_id', articles.is_special, headings.name_russian 'heading_name', headings.name 'heading', users.username 'username',authors.name 'author_name',authors.family 'family',links_to_site.link, add_scores.add_score, articler_payments.payment,ROUND(SUM(stat.num_all) * ".$this->pay_for_visit.",1) 'num_visites'
			 FROM articles
			  JOIN headings ON headings.id = articles.heading_id
			  JOIN users ON users.id = articles.user_id
			  LEFT JOIN authors ON authors.user_id = users.id
			  LEFT JOIN links_to_site ON articles.id = links_to_site.article_id
			  LEFT JOIN add_scores ON articles.id = add_scores.article_id
			  LEFT JOIN articler_payments ON articles.id = articler_payments.article_id
			  LEFT JOIN ".$this->statistic_model->repository_statistic.".statistic_day stat ON articles.id = stat.article_id
			  WHERE articles.activity =".$activity." $and
			  GROUP BY articles.id
			  ORDER BY $order $limit";
			$query = $this->db->query($sql);
			$result = $query->result_array();
			foreach($result as $key=>$value){
				$result[$key]['author'] = $value['author_name'].' '.$value['family'];
			}
			return $result;
		}
		else{
			$limit = '';
			$sql = "SELECT COUNT(articles.id) AS 'num' FROM articles JOIN headings ON headings.id = articles.heading_id JOIN users ON users.id = articles.user_id LEFT JOIN authors ON authors.user_id = users.id LEFT JOIN links_to_site ON articles.id = links_to_site.article_id WHERE articles.activity =".$activity." $and ORDER BY $order $limit";	
			$query = $this->db->query($sql);
			return $query->row()->num;
		}
		
	}
	
	function free_adverts_for_heading($id){
		$types = array('over_content','under_content','under_image');
		$types_names = array(
		'over_content' => lang('advert_position_1'),
		'under_content' => lang('advert_position_2'),
		'under_image' => lang('advert_position_3')
		);
		$sql = "SELECT aa.id, aa.name, aa.type, aa.code, 'using' AS mode FROM advert_blocks aa WHERE id IN (SELECT advert_id FROM advert_headings WHERE heading_id = $id) UNION
			SELECT aa.id, aa.name, aa.type, aa.code, 'free' AS mode FROM advert_blocks aa WHERE id NOT IN (SELECT advert_id FROM advert_headings WHERE heading_id = $id)
		";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$current_types = array();
		
		$need_types = array_diff($types,$current_types);
		$html = '<div class="container_free_adverts"><table cellpadding=4 cellspacing=4 border=1>';
		$html .= '<tr><th style="font-style: italic; font-weight: normal;">'.lang('articler_title').'</th><th style="font-weight: normal; font-style: italic;">'.lang('articler_location').'</th><th align=left style="font-weight: normal; font-style: italic;">'.lang('articler_code').'</th><th></th></tr>';
		foreach($result as $item){
			$html .= '<tr>';
			$html .= '<td>'.$item['name'].'</td>';
			$html .= '<td>'.$types_names[$item['type']].'</td>';
			$html .= '<td>'.$item['code'].'</td>';
			if($item['mode'] == 'using')
				$html .= '<td><input type="checkbox" data-id="'.$item['id'].'" style="margin-left: 5px;" name="advert-'.$item['id'].'" checked=""/></td>';
			else
				$html .= '<td><input type="checkbox" data-id="'.$item['id'].'" style="margin-left: 5px;" name="advert-'.$item['id'].'"/></td>';
			$html .= '</tr>';
		}
		
		$html .= '</table></div>';
		return $html;
	}
	
	function get_adverts_for_heading($id){

		$sql = "SELECT aa.id, aa.name, aa.type, aa.code FROM advert_blocks aa WHERE id IN (SELECT advert_id FROM advert_headings WHERE heading_id = $id)";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		
		if(!$result || !count($result))
			return '';
		$tooltip = '<div>';
		foreach($result as $item){
			$tooltip .= '<div><h3>'.$item['name'].'</h3></div>';
		}
		$tooltip.='</div>';
		$html = '<a    
    href="javascript:void(0);"    
    onmouseover="return  overlib(\''.$tooltip.'\',CENTER);"    
    onmouseout="return  nd();">'.lang('articler_look').'  </a> ';

		return $html;
	}
	
	function set_adverts_to_article($article_id){
		$advert_ids = $this->input->post('ids');
		$ids = explode(',',$advert_ids);
		$res = false;
		foreach($ids as $id){
			if(!$id)
				continue;
			$sql = "SELECT COUNT(*) AS num FROM advert_articles WHERE advert_id = $id AND article_id = $article_id";
			$query = $this->db->query($sql);
			$result = $query->row_array();
			if(!$result['num']){
				$data = array(
				'id' => null,
				'advert_id' => $id,
				'article_id' => $article_id
				);
				$res = $this->db->insert('advert_articles',$data);
			}
		}
		return $res;
	}
	
	
	
	function free_adverts_for_article($id){
		$types = array('over_content','under_content','under_image');
		$types_names = array(
		'over_content' => lang('advert_position_1'),
		'under_content' => lang('advert_position_2'),
		'under_image' => lang('advert_position_3')
		);
		$sql = "SELECT aa.id, aa.name, aa.type, aa.code, 'using' AS mode FROM advert_blocks aa WHERE id IN (SELECT advert_id FROM advert_articles WHERE article_id = $id) UNION
			SELECT aa.id, aa.name, aa.type, aa.code, 'free' AS mode FROM advert_blocks aa WHERE id NOT IN (SELECT advert_id FROM advert_articles WHERE article_id = $id)
		";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		$current_types = array();
		
		$need_types = array_diff($types,$current_types);
		$html = '<div style="padding: 20px; margin-bottom: 15px;" class="container_free_adverts"><table cellpadding=4 cellspacing=4>';
		$html .= '<tr><th>'.lang('articler_title').'</th><th>'.lang('articler_location').'</th><th align=left>'.lang('articler_code').'</th><th></th></tr>';
		foreach($result as $item){
			if($item['mode'] == 'using')
				continue;
			$html .= '<tr>';
			$html .= '<td>'.$item['name'].'</td>';
			$html .= '<td>'.$types_names[$item['type']].'</td>';
			$html .= '<td>'.$item['code'].'</td>';
			$html .= '<td><input type="checkbox" data-id="'.$item['id'].'" style="margin-left: 5px;"/></td>';
			$html .= '</tr>';
		}
		$html .= '<tr><td colspan="4">&nbsp;</td></tr>';
		$html .= '<tr><td colspan="4" class="container_save_button"><button onclick="save_advert_article(this,'.$id.');return false;" style="height: 40px; padding-left: 3px; padding-right; 3px;" >'.lang('save').'</button></td></tr>';
		$html .= '</table></div>';
		return $html;
	}
	

	
	function unset_advert_to_article($article_id,$advert_id){
		$res = $this->db->delete('advert_articles', array('article_id' => $article_id, 'advert_id' => $advert_id));
		return $res;
	}
	
	function get_article_adverts($article_id){
		
		$sql = "SELECT aa.id,aa.name,aa.code,aa.type FROM advert_blocks aa WHERE id IN (SELECT advert_id FROM advert_articles WHERE article_id = $article_id)";
		$query = $this->db->query($sql);
		$result = $query->result_array();
		if(!$result || !count($result))
			return '<div><a href="/moderator/advert_article/'.$article_id.'" onclick="set_advert_to_article(this,'.$article_id.');return false;">'.lang('advert_block_add').'</a></div';
		$html = '';
		$types_block = array(
		'over_content' => lang('advert_position_1'),
		'under_content' => lang('advert_position_2'),
		'under_image' => lang('advert_position_3')
		);
		$count = 0;
		$all_count = count($result);
		$html .= '<div>';
		foreach($result as $item){
			$html .= '<span style="margin-right: 10px;">';
			$html .= '<a href="javascript: void(0);" onclick="show_advert(this);return false;" title="'.lang('show_content_block').'" style="text-decoration: none; color: #000; border: 1px dashed; font-size: 12px; padding: 2px;">'.$item['name'].'</a>';
			
			$html .= '<div style="display: none; padding: 10px;">';
			$html .= '<table>';
			$html .= '<tr><td>'.lang('advert_block_title').'</td><td>'.$item['name'].'</td>';
			$html .= '<tr><td>'.lang('advert_block_type').'</td><td>'.$types_block[$item['type']].'</td>';
			$html .= '<tr><td>'.lang('advert_content').'</td><td>'.$item['code'].'</td>';
			$html .= '</table>';
			$html .= '</div>';
			$html .= '<a href="/moderator/advert_article/remove/'.$article_id.'/'.$item['id'].'" style="text-decoration: none; color: red; border-bottom: 1px dashed; font-size: 14px; margin-left: 5px;" title="'.lang('advert_block_unlink').'" onclick="remove_advert_article(this,'.$article_id.');return false;">x</a>';
			$html .= '</span>';
			
		}
		$sql = "SELECT COUNT(*) AS num FROM advert_blocks";
		$query = $this->db->query($sql);
		$result = $query->row_array();
		if($all_count < $result['num']){
			$html .= '<span>';
			$html .= '<a href="/moderator/advert_article/'.$article_id.'" style="margin-left: 20px;" onclick="set_advert_to_article(this,'.$article_id.');return false;">'.lang('advert_block_add').'</a>';
			$html .= '</span>';
		}
		$html .= '</div>';
		return $html;
	}
	
	function num_opened_payouts(){
		
		$sql = "SELECT COUNT(*) AS 'num_payouts' FROM articler_payouts WHERE status = 0";
		$query = $this->db->query($sql);
		if($query->row())
			return $query->row()->num_payouts;
		else
			return 0;
	}
	
	function num_unconsidered_pleas(){
		
		$sql = "SELECT COUNT(*) AS 'num_pleas' FROM user_pleas WHERE considered = 0";
		$query = $this->db->query($sql);
		if($query->row())
			return $query->row()->num_pleas;
		else
			return 0;
	}
	
	function list_pleas($user_id = null, $limit){
			
		$sql = "SELECT user_pleas.*,article_comments.comment,articles.url 'article_url',users.username 'username' FROM user_pleas,articles,users,article_comments WHERE  users.id = user_pleas.user_id AND articles.id = user_pleas.article_id AND user_pleas.comment_id = article_comments.id ORDER BY data ASC,considered ASC LIMIT $limit";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function list_comments($user_id = null, $limit = ''){
		
		if($user_id == null)
			$user_id = $this->dx_auth->get_user_id();		
		
		if($limit != '')
			$limit = 'LIMIT '.$limit;
			
		$order = 'article_comments.data DESC';
		
		$sql = "SELECT article_comments.id 'id',article_comments.comment 'comment', article_comments.data 'data', articles.header 'article_header', articles.url 'article_url', headings.name_russian 'heading_name_russian', headings.name 'heading_name' FROM article_comments,articles,headings WHERE headings.id = articles.heading_id AND article_comments.article_id = articles.id AND article_comments.user_id =".$user_id." ORDER BY $order $limit";
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}
	
	function list_articles($user_id = null, $limit = ''){
		
		if($user_id == null)
			$user_id = $this->dx_auth->get_user_id();		
		
		if($limit != '')
			$limit = 'LIMIT '.$limit;
			
		$order = 'articles.data_saved DESC';
		
		$sql = "SELECT articles.id 'id',articles.header 'header', articles.annotation,articles.activity 'activity', articles.url 'url', articles.data_published 'data_published', articles.data_saved 'data_saved',articles.rating,articles.data_last_modified 'data_last_modified', headings.name 'heading_name', headings.name_russian 'heading_name_russian' FROM articles,headings WHERE headings.id = articles.heading_id AND articles.user_id =".$user_id." ORDER BY $order $limit";
		$query = $this->db->query($sql);
		$result_array = $query->result_array();
		$ids = $this->get_articles_by_user($user_id);
		if(count($ids) > 0):
		foreach($ids as $elem){
			$arr[] = $elem['id'];
		}
		
		
		$sql = 'SELECT * FROM rejection_reasons WHERE article_id IN ('.implode(',',$arr).')';
		$rejection_query = $this->db->query($sql);
		foreach($rejection_query->result_array() as $elem){
			$new_arr[$elem['article_id']] = $elem['reason'];
		}
		
		
		if(count($new_arr)>0){
								
			for($i = 0; $i < count($result_array); $i++){
				if(isset($new_arr[$result_array[$i]['id']]))
					$result_array[$i]['rejection_reason'] = $new_arr[$result_array[$i]['id']];
			}
		}
		
		endif;
				
		for($i = 0; $i < count($result_array); $i++){
			$outer_link = $this->get_outer_link($result_array[$i]['id'],'link');
			if($outer_link && $result_array[$i]['activity'] != 2)
				$result_array[$i]['outer_link'] = $outer_link;
		}
		
		
		return $result_array;
	}
	
	
	function get_articles_for_home_feed($mktime,$limit = null){
		
		if($limit != null)
			$limit = 'LIMIT '.$limit;
		else
			$limit = '';
		$sql = '';
		$time_hold = $this->sandbox_hold * 86400;
		$mktime = mktime();
		if($this->use_divided_rating_show):
		foreach($this->rating_for_show as $key=>$value):
			$sql .= "SELECT a.header,a.id AS id,a.url,a.data_published,a.annotation,a.rating,h.name 'heading_name', h.name_russian 'name_russian', u.username 'username',au.name,au.family,COUNT(ac.id) AS comments, a.heading_id FROM articles a JOIN headings h ON WHERE a.heading_id = h.id JOIN type_headings th ON h.type_heading = th.id  JOIN authors au ON a.user_id = au.user_id JOIN users u ON a.user_id = u.id LEFT JOIN article_comments ac ON a.id = ac.article_id WHERE a.activity = 2 AND th.id = $key AND a.in_sandbox IS NULL";
			if($key != $this->settings_model->last_type_heading)
				$sql .= ' UNION ';
		endforeach;
		else:
		$value = array_shift($this->rating_for_show);
		$rating = $value;
		$sql .= "SELECT a.header,a.id,a.url,a.data_published,UNIX_TIMESTAMP(a.data_published) AS data,a.annotation,a.rating,h.name 'heading_name', h.name_russian 'name_russian', u.username 'username',au.name,au.family,COUNT(ac.id) AS comments, a.heading_id FROM articles a JOIN headings h ON a.heading_id = h.id  JOIN authors au ON a.user_id = au.user_id JOIN users u ON a.user_id = u.id LEFT JOIN article_comments ac ON a.id = ac.article_id WHERE a.activity = 2";

		endif;
		$sql .= " GROUP BY a.id ORDER BY data_published DESC $limit";

		$query = $this->db->query($sql);
		$arr = $query->result_array();
		foreach($arr as $key=>$value){
			if($value['rating'] < $rating || ($value['data'] + $time_hold) > $mktime)
				$arr[$key]['in_sandbox'] = "(статья в <a href=\"".site_url('pesochnica')."\" target=\"_blank\" style=\"color:#FF3000;\">Песочнице</a>) ";
			else
				$arr[$key]['in_sandbox'] = '';

		}
		return $arr;
	}
	
	
	
	function get_articles_for_sandbox($mktime,$limit = null){
		
		if($limit != null)
			$limit = 'LIMIT '.$limit;
		else
			$limit = '';
		$sql = '';
		$time_hold = $this->sandbox_hold * 86400;
		$mktime = mktime();
		if($this->use_divided_rating_show):
		foreach($this->rating_for_show as $key=>$value):
//			$sql .= "SELECT articles.header,articles.id,articles.url,articles.is_special,articles.data_published,articles.annotation,articles.rating,headings.name 'heading_name', headings.name_russian 'name_russian', users.username 'username',authors.name,authors.family FROM articles,headings,users,authors,type_headings WHERE articles.heading_id = headings.id AND headings.type_heading = type_headings.id AND type_headings.id = $key AND articles.user_id = authors.user_id AND articles.user_id = users.id AND articles.activity = 2 AND articles.in_sandbox = 1";
			$sql .= "SELECT a.header,a.id,a.url,a.is_special,a.data_published,a.annotation,a.rating,h.name 'heading_name', h.name_russian 'name_russian',u.username 'username',au.name,au.family,COUNT(ac.id) AS comments FROM articles a JOIN headings h ON a.heading_id = h.id JOIN type_headings th ON h.type_heading = th.id JOIN users u ON a.user_id = u.id JOIN authors au ON a.user_id = au.user_id LEFT JOIN article_comments ac ON a.id = ac.article_id WHERE a.activity = 2 AND a.in_sandbox = 1";
			if($key != $this->settings_model->last_type_heading)
				$sql .= ' UNION ';
		endforeach;
		else:
			$value = array_shift($this->rating_for_show);
//			$sql .= "SELECT articles.header,articles.id,articles.url,articles.is_special,articles.data_published,articles.annotation,articles.rating,headings.name 'heading_name', headings.name_russian 'name_russian', users.username 'username',authors.name,authors.family FROM articles,headings,users,authors WHERE articles.heading_id = headings.id  AND articles.user_id = authors.user_id AND articles.user_id = users.id AND articles.activity = 2 AND articles.in_sandbox = 1";
			$sql .= "SELECT a.header,a.id,a.url,a.is_special,a.data_published,a.annotation,a.rating,h.name 'heading_name', h.name_russian 'name_russian',u.username 'username',au.name,au.family,COUNT(ac.id) AS comments FROM articles a JOIN headings h ON a.heading_id = h.id JOIN users u ON a.user_id = u.id JOIN authors au ON a.user_id = au.user_id LEFT JOIN article_comments ac ON a.id = ac.article_id WHERE a.activity = 2 AND a.in_sandbox = 1";
		endif;
		$sql .= " GROUP BY a.id ORDER BY a.data_published DESC $limit";

		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function get_articles_by_heading_id($heading_id, $limit = '', $offset = ''){
		
		if($limit > -1)
			$limit = 'LIMIT '.$limit.','.$offset;
		$order = 'a.data_published DESC';
		
		$sql = "SELECT a.id,a.header,a.annotation,a.rating,a.url,a.heading_id,a.data_published,u.username 'username',au.name 'name',au.family 'family', COUNT(ac.id) AS comments FROM articles a JOIN users u ON a.user_id = u.id JOIN authors au ON a.user_id = au.user_id LEFT JOIN article_comments ac ON a.id = ac.article_id WHERE a.heading_id = ".$heading_id.' AND a.activity = 2  '." GROUP BY a.id ORDER BY $order ".$limit." ";
		$query = $this->db->query($sql);
		return $query->result_array();
		
	}
	
	function get_articles_by_heading_id_for_adverts($heading_id,$advert_id){
		
		$sql = "SELECT a.id, a.header, a.url, aa.article_id AS checked_id FROM articles a LEFT JOIN advert_articles aa ON (a.id = aa.article_id AND aa.advert_id = $advert_id) WHERE a.heading_id = ".$heading_id.' AND a.activity = 2  '." ORDER BY a.header ";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function get_articles_by_advert_id($advert_id,$heading_id = 0,$limit = '', $offset = ''){
		
		if($limit > -1)
			$limit = 'LIMIT '.$limit.','.$offset;
		$sql = "SELECT a.id, a.header, a.url, aa.article_id AS checked_id FROM articles a LEFT JOIN advert_articles aa ON (a.id = aa.article_id AND aa.advert_id = $advert_id) WHERE a.id IN (SELECT article_id  FROM advert_articles WHERE advert_id = $advert_id) AND a.activity = 2  ORDER BY a.header $limit";
//		echo $sql;exit;
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	function get_num_articles_by_advert_id($advert_id,$heading_id = 0){
		
		if($heading_id)
			$sql = "SELECT COUNT(*) AS num FROM articles a JOIN advert_articles aa ON (a.id = aa.article_id AND aa.advert_id = $advert_id) WHERE a.activity = 2 AND a.heading_id = $heading_id";	
		else
			$sql = "SELECT COUNT(*) AS num FROM articles a JOIN advert_articles aa ON (a.id = aa.article_id AND aa.advert_id = $advert_id) WHERE a.activity = 2";		
		$query = $this->db->query($sql);
		return $query->row()->num;
	}
	
	function get_uri_info_in_advert_articles($uriSegments){
		if(count($uriSegments) == 4){
			$type = 'all';
			$cur_page = 0;
			$heading_id = 0;

		}
		elseif(count($uriSegments) == 5 && is_numeric($uriSegments[5])){
			$type = 'all';
			$cur_page = $uriSegments[5];
			$heading_id = 0;
		}
		
		elseif(count($uriSegments) == 6 && is_numeric($uriSegments[6])){
			$type = 'heading';
			$cur_page = 0;
			$heading_id = $uriSegments[6];
		}
		elseif(count($uriSegments) == 7 && is_numeric($uriSegments[7])){
			$type = 'heading';
			$cur_page = $uriSegments[7];
			$heading_id = $uriSegments[6];

		}
		elseif(count($uriSegments) == 7 && !is_numeric($uriSegments[7])){
			$type = 'heading';
			$type_heading = $uriSegments[7];
			$cur_page = 0;
			$heading_id = $uriSegments[6];

		}
		elseif(count($uriSegments) == 8 && is_numeric($uriSegments[8])){
			$type = 'heading';
			$type_heading = $uriSegments[7];
			$cur_page = $uriSegments[8];
			$heading_id = $uriSegments[6];

		}
		if(empty($type))
			$type = 'all';
			
		if(empty($cur_page))
			$cur_page = 0;
		$array = array('type' => $type, 'cur_page' => $cur_page, 'heading_id' => $heading_id);
		return $array;
		
	}
	
	
	function join_articles_to_advert($advert_id,$checked,$unchecked){
		$last_symbol = substr($checked,-1);
		if($last_symbol == ','){
			$checked = substr($checked,0,-1);
		}
		$last_symbol = substr($unchecked,-1);
		if($last_symbol == ','){
			$unchecked = substr($unchecked,0,-1);
		}
		$buffer_unchecked = explode(',',$unchecked);
		$buffer_checked = explode(',',$checked);
		if(count($buffer_unchecked)){
			$sql = "DELETE FROM advert_articles WHERE advert_id = $advert_id AND article_id IN ($unchecked)";
			$res = $this->db->query($sql);
		}
		
		if(count($buffer_checked)){
			foreach($buffer_checked as $id){
				$data = array(
				'id' => null,
				'advert_id' => $advert_id,
				'article_id' => $id
				);
				$res = $this->db->insert('advert_articles',$data);
			}
		}
		return $res;
	}
	
	function join_heading_to_advert($advert_id,$heading_id){
		if(!$heading_id)
			return false;
		$res = $this->db->delete('advert_headings', array('advert_id' => $advert_id));
		if($res){
			$data = array(
			'id' => null,
			'advert_id' => $advert_id,
			'heading_id' => $heading_id
			);
			$res_insert = $this->db->insert('advert_headings',$data);
			return $res_insert;
		}
		return $res;
	}
	
	function get_advert_by_id($id){
		
		$this->db->select('*');
		$query = $this->db->get_where('advert_blocks',array('id' => $id));
		$result = $query->row_array();
		if($result['type'] == 'over_content')
			$result['type_russian'] = 'Над контентом';
		elseif($result['type'] == 'under_content')
			$result['type_russian'] = 'Под контентом';
		elseif($result['type'] == 'under_image')
			$result['type_russian'] = 'Под изображением';
				
		return $result;
	
	}
	
	function get_default_advert_by_id($id){
		
		$this->db->select('*');
		$query = $this->db->get_where('advert_default_blocks',array('id' => $id));
		$result = $query->row_array();
				
		return $result;
	}
	
	function get_articles_by_user($user_id){
		
		$this->db->select('id');
		$query = $this->db->get_where('articles',array('user_id' => $user_id));
		return $query->result_array();
	}
	
	function get_heading_by_id($id){
		
		$query = $this->db->get_where('headings',array('id' => $id));
		return $query->row();
	}
	
	function get_heading_by_name($name){
		
		$query = $this->db->get_where('headings',array('name' => $name));
		return $query->row();
	}
	
	function get_article_content_by_id($id){
		
		$this->db->select('content');
		$this->db->from('articles');
		$this->db->where('id',$id);
		$query = $this->db->get();
		return $query->row()->content;
	}
    
	
	function get_article_by_parameters($select = '*', $where){
		
		$where = 'AND '.$where;
		
		if(strpos($where, 'activity = 2'))
			$order = 'articles.data_published DESC';
		elseif(strpos($where, 'activity = 0'))
			$order = 'articles.data_saved DESC';
		elseif(strpos($where, 'activity = 1'))
			$order = 'articles.data_moderated ASC';
		else
			$order = 'articles.header';
		
		$sql = "SELECT articles.$select, users.username 'username', headings.name 'heading_name', headings.name_russian 'heading_name_russian' FROM articles,headings,users WHERE headings.id = articles.heading_id AND users.id = articles.user_id  $where ORDER BY $order ";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	function get_article_rating($article_id){
		
		$this->db->select('rating');
		$this->db->from('articles');
		$this->db->where('id',$article_id);
		$query = $this->db->get();
		return $query->row()->rating;
	}
	
	function get_article_by_id($id,$select = '*',$where=''){
		
		if(strpos($where, 'activity = 2'))
			$order = 'articles.data_published DESC';
		elseif(strpos($where, 'activity = 0'))
			$order = 'articles.data_saved DESC';
		elseif(strpos($where, 'activity = 1'))
			$order = 'articles.data_moderated ASC';
		else
			$order = 'articles.header';
			$extract = '';
		if($select != '*'){
			if(strpos($select, ',')):
			$buffer = explode(',',$select);
			for($i=0;$i<count($buffer);$i++){
				if($i == count($buffer)-1)
					$extract .= "articles.$buffer[$i]";
				else
					$extract .= "articles.$buffer[$i],";
			}
			else:
				$extract = 'articles.'.$select;
			endif;
		}
		else{
			$extract = 'articles.*';
		}
		
		$sql = "SELECT $extract, users.username 'username', headings.name 'heading_name', headings.name_russian 'heading_name_russian', headings.type_heading FROM articles,headings,users WHERE headings.id = articles.heading_id AND users.id = articles.user_id AND articles.id =".$id." $where ORDER BY $order ";
		$query = $this->db->query($sql);
		return $query->row();
	}
	
	private function validate_publication($heading, $update = null){
		
		$url = $this->input->post('url');
		
		if($this->input->post('headings') == 0){
			$message['errors']['headings'] = 'Не выбрана рубрика!';
		}
		
		if($this->input->post('header') == ''){
			$message['errors']['header'] = 'Поле "Заголовок" не должно быть пустым!';
			
		}
		
		if($url == ''){
			$message['errors']['url_empty'] = 'Поле "Url" не должно быть пустым!';
		}
		
		if(strpos('/',$url) != false || strpos('.',$url) != false || strpos(':',$url) != false){
			$message['errors']['url_wrong'] = 'В поле "Url" присутствуют недопустимые символы!';
		}
		
		if($update == null):
		$inserted_url = '/'.$heading->name.'/'.$url.'.html';
		
		$query = $this->db->get_where('articles',array('url' => $inserted_url));
		if(count($query->result_array())>0){
			$message['errors']['url_exists'] = 'Публикация с данным url уже существует!';
		}
		
		$query = $this->db->get_where('articles',array('url' => $inserted_url));
		if(count($query->result_array())>0){
			$message['errors']['url_exists'] = 'Публикация с данным url уже существует!';
		}
		
		endif;
		
		
		return $message;
	}
	
	function get_message_error(){
		return $this->message_error;
	}
	
	function get_branch($refer_id){
		
		$sql = "SELECT u.username,ur.id,ur.data FROM user_refers ur JOIN users u ON u.id = ur.user_id WHERE ur.refer_id = $refer_id";
		$res = $this->db->query($sql);
		$html = '';
		if(!$res)
			return false;
		if($res->num_rows() > 0){
			$counter = 0;
			$num_rows = count($res->result_array());
			foreach($res->result_array() as $elem){
				if($counter == $num_rows - 1)
					$html .= "<li class=\"last\"><span class=\"file\"><a href=\"/avtory/".$elem['username']."\" target=\"_blank\">".ucfirst($elem['username']).'</a></span><span style="padding-left:15px;">Дата регистрации: '.time_change_show_data($elem['data']).'</span></li>';
				else
					$html .= "<li><span class=\"file\"><a href=\"/avtory/".$elem['username']."\" target=\"_blank\">".ucfirst($elem['username']).'</a></span><span style="padding-left:15px;">Дата регистрации: '.time_change_show_data($elem['data']).'</span></li>';
				$counter++;
			}
			return $html;
		}
		return false;
		
		}
	
}

?>