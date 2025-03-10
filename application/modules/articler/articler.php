<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start();
//define(THEME_NAME,'articler');
define(THEME_NAME,'ruautor');

class Articler extends MY_Controller{
	
	public $layout = 'main';
	public $THEME = 'templates/ruautor';
	public $path_avatars = '/uploads/images/avatars/';
	public $theme_name = 'ruautor';
	protected $user_finance;
	protected $access = false;
	protected $moderator = false;
	protected $editor = false;
	protected $login;
	protected $per_page = 5;
	protected $per_page_comment = 4;
	protected $num_last_articles = 4;
	protected $max_file_size;
	protected $max_height = 40;
	protected $max_width = 54;
	protected $length_last_comment = 50;
	protected $user_author_rating;
	protected $user_rating_activity;	
	protected $operation;
	protected $author_group;
	protected $rating_for_publish = 0;
	protected $user_id;
    protected $site_name;
    protected $site_title;
	protected $related_controllers = array('moderator','comment','publish','list_comments','raw','moderate','avtor','private','author');
	protected $settings;
	protected $site_description;
	protected $site_keywords;
	
	function __construct(){
		
		parent::__construct();
        $this->load->library('DX_Auth');
		$this->load->library('Email');
		$this->load->library('pagination');
		$this->load->library('template');
		$this->load->helper('form');
		$this->load->helper('time');
		$this->load->helper('image');
		$this->load->helper('my_string');
		$this->load->model('articler/statistic_model');
		$this->load->model('articler/articler_model');
		$this->settings = $this->cms_base->get_settings();
		$this->site_name = $this->settings['site_short_title'];
		$this->site_title = $this->settings['site_title'];
		$this->THEME = 'templates/'.$this->settings['site_template'];
		$this->theme_name = $this->settings['site_template'];
		$this->template->assign('THEME',site_url('').$this->THEME);
		$this->template->assign('is_articler',1);
		$this->load->module('comments/comments');
		$this->comments->autoload();
		$this->init();
		$this->lang->load('articler',$this->main_language);
		
		if(in_array($this->controller,$this->related_controllers))
			$this->controller = 'articler';
		
		$this->beforeFilter();
	}
	
	protected function set_environment_info(){
		
		$this->max_file_size = ini_get('post_max_size');
		$this->max_file_size = str_replace('M','Мб',$this->max_file_size);
		ini_set('zlib.output_compression', 'On');
		ini_set('zlib.output_compression_level', '1');
		mb_internal_encoding("UTF-8");
	}
	
	protected function js_cache(){
		$check_file = $_SERVER['DOCUMENT_ROOT'].'/templates/'.$this->theme_name.'/js/data.js';
		$period = 3600 * 24;
		if(!file_exists($check_file) || (file_exists($check_file) && (time() - $period) > filemtime($check_file))){
			$fh = fopen($check_file,'w');
			$js = $this->articler_model->get_users_and_authors_js();
			fwrite($fh,$js);
			fclose($fh);
			return true;
		}		
		return false;
	}
	
	protected function init(){
		
		parent::init();
		$this->per_page_comment = $this->settings_model->get_setting('comments_system', 'per_page_comments', 'property');
		$this->per_page = $this->settings_model->get_setting('interface_settings', 'per_page_elements', 'property');
	}
	
	protected function beforeFilter(){

		$this->template->assign('main_headings',$this->main_headings);
		$this->template->assign('add_headings',$this->add_headings);
		$this->template->assign('site_name', str_replace('www.','',$this->site_name));
		$uriSegments = $this->uri->segment_array();
		
		if($this->controller == 'articler' && ($this->action == 'ajaxupload' || $this->action == 'ckupload' ))
			$_POST['type'] = 'ajax';
			
		if(empty($_POST['type'])){
			$this->template->assign('sandbox', $this->get_sandbox_info());
			$this->template->assign('last_comments', $this->get_last_comments_info());
			$this->template->assign('top_authors',$this->get_top_authors());
		}

		$this->set_environment_info();
		if($this->dx_auth->is_logged_in() && $this->dx_auth->get_role_name() == 'user'){
			
			$this->moderator = false;	
			$this->access = true;
			$this->template->assign('access',1);
			$this->login = $this->dx_auth->get_username();
			$this->user_id = $this->dx_auth->get_user_id();
			$this->user_finance = $this->articler_model->get_user_finance($this->user_id);
			if(isset($_SESSION['private_url'])){
				$buffer = $_SESSION['private_url'];
				unset($_SESSION['private_url']);
				redirect($buffer);
			}
			
			if($_POST['type'] == 'ajax')
				return true;
			if($this->user_finance->purse != null)
				$this->template->assign('have_purse', 1);
			else
				$this->template->assign('have_purse', 0);
			$key = $this->login.'_'.$this->user_id.'_key';
			$_SESSION[$key] = $this->articler_users->get_password($this->user_id);
			$this->user_author_rating = $this->articler_model->get_rating($this->user_id);
			$this->user_rating_activity = $this->articler_model->get_rating($this->user_id, 'activity');
			if(!$this->user_rating_activity)
				$this->user_rating_activity = '0';
			$this->template->assign('login',$this->login);
			$num_articles = $this->articler_model->get_num_articles_by_user_id($this->user_id);
			$this->author_group = $this->articler_model->get_author_group($this->user_author_rating, $num_articles);
			if($this->author_group)
				$this->template->assign('author_group',$this->author_group);
			$this->template->assign('num_articles',$num_articles);
			$this->template->assign('num_articles_rough',$this->articler_model->get_num_articles_by_user_id($this->user_id,'0'));
			$this->template->assign('num_articles_rejected',$this->articler_model->get_num_articles_by_user_id($this->user_id,'-1'));
			$this->template->assign('num_moderate_articles',$this->articler_model->get_num_articles_by_user_id($this->user_id,'1'));
			$this->template->assign('num_refers',$this->articler_model->get_num_refers_by_user_id($this->user_id));
			$this->template->assign('num_comments',$this->articler_model->get_num_comments_by_user_id($this->user_id));
			$this->template->assign('user_rating', $this->user_author_rating);
			$this->template->assign('user_rating_activity', $this->user_rating_activity);
			$this->template->assign('num_visites_avg',$this->statistic_model->get_visites_by_user_id($this->user_id,'sum',false));
			$this->template->assign('num_visites_all',$this->statistic_model->get_visites_by_user_id($this->user_id,'sum'));
//			$this->template->assign('num_payments_daily',$this->articler_model->get_payments_daily($this->user_id));
			$this->template->assign('num_payments_weekly',$this->articler_model->get_payments_weekly($this->user_id));
			$this->template->assign('num_payments_all',$this->articler_model->get_payments_all($this->user_id));
			
			if($this->dx_auth->get_role_name() == 'user' && empty($_SESSION['first_enter']) && $this->articler_model->modal_box_for_user){
				$this->template->assign('first_enter', 1);
				$_SESSION['first_enter'] = 1;
		}
			
			if($_POST['type'] == 'ajax'){
				
			}
			else{
				$this->template->assign('modal_windows',$this->get_modal_windows());

			}
 			$this->set_avatar_src($this->login);
 			$this->template->assign('is_moderator',0);

		}
		elseif($this->dx_auth->is_logged_in() && ($this->dx_auth->get_role_name() == 'moderator' || $this->dx_auth->get_role_name() == 'editor')){
			$this->moderator = true;
			if($this->dx_auth->get_role_name() == 'editor')
				$this->editor = true;
			if($this->editor)
				$this->template->assign('is_editor',1);
								
			if($_POST['type'] == 'ajax')
				return true;
				
			$this->template->assign('login',$this->dx_auth->get_role_name());
			$key = $this->dx_auth->get_username().'_'.$this->dx_auth->get_user_id().'_key';
			$_SESSION[$key] = $this->articler_users->get_password($this->dx_auth->get_user_id());

			$this->template->assign('access',2);
			$this->template->assign('num_moderate_articles',$this->articler_model->count_moderate_articles());
			if(!$this->editor){
				$this->template->assign('num_unconsidered_pleas',$this->articler_model->num_unconsidered_pleas());
				$this->template->assign('num_unclosed_payouts', $this->articler_model->num_opened_payouts());
			}
			if($this->editor)	{
				$this->template->assign('profile',lang('cabinet_editor'));
			}		
			else{
				$this->template->assign('profile',lang('cabinet_moderator'));
				if($_POST['type'] == 'ajax'){			
				}
				else{
				$this->template->assign('modal_windows',$this->get_modal_windows());

				}
			}
			
		$this->js_cache();
		$num_articles = $this->articler_model->get_all_num_articles();
		$this->template->assign('num_articles',$num_articles);
		$this->template->assign('num_articles_rough',$this->articler_model->get_all_num_articles('0'));
		$this->template->assign('num_articles_rejected',$this->articler_model->get_all_num_articles('-1'));
		$this->template->assign('num_moderate_articles',$this->articler_model->get_all_num_articles('1'));
		$this->template->assign('num_published_articles',$this->articler_model->get_all_num_articles('2'));
		$this->template->assign('num_visites_avg',$this->statistic_model->average_num_visites());
		$average_num_visites_correct = $this->statistic_model->average_num_visites_correct();
		$this->template->assign('num_visites_avg_correct',$average_num_visites_correct);
		$this->template->assign('num_visites_all',$this->statistic_model->all_num_visites());
		$this->template->assign('num_visites_all_correct',$this->statistic_model->all_num_visites_correct());
				
		}
		else{
			$this->template->assign('access',0);
			if(empty($_COOKIE['first_enter_guest']) && $this->articler_model->modal_box_for_guest){
				$this->template->assign('first_enter_guest', 1);
				$this->template->assign('modal_windows',$this->get_modal_windows_guest());
				setcookie('first_enter_guest',1);
		}

		}
		if($this->access || $this->moderator)
			$this->load->library('ckeditor');
		
			
		if($this->moderator)
			$is_moderator = 1;
		else
			$is_moderator = 0;
		
		$this->template->assign('is_moderator',$is_moderator);
							
	}
	
	protected function get_banners(){
		
		return '<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
	}
	
	protected function set_avatar_src($login, $return = false){
	
		$avatar = $this->articler_model->get_avatar($this->user_id);
		if($avatar)
			$src_id = site_url('uploads/images/avatars').'/'.$this->user_id.'.'.$avatar->ext;
		
		if($avatar && file_exists($_SERVER['DOCUMENT_ROOT'].'/uploads/images/avatars/'.$this->user_id.'.'.$avatar->ext)){
			$avatar_sizes['height'] = $avatar->small_height;
			$avatar_sizes['width'] = $avatar->small_width;
			$avatar_src = site_url('').$this->path_avatars.$this->user_id.'.'.$avatar->ext.'?'.time();	

		}
		else{
			$src_id = site_url('uploads/images/avatars').'/'.$this->login.'.'.$avatar->ext.'?'.time();

		}
		if(empty($avatar_src))
			$avatar_src = $src_id;
		if(!$return){
			$this->template->assign('avatar_src',$avatar_src);
			$this->template->assign('avatar_sizes', $avatar_sizes);
		}
		else{
			$result['avatar_src'] = $avatar_src;
			$result['avatar_sizes'] = $avatar_sizes;
			return $result;
		}

	}
	
	protected function form_search_articles($cur_page,$is_moderator=true){
	
		
		if($cur_page == 0){
			$page = '';
		}
		else{
			$page = '/'.$cur_page;
		}
		$headings[0] = lang('all_search_articles');

		foreach($this->all_headings as $heading){
			$headings[$heading['id']] = $heading['name_russian'];
		}
		if(isset($_REQUEST['headings']))
			$selected = $_REQUEST['headings'];
		else
			$selected = 0;
		$select_headings = form_dropdown('headings',$headings,$selected,'id="headings" style="background: white; color: black;"');
		
		if(isset($_REQUEST['data1']))
			$data1 = $_REQUEST['data1'];
		else
			$data1 = '00.00.0000';
			
		if(isset($_REQUEST['data2']))
			$data2 = $_REQUEST['data2'];
		else
			$data2 = '00.00.0000';
			
		if(isset($_REQUEST['rating_from']))
			$rating_from = $_REQUEST['rating_from'];
		else
			$rating_from = '';
			
		if(isset($_REQUEST['rating_to']))
			$rating_to = $_REQUEST['rating_to'];
		else
			$rating_to = '';
			
		if(isset($_REQUEST['is_special']))
			$is_special = 'checked="checked"';
		else
			$is_special = '';
			
		if(isset($_REQUEST['outer_links']))
			$outer_link = 'checked="checked"';
		else
			$outer_link = '';
			
		if(isset($_REQUEST['header']))
			$header = $_REQUEST['header'];
		else
			$header = '';
			
		if(isset($_REQUEST['article']))
			$article = $_REQUEST['article'];
		else
			$article = '';
			
		if(isset($_REQUEST['author']))
			$author = $_REQUEST['author'];
		else
			$author = '';
			
		if($is_moderator)
			$action = 'moderator/public_articles';
		else
			$action = 'search';

		$data = array(
		'action' => site_url($action),
		'headings' => $select_headings,
		'data1' => $data1,
		'data2' => $data2,
		'rating_from' => $rating_from,
		'rating_to' => $rating_to,
		'is_special' => $is_special,
		'outer_link' => $outer_link,
		'header' => $header,
		'article' => $article,
		'author' => $author,
		'is_moderator' => $is_moderator,
		'main_language' => $this->main_language
		);
		return $this->template->view($this->theme_name.'/forms/search_articles.tpl',$data,true);

	}
	
	public function search(){
		
		$uriSegments = $this->uri->segment_array();
		if(count($uriSegments) == 2)
			$cur_page = $uriSegments[2];
		else
			$cur_page = 0;
		$this->template->assign('site_title',lang('site_search'));
		$this->site_title = lang('site_search');
		$activity = 2;
		if(isset($_REQUEST['search'])){
			$articles = $this->articler_model->all_list_articles($activity, "$cur_page,$this->per_page");
			$config['base_url'] = site_url('search');	
			$config['total_rows'] = $this->articler_model->all_list_articles($activity, "$cur_page,$this->per_page",true);
			$config['per_page'] = $this->per_page;
			$config['use_page_numbers'] = TRUE;
			$config['uri_segment'] = 3;
			$this->pagination->initialize($config);
			$paginator = $this->pagination->create_links();
		}
		else{
			$articles = array();
			$paginator = '';
		}
		
		if($activity == 2)
			$frm_search = $this->form_search_articles($cur_page,false);
		else
			$frm_search = '';
		
		$data = array(
		'articles' => $articles,
		'message' => $message,
		'THEME' => $this->THEME,
		'is_search' => 1,
		'activity' => $activity,
		'paginator' => $paginator,
		'frm_search' => $frm_search,
		'main_language' => $this->main_language
		);
		
		if($this->editor)
			$data['is_editor'] = 1;
			
	 	$content = $this->template->view($this->theme_name.'/list_articles.tpl',$data,true);
		$this->display_layout($content);

	}
	
	protected function moderator_list_articles($activity){
	
		$uriSegments = $this->uri->segment_array();
		if(count($uriSegments) == 3)
			$cur_page = $uriSegments[3];
		else
			$cur_page = 0;

		if($activity == 2){
			$this->template->assign('site_title',lang('list_of_published_materials'));
			$this->site_title = lang('List_of_published_materials');
		}
		elseif($activity == 1){
			$this->template->assign('site_title',lang('list_of_materials_for_moderation'));
			$this->site_title = lang('List_of_materials_for_moderation');
		}
			
		$this->template->assign('activity',$activity);
		$this->template->assign('is_moderator',1);
		
		$articles = $this->articler_model->all_list_articles($activity, "$cur_page,$this->per_page");
		foreach($articles as $key=>$value){
			$articles[$key]['adverts'] = $this->articler_model->get_article_adverts($value['id']);
			
		}
		if($activity == 2)
			$config['base_url'] = site_url('moderator/public_articles');
		else
			$config['base_url'] = site_url('moderator/moderate_articles');
			
		$config['total_rows'] = $this->articler_model->all_list_articles($activity, "$cur_page,$this->per_page",true);
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();
		if($activity == 2)
			$frm_search = $this->form_search_articles($cur_page);
		else
			$frm_search = '';
		
		$data = array(
		'articles' => $articles,
		'message' => $message,
		'THEME' => $this->THEME,
		'is_moderator' => 1,
		'activity' => $activity,
		'paginator' => $paginator,
		'frm_search' => $frm_search,
		'main_language' => $this->main_language
		);
		
		if($this->editor)
			$data['is_editor'] = 1;
			
		return $this->template->view($this->theme_name.'/list_articles.tpl',$data,true);

	}
	
	public function order_payout(){
		
		if($this->access){
			$pay = $this->articler_model->get_available_for_pay($this->user_id);
			
			
			if($pay > 0 && $this->user_finance->purse != null){
				$res = $this->articler_model->add_payout($this->user_id, $pay);
				if($res)
					$response['content'] = lang('payout_successfully_ordered');
				else
					$response['content'] = lang('unknown_error');
			}
			elseif($pay > 0 && $this->user_finance->purse == null){
				$response['content'] = lang('not_assigned_price');
			}
			else{
				
				$response['content'] = lang('no_payments_available');			
			}
			
		}
		else{
					
			$response['content'] = lang('access_only_for_registered_users');
				
		}
		
			echo json_encode($response);

	}
	
	public function author_profile(){
		
		if($this->access){
		
			if(isset($_POST['update'])){
				$res = $this->articler_model->change_author_profile($this->user_id);

			}
			$settings_by_author = lang('settings_by_author');
			$settings_by_author = str_replace('%login%',ucfirst($this->login),$settings_by_author);
			$this->template->assign('site_title', $settings_by_author);
			$profile = $this->articler_model->get_author_profile($this->user_id);
			$data = array(
			'name' => $profile->name,
			'family' => $profile->family,
			);
			if($res)
				$data['updated'] = 1;
				
			$uriSegments = $this->uri->segment_array();
			if(count($uriSegments) == 3 && $uriSegments[3] == 'set_name')
				$data['set_name'] = 1;
			$content = $this->template->view($this->theme_name.'/author_profile.tpl',$data,true);

		}
		else{
			$data = array(
			'message' => lang('access_only_for_registered_users')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		$this->display_layout($content);
	}
	
	public function author_refers(){
		
		if($this->access){
			$referrals = lang('referrals_for_login');
			$referrals = str_replace('%login%',ucfirst($this->login),$referrals);
			$this->template->assign('site_title', $referrals);
			$data = array(
			'refers' => $this->articler_model->get_refers_by_user_id($this->user_id),
			'login' => ucfirst($this->login),
			'user_id' => $this->user_id,
			'main_language' => $this->main_language
			);
			$days_refer = $this->articler_model->get_refer_period_by_user_id($this->user_id);
			if($days_refer)
				$data['days_refer'] = $days_refer;
			$content = $this->template->view($this->theme_name.'/author_refers.tpl',$data,true);

		}
		else{
			$data = array(
			'message' => lang('access_only_for_registered_users')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		$this->display_layout($content);
	}
	
	public function author_finance(){
		
		if($this->access){
		
			$finance_for_login = lang('finance_for_login');
			$finance_for_login = str_replace('%login%',ucfirst($this->login),$finance_for_login);
			$this->template->assign('site_title', $finance_for_login);
			$data = array(
			'all_earn' => $this->articler_model->get_all_earn($this->user_id),
			'all_paid' => $this->articler_model->get_all_paid($this->user_id),
			'remains' => $this->articler_model->get_balance($this->user_id),
			'lowest_sum_for_pay' => $this->articler_model->lowest_sum_for_pay,
			'available_for_pay' => $this->articler_model->get_available_for_pay($this->user_id),
			'login' => ucfirst($this->login),
			'user_id' => $this->user_id,
			'main_language' => $this->main_language
			);
			$content = $this->template->view($this->theme_name.'/author_finance.tpl',$data,true);

		}
		else{
			$data = array(
			'message' => lang('access_only_for_registered_users')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		$this->display_layout($content);
	}
	
	
	
	public function change_purse(){
		
		if($this->access){
			$res = $this->articler_model->change_purse($this->user_id, $this->user_finance);
			if($res){
				$response['answer'] = 1;
			}
			else{
				$response['answer'] = 0;
				$response['content'] = lang('unknown_error');
			}
		}
		else{
			$response['content'] = lang('access_to_the_operation_is_prohibited');
		}
		
		echo json_encode($response);
	}
	
	public function change_password(){
		
		if($this->access && $this->input->post('type') == 'ajax'){
			
		
			if($this->articler_users->check_password($this->dx_auth->_encode($this->input->post('old_password')),$this->user_id)){
	
					$res = $this->users->change_password($this->user_id, crypt($this->dx_auth->_encode($this->input->post('new_password'))));
					if($res){
						$response['answer'] = 1;
						$response['content'] = lang('password_successfully_changed');
					}
					else{
						$response['answer'] = 0;
						$response['content'] = lang('unknown_error');
					}
				
			}
			else{
				$response['answer'] = 0;
				$response['content'] = lang('current_password_is_incorrect');
			}
				echo json_encode($response);

		}
		
	}
	
	public function change_email(){
		
		if($this->access && $this->input->post('type') == 'ajax'){
	
			if(!$this->dx_auth->is_email_available($this->input->post('old_email'))){
				
				if(!$this->dx_auth->is_email_available($this->input->post('new_email'))){
					$response['answer'] = 0;
					$response['content'] = lang('this_email_is_already_reserved_for_another_user');
				}
				else{
					$res = $this->articler_users->change_email($this->user_id, $this->input->post('new_email'));
					if($res){
						$response['answer'] = 1;
						$response['content'] = lang('email_successfully_changed');
					}
					else{
						$response['answer'] = 0;
						$response['content'] = lang('unknown_error');
					}
				}

				
			}
			else{
				$response['answer'] = 0;
				$response['content'] = lang('current_email_is_incorrect');
			}
		}
		
		echo json_encode($response);
	}
	
	protected function get_modal_windows_guest(){
		
		if($this->controller == 'articler'){
			$windows['first_enter_info_guest'] = $this->template->view($this->theme_name.'/widgets/first_enter_info_guest.tpl',array(),true);
		}
		else{
			$windows['first_enter_info_guest'] = $this->template->view('/widgets/first_enter_info_guest.tpl',array(),true);

		}
		return $windows;
	}
	
	protected function get_modal_windows(){
		
		$uriSegments = $this->uri->segment_array();
		
		$purse_data = array(
		'purse' => $this->user_finance->purse
		);
		if($this->user_finance->purse != null)
				$purse_data['have_purse'] = 1;
			else
				$purse_data['have_purse'] = 0;
		
		if($this->controller == 'articler'){
			$windows['change_email'] = $this->template->view($this->theme_name.'/widgets/change_email.tpl',array('current_email' => $this->articler_users->get_email_by_user_id($this->user_id)),true);
			$windows['change_password'] = $this->template->view($this->theme_name.'/widgets/change_password.tpl',array(),true);
			$windows['change_purse'] = $this->template->view($this->theme_name.'/widgets/change_purse.tpl',$purse_data,true);
			$windows['reply_comment'] = $this->template->view($this->theme_name.'/widgets/reply_comment.tpl',array(),true);
			$windows['add_plea'] = $this->template->view($this->theme_name.'/widgets/add_plea.tpl',array(),true);
			$windows['reply_plea'] = $this->template->view($this->theme_name.'/widgets/reply_plea.tpl',array(),true);
			$windows['add_outer_link'] = $this->template->view($this->theme_name.'/widgets/add_outer_link.tpl',array(),true);
			$windows['first_enter_info'] = $this->template->view($this->theme_name.'/widgets/first_enter_info.tpl',array('rating_activity' => $this->user_rating_activity),true);
			
		}
		else{
			$windows['change_email'] = $this->template->view('/widgets/change_email.tpl',array('current_email' => $this->articler_users->get_email_by_user_id($this->user_id)),true);
			$windows['change_password'] = $this->template->view('/widgets/change_password.tpl',array(),true);
			$windows['change_purse'] = $this->template->view('/widgets/change_purse.tpl',$purse_data,true);
			$windows['reply_comment'] = $this->template->view('/widgets/reply_comment.tpl',array(),true);
			$windows['add_plea'] = $this->template->view('/widgets/add_plea.tpl',array(),true);
			$windows['add_outer_link'] = $this->template->view('/widgets/add_outer_link.tpl',array(),true);
			$windows['reply_plea'] = $this->template->view('/widgets/reply_plea.tpl',array(),true);
			$windows['first_enter_info'] = $this->template->view('/widgets/first_enter_info.tpl',array('rating_activity' => $this->user_rating_activity),true);
		}
		return $windows;
	}
	
	protected function get_top_authors(){
		
		$authors = $this->articler_model->top_authors();

		if(count($authors) < 1)
			return '';
		$data = array(
		'authors' => $authors,
		'main_language' => $this->main_language
		);
		if($this->controller == 'articler'){	
			$content = $this->template->view($this->theme_name.'/widgets/top_authors.tpl',$data,true);	
		}
		
		else{
			$content = $this->template->view('/widgets/top_authors.tpl',$data,true);	

		}
		
		return $content;
	}
	
	protected function get_sandbox_info(){
		
		$articles = $this->articler_model->last_articles_in_sandbox();
			$data = array(
			'articles' => $articles,
			'main_language' => $this->main_language
			);
		if($this->controller == 'articler'){	
			$content = $this->template->view($this->theme_name.'/widgets/sandbox.tpl',$data,true);	
		}
		
		else{
			$content = $this->template->view('/widgets/sandbox.tpl',$data,true);	

		}
		
		return $content;
	}
	
	public function rules(){
		
		$this->template->assign('site_title',lang('about_service'));
		$this->site_title = lang('about_service');
		$content = $this->template->view($this->theme_name.'/rules.tpl',array(),true);	
		$this->display_layout($content);
	}
	
	public function enter(){
		
		$this->template->assign('site_title',lang('registration_in_system'));
		$this->site_title = lang('registration_in_system');
		$content = $this->template->view($this->theme_name.'/enter.tpl',array(),true);	
		$this->display_layout($content);
	}
	
	public function contact(){
		
		$this->template->assign('site_title',lang('articler_feedback'));
		$this->site_title = lang('articler_feedback');
		$content = $this->template->view($this->theme_name.'/contact.tpl',array(),true);	
		$this->display_layout($content);
	}
	
	protected function get_last_comments_info(){
		
		$comments = $this->articler_model->last_comments();
			$data = array(
			'comments' => $comments,
			'length_last_comment' => $this->length_last_comment
			);
		if($this->controller == 'articler'){	
			$content = $this->template->view($this->theme_name.'/widgets/last_comments.tpl',$data,true);	
		}
		
		else{
			$content = $this->template->view('/widgets/last_comments.tpl',$data,true);	

		}
		
		return $content;
	}
	
	public function make_payout(){
		
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[3];
		
		if($this->moderator && !$this->editor){
			
			$res = $this->articler_model->make_payout($id);
			if($res){
				$response['answer'] = 1;
				$response['num_payouts'] = $this->articler_model->num_opened_payouts();
			}
			else{
				$response['answer'] = 0;
				$response['content'] = lang('error_payment_execution');
			}
		}
		else{
			$response['content'] = lang('this_action_allowed_only_moderator');

		}
		
		echo json_encode($response);
	}
	
	
	public function refer_exception(){
		
		
		if($this->moderator && !$this->editor){
			
		$this->site_title = lang('add_an_exception');
		$this->template->assign('site_title', $this->site_title);

		$uriSegments = $this->uri->segment_array();
		if($uriSegments[3] == 'add'){
			
			$data = array();
			
			if(isset($_POST['submit'])){
				$insert = $this->articler_model->add_refer_exception();
				if($insert)
					redirect(site_url('moderator/refer_settings'));
				else
					$data['message'] = lang('exception_already_exists');
			}
						
			$content = $this->template->view($this->theme_name.'/refer_exception_add.tpl',$data,true);
			
		}
		elseif($uriSegments[3] == 'delete'){
			
			$res = $this->articler_model->delete_refer_exception($uriSegments[4]);
			if($res)
				$response['answer'] = 1;
			else
				$response['answer'] = 0;
			echo json_encode($response);
			exit;
		}
			
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		$this->display_layout($content);

	}
	
	public function refer_settings(){
		
		if($this->moderator && !$this->editor){
			$uriSegments = $this->uri->segment_array();
			if(isset($uriSegments[3]) && $uriSegments[3] == 'add_refer'){
				$add_refer = (int)$_REQUEST['add_refer'];
				$add_user = (int)$_REQUEST['add_user'];
				$add_user_refer = (int)$_REQUEST['add_user_refer'];
				if($add_refer){
					$sql = "UPDATE 	articler_settings SET value = '$add_refer' WHERE type = 'refer_system' AND `key` = 'add'";
					$res = $this->db->query($sql);
					
					$sql = "UPDATE 	articler_settings SET value = '$add_user' WHERE type = 'refer_system' AND `key` = 'add_user'";
					$res = $this->db->query($sql);
					
					$sql = "UPDATE 	articler_settings SET value = '$add_user_refer' WHERE type = 'refer_system' AND `key` = 'add_refer'";
					$res = $this->db->query($sql);
					if($res){
						$response['add'] = $add_refer;
						$response['answer'] = 1;
					}
					else{
						$response['answer'] = 0;

					}
					echo json_encode($response);
					exit;
				}
			}
			$this->template->assign('site_title', lang('referal_settings'));
			$this->site_title = lang('referal_settings');
			$res = $this->db->get_where('articler_settings', array('type' => 'refer_system'));
			$arr = array();
			foreach($res->result_array() as $item){
				$arr[$item['key']] = $item['value'];
			}

			$data = array(
			'exceptions' => $this->articler_model->get_refer_pages_exceptions(),
			'THEME' => $this->THEME,
			'arr' => $arr
			);
			$content = $this->template->view($this->theme_name.'/refer_settings.tpl',$data,true);

		}
		else{
			if($this->editor && $buffer->activity == 2){
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
			}

		}
			$this->display_layout($content);
	}
	
	public function refer(){
		
		if($this->moderator && !$this->editor){
			$uriSegments = $this->uri->segment_array();
			$refer_id = $uriSegments[4];
			if(!$refer_id)
				exit;
						
			$branch = $this->articler_model->get_branch($refer_id);
			if($branch){
				$response['answer'] = 1;
				$response['content'] = $branch;
			}
			else
				$response['answer'] = 0;
			
			echo json_encode($response);
			exit;
		}
		else{
			
			exit;
		}
	}
	
	public function list_refers(){
		
		if($this->moderator && !$this->editor){
			$uriSegments = $this->uri->segment_array();
			$this->template->assign('site_title', lang('referral_section'));
			$this->site_title = lang('referral_section');
			if(count($uriSegments) == 3)
				$cur_page = $uriSegments[3];
			else
				$cur_page = 0;
			
			$config['base_url'] = site_url('moderator/refers/'.$cur_page);
			
			$refers = $this->articler_model->get_all_refers("$cur_page,$this->per_page");
			
			$config['total_rows'] = $this->db->count_all('user_refers');
			$config['per_page'] = $this->per_page;
			$config['use_page_numbers'] = TRUE;
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$paginator = $this->pagination->create_links();
			$data = array(
			'refers' => $refers,
			'THEME' => $this->THEME,
			'main_language' => $this->main_language
			);
			$content = $this->template->view($this->theme_name.'/list_refers.tpl',$data,true);


		}
		else{
			if($this->editor && $buffer->activity == 2){
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
			}

		}
			$this->display_layout($content);
	}
	
	public function list_payouts(){
		
		if($this->moderator && !$this->editor){
			$uriSegments = $this->uri->segment_array();
			$this->template->assign('site_title', lang('payouts_requests'));
			$this->site_title = lang('payouts_requests');
			if(count($uriSegments) == 4)
				$cur_page = $uriSegments[4];
			else
				$cur_page = 0;
			if(count($uriSegments) == 3)
				$status = $uriSegments[3];
			else
				$status == null;
			$config['base_url'] = site_url('moderator/payouts/'.$status);

			
			if($status == 'open'){
				$status = 0;
			}
			elseif($status == 'closed'){
				$status = 1;
			}
			else{
				$status = null;
			}
			$payouts = $this->articler_model->get_payouts($status, null, "$cur_page,$this->per_page");
			
			$config['total_rows'] = $this->articler_model->get_num_payouts_by_status_id($status);
			$config['per_page'] = $this->per_page;
			$config['use_page_numbers'] = TRUE;
			$config['uri_segment'] = 4;
			$this->pagination->initialize($config);
			$paginator = $this->pagination->create_links();
			$data = array(
			'payouts' => $payouts,
			'THEME' => $this->THEME,
			'main_language' => $this->main_language
			);
			$content = $this->template->view($this->theme_name.'/list_payouts.tpl',$data,true);


		}
		else{
			if($this->editor && $buffer->activity == 2){
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
			}

		}
			$this->display_layout($content);

	}
	
	protected function moderator_article($id, $message_error = ''){
			
		$buffer = $this->articler_model->get_article_by_id($id);
		if($buffer->activity == 1)
			$this->articler_model->block_article($id);

        foreach($this->all_headings as $heading){
            $options[$heading['id']] = $heading['name_russian'];
        }
		
		if($buffer->activity == 2){
			$editor = $buffer->content;
		}
		else{
			$this->CKEditor = new CKEditor('/ckeditor/');
			ob_start();
			$this->CKEditor->editor("editor",$buffer->content,array('language' => substr($this->main_language,0,2))); 
			$editor=ob_get_contents();
			ob_clean();
		}
		
		$this->template->assign('activity',$buffer->activity);
		$this->template->assign('is_moderator',1);
		$this->template->assign('id',$id);
		
		if($buffer->activity == 2)
			$headings = $buffer->heading_name_russian;
		else
			$headings = form_dropdown('headings',$options,$buffer->heading_id,'id="headings"');
			
			$buffer_url = $buffer->url;
			list($empty,$url_heading,$url_article) = split('/',$buffer_url);
			$arr_article = explode('.',$url_article);
			$url = $arr_article[0];
			$temp = explode('_',$url);
			array_pop($temp);
			$url = implode('_',$temp);
			

        $data = array(
            'header' => $buffer->header,
            'content' => $editor,
			'annotation' => $buffer->annotation,
            'headings' => $headings,
			'is_select_headings' => 1,
            'is_moderator' => 1,
			'activity' => $buffer->activity,
			'id' => $id,
			'message_error' => $message_error,
			'is_publish' => 1,
			'header' => $buffer->header,
			'url' => $url,
			'main_language' => $this->main_language
        );
		
		$content = $this->template->view($this->theme_name.'/moderator_article.tpl',$data,true);
		return $content;
	}
	
	protected function moderator_resolution($id){
		
		
		$this->template->assign('activity',1);
		$this->template->assign('is_moderator',1);
		
		if(isset($_REQUEST['reject']) && ($_REQUEST['reason'] == '' || strlen($_REQUEST['reason']) < 3)){
			return $this->moderator_article($id, lang('rejection_of_the_publication_empty'));
		}
		
		if(isset($_REQUEST['is_published'])){
			$article = $this->articler_model->get_article_by_id($id,'header');
			$res = $this->articler_model->update_article($id);
			
			if($res)
				$message = 'Статья "'.$article->header.'" из рубрики "'.$article->heading_name_russian.'" успешно отредактирована';
			else
				$message = 'Неизвестная ошибка редактирования!';
		}
		elseif(isset($_REQUEST['publish']) || $_REQUEST['hidden_publish'] == 1){
			$res = $this->articler_model->publish_article($id);
			$article = $this->articler_model->get_article_by_id($id,'header');
			$this->template->assign('num_moderate_articles',$this->articler_model->count_moderate_articles());	
			if(isset($_REQUEST['is_published'])){
				$message = lang('article_from_rubric_changed');
				$message = str_replace('%article%',$article->header,$message);
				$message = str_replace('%rubric%',$article->heading_name_russian,$message);
			}

			else{
				$message = lang('article_from_rubric_published');
				$message = str_replace('%article%',$article->header,$message);
				$message = str_replace('%rubric%',$article->heading_name_russian,$message);
			}
			
		}
		
		
		elseif(isset($_REQUEST['reject']) || $_REQUEST['hidden_reject'] == 1){
			
			$res = $this->articler_model->reject_article($id, $_REQUEST['reason']);
			$article = $this->articler_model->get_article_by_id($id,'header,user_id,url');
			$this->template->assign('num_moderate_articles',$this->articler_model->count_moderate_articles());	
			$message = 'Статья <<'.$article->header.'>> из рубрики "'.$article->heading_name_russian.'" отклонена по следующей причине: "'.strip_tags($_REQUEST['reason']).'"';
			$mail_message = $this->settings_model->get_mail_template('reject_article','property','text');
			$mail_message = str_replace('%автор%',$article->username,$mail_message);
			$mail_message = str_replace('%статья%',$article->header,$mail_message);
			$mail_message = str_replace('%рубрика%',$article->heading_name_russian,$mail_message);
			$mail_message = str_replace('%формулировка%',strip_tags($_REQUEST['reason']),$mail_message);
			$to = $this->articler_users->get_email($article->user_id);
			$subject = lang('reject_article');
			$email = $this->email;
			if(defined('USE_SENDMAIL'))
            	$email->protocol = 'sendmail';
      		 $email->mailtype = 'html';
			$email->from($this->articler_model->site_mail);
			$email->to($to);
			$email->subject($subject);
			$email->message($mail_message);
			$res_email = $email->send();

		}
		
		
		$data = array(
		'message' => $message
		);
		
		
		$content = $this->template->view($this->theme_name.'/resolution.tpl',$data,true);
		return $content;
	}
	
	public function moderator_edit(){
	
		if($this->moderator):	
		
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[3];
		$buffer = $this->articler_model->get_article_by_id($id);
		$content = $buffer->content;
		$temp = lang('editing_the_material');
		$temp = str_replace('%material%',$buffer->heading_name_russian,$temp);
		$this->template->assign('site_title',$temp);
		$this->site_title = $temp;
		if($buffer->activity==1)
			$this->articler_model->block_article($buffer->id,'moderate');
		$user = $this->users->get_user_by_id($buffer->user_id);
		$_SESSION['user_login'] = $user->row()->username;
		
		$this->CKEditor = new CKEditor('/ckeditor/');
		ob_start();
		$this->CKEditor->editor("editor",$content,array('language' => substr($this->main_language,0,2))); 
		$editor=ob_get_contents();
		ob_clean();
		
		$this->template->assign('activity',$buffer->activity);
		$this->template->assign('is_moderator',1);
		$this->template->assign('id',$id);
		$this->template->assign('is_edited',$id);
		
		foreach($this->all_headings as $heading){
            $options[$heading['id']] = $heading['name_russian'];
        }
		
		$buffer_url = $buffer->url;
        $temp = explode('/',$buffer_url);
        $url_heading = $temp[1];
        $url_article = $temp[2];
		$arr_article = explode('.',$url_article);
		$url = $arr_article[0];
		if(strpos($url,'_')){
			$temp = explode('_',$url);
			array_pop($temp);
			$url = implode('_',$temp);
		}
			
		
		$data = array(
		'header' => $buffer->header,
		'annotation' => $buffer->annotation,
        'description' => $buffer->description,
        'keywords' => $buffer->keywords,
		'username' => $buffer->username,
		'content' => $editor,
		'is_moderator' => 1,
		'is_edited' => 1,
		'id' => $buffer->id,
		'headings' => form_dropdown('headings',$options,$buffer->heading_id,'id="headings"'),
		'is_select_headings' => 1,
		'header' => $buffer->header,
		'url' => $url,
		'activity' => $buffer->activity,
		'rating_for_homefeed' => $this->articler_model->rating_for_show[$buffer->type_heading],
		'bonus_for_homefeed' => $this->articler_model->bonus_for_homefeed,
		'main_language' => $this->main_language
		);
		
		$outer_link = $this->articler_model->get_outer_link($id);
	
		if($outer_link)
			$data['outer_link'] = $outer_link;
		
		if($this->editor && $buffer->activity == 2){
			$editor_data = array(
			'message' => lang('rights_on_edit_published_articles_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$editor_data,true);
		}
		else{
			$content = $this->template->view($this->theme_name.'/article_moderated.tpl',$data,true);

		}
		
		
		else:
		
		$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
			
		endif;
		
		$this->display_layout($content);

	}
	
	public function rubric_add(){
		
		if($this->moderator && !$this->editor){
			
			if(isset($_POST['submit'])){
				$rubric_exists = $this->articler_model->is_rubric_exists($this->input->post('name'));
				if(in_array($this->input->post('name'),$this->articler_model->forbidden_headings))
					$forbidden_headings = true;
				else
					$forbidden_headings = false;
				if(!$rubric_exists && !$forbidden_headings){
					$res = $this->articler_model->add_rubric();
					if($res)
						redirect(site_url('moderator/rubricator'));
				}
				
				
			}
			$data = array(
			'type_headings' => form_dropdown('type_heading',array('1' => 'Основная', '2' => 'Особая', '3' => 'Скрытая'),1),
			'THEME' => $this->THEME,
			'main_language' => $this->main_language
			);
			if(isset($_POST['submit']) && $rubric_exists){
				$data['message'] = lang('rubric_already_exists');
				$data['message'] = str_replace('%rubric%', $this->input->post('name_russian'),$data['message']);
				$data['header'] = $this->input->post('name_russian');
				$data['url'] = $this->input->post('name');

			}
			elseif(isset($_POST['submit']) && $forbidden_headings){
				$data['message'] = lang('rubrics_are_prohibited');
				$data['message'] = str_replace('%rubrics%',$this->articler_model->forbidden_headings,$data['message']);
				$data['header'] = $this->input->post('name_russian');
				$data['url'] = $this->input->post('name');
			}
			$content = $this->template->view($this->theme_name.'/rubric_add.tpl',$data,true);
			$this->site_title = lang('add_rubric');
			$this->template->assign('site_title', $this->site_title);

		
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		$this->display_layout($content);
	}
	
	
	public function rubric_update(){
		
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[3];
		if($id == 0)
			redirect(site_url(''));
		
		if($this->moderator && !$this->editor){
			
			if(isset($_POST['submit'])){
				$rubric_exists = $this->articler_model->is_rubric_exists($this->input->post('name'),$id);
				if(!$rubric_exists){
					$res = $this->articler_model->update_rubric($id);
					if($res)
						redirect(site_url('moderator/rubricator'));
				}
						
			}
			$query = $this->db->get_where('headings',array('id' => $id));
			$adverts_block = $this->articler_model->free_adverts_for_heading($id);
			$data = array(
			'type_headings' => form_dropdown('type_heading',array('1' => lang('based_type_heading'), '2' => lang('special_type_heading'), '3' => lang('hidden_type_heading')),$query->row()->type_heading),
			'THEME' => $this->THEME,
			'is_update' => 1,
			'header' => $query->row()->name_russian,
			'url' => $query->row()->name,
			'id' => $id,
			'adverts_block' => $adverts_block,
			'main_language' => $this->main_language
			);
			if(isset($_POST['submit']) && $rubric_exists){
				$data['message'] = lang('rubric_already_exists');
				$data['message'] = str_replace('%rubric%',$this->input->post('name_russian'),$data['message']);
				$data['header'] = $this->input->post('name_russian');
				$data['url'] = $this->input->post('name');

			}
			$content = $this->template->view($this->theme_name.'/rubric_add.tpl',$data,true);
			$this->site_title = lang('edit_rubric');
			$this->site_title = str_replace('%rubric%',$query->row()->name_russian,$this->site_title);
			$this->template->assign('site_title', $this->site_title);

		
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		$this->display_layout($content);
	}
	
	public function giventopic_update(){
		
		$uriSegments = $this->uri->segment_array();
		if($this->moderator && !$this->editor){
			$this->site_title = lang('editing_given_topic');
			$this->template->assign('site_title', $this->site_title);
			$id = (int)$uriSegments[4];
			if(!$id)
				redirect(site_url(''));
			if(isset($_POST['submit'])){
				if(!$this->articler_model->is_giventopic_exists($this->input->post('url'),$this->input->post('headings'),$id)){
					$res = $this->articler_model->update_giventopic($id);
					if($res)	
						redirect(site_url('moderator/giventopics'));
					else
						$message = lang('error_inserting_base');
				}
				else
					$message = lang('specified_topic_with_url_exists');
			}
			
			$query = $this->db->get_where('given_topics',array('id' => $id));
			if(count($query->result_array()) < 1)
				redirect(site_url(''));
			foreach($this->all_headings as $heading){
				$headings[$heading['id']] = $heading['name_russian'];
			}
			$data = array(
			'THEME' => $this->THEME,
			'is_update' => 1,
			'header' => $query->row()->header,
			'url' => $query->row()->url,
			'headings' => form_dropdown('headings',$headings,$query->row()->heading_id,'id="headings"'),
			'id' => $id,
			'main_language' => $this->main_language
			);
			if(isset($message))
				$data['message'] = $message;
			
			$content = $this->template->view($this->theme_name.'/giventopic_add.tpl',$data,true);
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
			$this->display_layout($content);

	}
	
	public function giventopic_add(){
		
		if($this->moderator && !$this->editor){
		
			if(isset($_POST['submit'])){
				if(!$this->articler_model->is_giventopic_exists($this->input->post('url'),$this->input->post('headings'))){
					$res = $this->articler_model->add_giventopic();
					if($res)	
						redirect(site_url('moderator/giventopics'));
					else
						$message = lang('error_inserting_base');
				}
				else
					$message = lang('specified_topic_with_url_exists');
			}

			foreach($this->all_headings as $heading){
				$headings[$heading['id']] = $heading['name_russian'];
			}
			$select_headings = form_dropdown('headings',$headings,$selected,'id="headings"');
			$this->site_title = lang('addition_given_topic');
			$this->template->assign('site_title', $this->site_title);
			$data = array(
			'THEME' => $this->THEME,
			'headings' => $select_headings,
			'main_language' => $this->main_language
			);
			if(isset($message))
				$data['message'] = $message;
			if(isset($_POST['submit'])){
				$data['header'] = $_POST['header'];
				$data['url'] = $_POST['url'];
				$data['headings'] = form_dropdown('headings',$headings,$_POST['headings'],'id="headings"');
			}
			$content = $this->template->view($this->theme_name.'/giventopic_add.tpl',$data,true);
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		$this->display_layout($content);
	}
	
	public function giventopics_list(){
		
		if($this->moderator && !$this->editor){
			$this->site_title = lang('assigned_topics');
			$this->template->assign('site_title', $this->site_title);
			$data = array(
			'topics' => $this->articler_model->get_giventopics(true),
			'THEME' => $this->THEME
			);
			$content = $this->template->view($this->theme_name.'/giventopics.tpl',$data,true);
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		$this->display_layout($content);
		
	}
	
	public function advert_delete(){
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[3];
		if($this->moderator && !$this->editor){
			$res = $this->articler_model->delete_advert($id);
			if($res)
				$response['answer'] = 1;
			else
				$response['answer'] = 0;
			

		}
		else{
			$response['answer'] = 0;

		}
		
		echo json_encode($response);
		exit;
	}
	
	public function default_advert_update(){
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[3];
		
		if($this->moderator && !$this->editor){
			$message = '';
			$advert = $this->articler_model->get_default_advert_by_id($id);
			
			if(isset($_POST['submit'])){
				$res = $this->articler_model->update_default_advert($id);
				if($res)
					redirect(site_url('moderator/adverts'));
				else
					$message = lang('error_edit_advert_block');
				
			}

			$data = array(
			'message' => $message,
			'advert' => $advert
			);
		
			$content = $this->template->view($this->theme_name.'/default_advert_update.tpl',$data,true);
			$this->site_title = lang('editing_advert_block');
			$this->template->assign('site_title', $this->site_title);

		
		}
		else{
			$data = array(
			'message' => 'Доступ в данный раздел разрешен только модератору!'
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		$this->display_layout($content);
	}
	
	public function advert_update(){
		
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[3];

		if($this->moderator && !$this->editor){
			$message = '';
			$types = array(
			'over_content' => 'Над контентом',
			'under_content' => 'Под контентом',
			'under_image' => 'Под изображением'
			);
			$advert = $this->articler_model->get_advert_by_id($id);
			$types_box = form_dropdown('type',$types,$advert['type'],' style="width: 200px;" id="advert_type"');
			
			if(isset($_POST['submit'])){
				$advert_exists = $this->articler_model->is_advert_exists($this->input->post('name'),$id);
				if(!$advert_exists){
					$res = $this->articler_model->update_advert($id);
					if($res)
						redirect(site_url('moderator/adverts'));
					else
						$message = 'Ошибка редактирования рекламного блока';
				}
				else{
					$message = 'Рекламный блок с данным названием уже существует!';
				}
				
			}

			$data = array(
			'types_box' => $types_box,
			'message' => $message,
			'advert' => $advert,
			'is_update' => true
			);
		
			$content = $this->template->view($this->theme_name.'/advert_add.tpl',$data,true);
			$this->site_title = 'Редактировать рекламный блок';
			$this->template->assign('site_title', $this->site_title);

		
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		$this->display_layout($content);
		
	}
	
	
	public function advert_article(){
		
		if($this->moderator && !$this->editor){
			$uriSegments = $this->uri->segment_array();
			$id = $uriSegments[3];
			$response['content'] = $this->articler_model->free_adverts_for_article($id);
			echo json_encode($response);
			exit;
		}
		else{
			$response['content'] = lang('rights_on_the_section_only_moderator');
			echo json_encode($response);
			exit;
		}
	}
	
	public function save_advert_article(){
		
		if($this->moderator && !$this->editor){
			$uriSegments = $this->uri->segment_array();
			$id = $uriSegments[4];
			$res = $this->articler_model->set_adverts_to_article($id);
			$response['content'] = $this->articler_model->get_article_adverts($id);
			echo json_encode($response);
			exit;
		}
		else{
			$response['content'] = lang('rights_on_the_section_only_moderator');
			echo json_encode($response);
			exit;
		}
	}
	
	public function remove_advert_article(){
		
		if($this->moderator && !$this->editor){
			$uriSegments = $this->uri->segment_array();
			$article_id = $uriSegments[4];
			$advert_id = $uriSegments[5];
			$res = $this->articler_model->unset_advert_to_article($article_id,$advert_id);
			$response['content'] = $this->articler_model->get_article_adverts($article_id);
			echo json_encode($response);
			exit;
		}
		else{
			$response['content'] = lang('rights_on_the_section_only_moderator');
			echo json_encode($response);
			exit;
		}
		
		
	}
	
	public function advert_add(){
		
		if($this->moderator && !$this->editor){
			$message = '';
			$types = array(
			'over_content' => 'Над контентом',
			'under_content' => 'Под контентом',
			'under_image' => 'Под изображением'
			);
			$types_box = form_dropdown('type',$types,'over_content',' style="width: 200px;"');
			
			if(isset($_POST['submit'])){
				$advert_exists = $this->articler_model->is_advert_exists($this->input->post('name'));
				if(!$advert_exists){
					$res = $this->articler_model->add_advert();
					if($res)
						redirect(site_url('moderator/adverts'));
					else
						$message = 'Ошибка добавления рекламного блока';
				}
				else{
					$message = 'Рекламный блок с данным названием уже существует!';
				}
				
			}
			
			$data = array(
			'types_box' => $types_box,
			'message' => $message
			);
		
			$content = $this->template->view($this->theme_name.'/advert_add.tpl',$data,true);
			$this->site_title = 'Добавить рекламный блок';
			$this->template->assign('site_title', $this->site_title);

		
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		$this->display_layout($content);
		
	}
	
	
	
	public function advert_articles(){
		if(isset($_POST['is_ajax']))
			$is_ajax = 1;
		else
			$is_ajax = 0;
		if($this->moderator && !$this->editor){
			$uriSegments = $this->uri->segment_array();
			$uri_arr = $this->articler_model->get_uri_info_in_advert_articles($uriSegments);
			$advert_id = $uriSegments[4];
			$advert = $this->articler_model->get_advert_by_id($advert_id);
			$articles = $this->articler_model->get_articles_by_advert_id($advert_id,$uri_arr['heading_id'], $uri_arr['cur_page'], $this->per_page);
			$config['base_url'] = site_url($uriSegments[1]);
			$config['total_rows'] = $this->articler_model->get_num_articles_by_advert_id($advert_id,$uri_arr['heading_id']);
			$config['per_page'] = $this->per_page;
			$config['use_page_numbers'] = TRUE;
			$this->pagination->initialize($config);
			$paginator = $this->pagination->create_links();
			$this->site_title = 'Связи статей с рекламными блоками';
		
			$this->template->assign('site_title',$this->site_title);	
			$data = array(
			'articles' => $articles,
			'THEME' => $this->THEME,
			'paginator' => $paginator,
			'advert' => $advert
			);
		
			$content = $this->template->view($this->theme_name.'/advert_articles.tpl',$data,true);

		
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		if($is_ajax){
			$response['content'] = $content;
			echo json_decode($response);
			exit;
		}
		else{
			$this->display_layout($content);
		}
	}
	
	public function advert_articles_ajax(){
		$action = $_POST['action'];
		
		if($this->moderator && !$this->editor){
			if($action == 'show'){
				$heading_id = (int)$_POST['heading_id'];
				$advert_id = (int)$_POST['advert_id'];
				$articles = $this->articler_model->get_articles_by_heading_id_for_adverts($heading_id,$advert_id);
				if(count($articles)){
					$html = '<input type="hidden" id="heading_id" value="'.$heading_id.'" />';
					$html .= '<table width="80%" style="margin-left: 15px; margin-right: 15px;">';
					foreach($articles as $article){
						$html .= '<tr>';
						if($article['checked_id'] && $article['id'] == $article['checked_id'])
							$checked = 'checked';
						else
							$checked = '';
						$html .= '<td><input type="checkbox" class="checkbox_article" data-id="'.$article['id'].'" '.$checked.'/></td>';
						$html .= '<td><a href="'.$article['url'].'" target="_blank" title="'.$article['header'].'">'.splitterWord($article['header'],40).'</a></td>';
						$html .= '</tr>';
					}
					$html .= '</table>';
					$response['answer'] = 1;
					$response['content'] = $html;
				}
				else{
					$response['answer'] = 0;
				}
				
				
			}
			elseif($action == 'join'){
				$advert_id = (int)$_POST['advert_id'];
				$res = $this->articler_model->join_articles_to_advert($advert_id,$_POST['checked'],$_POST['unchecked']);
				if($res)
					$response['answer'] = 1;
				else
					$response['answer'] = 0;

			}
			
			elseif($action == 'set_heading'){
				$heading_id = (int)$_POST['heading_id'];
				$advert_id = (int)$_POST['advert_id'];
				$res = $this->articler_model->join_heading_to_advert($advert_id,$heading_id);
				if($res)
					$response['answer'] = 1;
				else
					$response['answer'] = 0;
			}
		}
		else{
			$response['answer'] = 0;
		}
		
		echo json_encode($response);
		exit;
	}
	
	public function adverts(){
		
		if($this->moderator && !$this->editor){
			$this->site_title = 'Рекламные блоки';
			$this->template->assign('site_title', $this->site_title);
			$blocks = $this->articler_model->get_advert_blocks();
			$default_blocks = $this->articler_model->get_advert_default_blocks();
			$options['0'] = 'Не выбрана';
			foreach($this->all_headings as $heading){
				$options[$heading['id']] = $heading['name_russian'];
			}
			
			$data = array(
				'headings' => form_dropdown('headings',$options,0,'onchange="load_articles(this);return false;"'),
			);
			$list_articles = $this->template->view($this->theme_name.'/advert_list_articles.tpl',$data,true);
			$data = array(
			'blocks' => $blocks,
			'default_blocks' => $default_blocks,
			'headings' => form_dropdown('headings',$options,0,'onchange="set_advert_heading(this);return false;" style="width: 250px;"'),
			'list_articles' => $list_articles,
			'THEME' => $this->THEME
			);
			$content = $this->template->view($this->theme_name.'/adverts.tpl',$data,true);
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		$this->display_layout($content);
	}
	
	public function rubricator(){
		
		if($this->moderator && !$this->editor){
			$this->site_title = 'Рубрики';
			$this->template->assign('site_title', $this->site_title);
			$headings = $this->all_headings;
			foreach($headings as $key=>$value){
				$headings[$key]['adverts'] = $this->articler_model->get_adverts_for_heading($value['id']);
			}
			$data = array(
			'headings' => $headings,
			'THEME' => $this->THEME
			);
			$content = $this->template->view($this->theme_name.'/rubricator.tpl',$data,true);
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);			
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		$this->display_layout($content);

	}
	
	public function moderator(){
		
		$uriSegments = $this->uri->segment_array();
	
		if($this->moderator){
				$action = $uriSegments[2];
				switch($action):
			case 'moderate_articles':
				$content = $this->moderator_list_articles(1);
			break;
			case 'public_articles':
				$content = $this->moderator_list_articles(2);
			break;
			case 'edit':
				$content = $this->moderator_list_articles(3);
			break;
			case 'article':
				$content = $this->moderator_article($uriSegments[3]);	
			break;
			case 'resolution':
				$content = $this->moderator_resolution($uriSegments[3]);
			break;
				
				endswitch;
			
		}
		else{
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
				$this->display_layout($content);

	}
	
	public function change_article_rating(){
		
		$uriSegments = $this->uri->segment_array();
		$article_id = $uriSegments[3];
		$rating = $this->input->post('rating');
		if($article_id < 1)
			exit;
		$article = $this->articler_model->get_article_by_id($article_id,'user_id');
		
		if($this->moderator && !$this->editor):
			
				if($rating < 0)	{
					$response['answer'] = 0;
					$response['content'] = lang('article_cannot_be_0');
				}
				
									
			if(empty($response['answer'])):
					$is_quit_sandbox = false;
					$res = $this->articler_model->change_rating_manually('article',$rating,$article->user_id,$article_id,$is_quit_sandbox);
					if(!$res){
						$response['answer'] = 0;
						$response['content'] = lang('error_changing_rating');
					}
					else{
						$response['answer'] = 1;
						$response['content'] = $res;
						$response['user_rating'] = $this->articler_model->get_rating($article->user_id, 'author');
						if($is_quit_sandbox)
							$response['is_quit_sandbox'] = 1;
						else
							$response['is_quit_sandbox'] = 0;

					}
					
			endif;
				
		
		else:
		
		$response['answer'] = 0;
		$response['content'] = lang('has_no_right_for_changing_rating');
		
		endif;
		
		echo json_encode($response);
		
	}
	
	public function check_refer_exception(){
		$page = $this->input->post('page');
		$url = site_url('').$page;
		$response['url'] = $url;
		if(check_http_status($url) != 200){
			$response['answer'] = 0;
			$response['error'] = 'Адрес недоступен';

		}
		else{
			
			$exceptions = $this->articler_model->get_refer_pages_exceptions(true);
			if(in_array($page,$exceptions))
				$response['answer'] = 0;
			else
				$response['answer'] = 1;
				
			if(!$response['answer'])
				$response['error'] = 'Адрес запрещен в использовании';

		}
		
		echo json_encode($response);
		exit;
	}
	
	public function change_rating(){
		
		$uriSegments = $this->uri->segment_array();
		$login = $uriSegments[3];
		$type_rating = $this->input->post('type_rating');
		$rating = $this->input->post('rating');
		$query = $this->users->get_user_by_username($login);
		
		if($this->moderator && !$this->editor):
		
			if($type_rating == 'activity'){
			
			
				if($rating < $this->articler_model->lowest_rating_activity)	{
					$response['answer'] = 0;
					$response['content'] = lang('rating_activity_cannot_be_lower');
					$response['content'] = str_replace('%rating%',$this->articler_model->lowest_rating_activity,$response['content']);
				}
				elseif($rating > $this->articler_model->highest_rating_activity){
					$response['answer'] = 0;
					$response['content'] = lang('rating_activity_cannot_be_higher');
					$response['content'] = str_replace('%rating%',$this->articler_model->highest_rating_activity,$response['content']);
				}
				else{
						$response['answer'] = 1;
				}
						
			}
				
			else{
			
	
				if($rating < 0){
					$response['answer'] = 0;
					$response['content'] = lang('author_rating_cannot_be_lower_0');
				}
				else{
					$response['answer'] = 1;
				}
			
		
			}

						
			if($response['answer'] == 1):
					
					$res = $this->articler_model->change_rating_manually($type_rating,$rating,$query->row()->id);
					if(!$res){
						$response['answer'] = 0;
						$response['content'] = lang('error_changing_rating');
					}
					else{
						$response['answer'] = 1;
						$response['content'] = $res;
					}
					
			endif;
				
		
		else:
		
		$response['answer'] = 0;
		$response['content'] = lang('has_no_right_for_changing_rating');
		
		endif;
		
		echo json_encode($response);

	}
	

	public function list_authors(){
		
		$uriSegments = $this->uri->segment_array();	
		if(count($uriSegments) == 2)
			$cur_page = $uriSegments[2];
		else
			$cur_page = 0;			
		$this->template->assign('site_title',lang('list_authors'));	
		$this->site_title = lang('list_authors');
		$authors = $this->articler_model->list_authors("$cur_page,$this->per_page");
		$author_groups = array_group($this->articler_model->settings,'author_groups','type');
		$statuses_points = $this->articler_model->get_author_statuses('points', 1);
		$statuses_articles = $this->articler_model->get_author_statuses('articles', 1);

		for($i = 0; $i < count($authors); ++$i){
			$authors[$i]['status'] = $this->articler_model->get_author_group($authors[$i]['author_rating'],$authors[$i]['num_articles'],$statuses_points,$statuses_articles);

		}
	
		$config['base_url'] = site_url('avtory');
		$config['total_rows'] = $this->articler_model->num_authors();
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 2;
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();
		
		$data = array(
		'authors' => $authors,
		'paginator' => $paginator,
		'main_language' => $this->main_language
		);
		
		$content = $this->template->view($this->theme_name.'/list_authors.tpl',$data,true);
		$this->display_layout($content);
		
	}
	
	public function profile($login){

		$uriSegments = $this->uri->segment_array();	
		if(count($uriSegments) == 3)
			$cur_page = $uriSegments[3];
		else
			$cur_page = 0;

		$user = $this->users->get_user_by_username($login);
		if(count($user->result_array()) == 0){
			$this->action404();
			return true;
		}
		$this->template->assign('nickname',$login);
		$site_title = lang('dashboard');
		$site_title = str_replace('%login%',$login,$site_title);
		$this->template->assign('site_title',$site_title);
		$this->site_title = $site_title;
			
		$user_id = $user->row()->id;
		$author_rating = $this->articler_model->get_rating($user_id);
		$rating_activity = $this->articler_model->get_rating($user_id,'activity');
		$num_articles = $this->articler_model->get_num_articles_by_user_id($user_id);
		$num_comments_author = $this->articler_model->get_num_comments_by_user_id($user_id);
		$last_comments_author = $this->articler_model->last_comments("user_id = $user_id");
		$last_comments_for_author = $this->articler_model->last_comments_for_author($user_id);
		$num_comments_for_author = $this->articler_model->get_num_comments_to_user_id($user_id);
		$last_articles = $this->articler_model->last_articles_user_id($user_id);
		$author_group = $this->articler_model->get_author_group($author_rating, $num_articles);
		if(!$num_articles)
			$num_articles = '0';
		if(!$num_comments_author)
			$num_comments_author = '0';
		if(!$num_comments_for_author)
			$num_comments_for_author = '0';
		
		$articles = $this->articler_model->get_articles_author($user_id,"$cur_page,$this->per_page");
		$author_profile = $this->articler_model->get_author_profile($user_id);
		$config['base_url'] = site_url(lang('authors').'/'.$login);
		$config['total_rows'] = $this->articler_model->get_num_articles_by_user_id($user_id);
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();
		
		$buffer = $this->set_avatar_src($login, true);
		$avatar = $this->articler_model->get_avatar($user_id);
		if($avatar)
			$src_id = site_url('uploads/images/avatars').'/'.$user_id.'.'.$avatar->ext;
			
		if($author_profile)
			$author_name = $author_profile->name.' '.$author_profile->family;
		else
			$author_name = $login;
			
		$data = array(
		'avatar_src' => $src_id,
		'avatar_sizes' => array('width'=>$avatar->small_width, 'height'=>$avatar->small_height),
		'author_rating' => $author_rating,
		'rating_activity' => $rating_activity,
		'num_articles' => $num_articles,
		'num_comments_author' => $num_comments_author,
		'last_comments_author' => $last_comments_author,
		'num_comments_for_author' => $num_comments_for_author,
		'last_comments_for_author' => $last_comments_for_author,
		'articles' => $articles,
		'author_name' => $author_name,
		'nickname' => ucfirst($login),
		'paginator' => $paginator,
		'main_language' => $this->main_language
		);
		
		if($this->login)
			$data['author'] = 'user';
		else
			$data['author'] = 'guest';
		
		if($author_group)
			$data['author_group'] = $author_group;
		
		if($this->moderator)
			$data['is_moderator'] = 1;
			
		if($this->editor)
			$data['is_editor'] = 1;
		if($this->moderator && !$this->editor){
			$resoult_source = 0;
			$num_visites_avg = $this->statistic_model->get_visites_by_user_id($user_id,'sum',false,$resoult_source);
			if($resoult_source)
				$data['num_visites_avg'] = $resoult_source;
			$resoult_source = 0;
			$num_visites_all = $this->statistic_model->get_visites_by_user_id($user_id,'sum',true,$resoult_source);
			if($resoult_source)
				$data['num_visites_all'] = $resoult_source;
			$data['num_visites_avg_correct'] = $num_visites_avg;
			$data['num_visites_all_correct'] = $num_visites_all;			
			
		}
		
		$content = $this->template->view($this->theme_name.'/profile.tpl',$data,true);

		$this->display_layout($content);
	
	}
	
	public function index(){
	
		$this->template->assign('site_title', $this->site_title);
		$this->template->assign('is_main', 1);
		$data = array(
		'main_headings' => $this->main_headings,
		'add_headings' => $this->add_headings
		);
		$content = '';
		if(!$this->cur_page):
			$content = $this->template->view($this->theme_name.'/includes/main/preface.tpl',$data,true);
		endif;
		$content .= $this->home_feed();
		$this->template->assign('is_index',1);
		$this->display_layout($content);

	}
	
	protected function home_feed(){
		
		$uriSegments = $this->uri->segment_array();	
		if($this->cur_page)
			$cur_page = $this->cur_page;
		else
			$cur_page = 0;	
		$mktime = mktime();
		$articles = $this->articler_model->get_articles_for_home_feed($mktime,"$cur_page,$this->per_page");
		$config['base_url'] = site_url('');

		$config['total_rows'] = $this->articler_model->get_num_articles_for_home_feed($mktime);
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 1;
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();
		
		$data = array(
		'articles' => $articles,
		'paginator' => $paginator,
		'user_id' => $this->user_id,
		'main_language' => $this->main_language
		);
		if($this->login)
			$data['author'] = 'user';
		else
			$data['author'] = 'guest';

		if($this->moderator && !$this->editor)
			$data['is_moderator'] = 1;
		
		return $this->template->view($this->theme_name.'/home_feed_articles.tpl',$data,true);
		
	}
	
	
	public function sandbox(){
				
		$uriSegments = $this->uri->segment_array();	
		if(count($uriSegments) == 2)
			$cur_page = $uriSegments[2];
		else
			$cur_page = 0;			
		$mktime = mktime();
		$this->template->assign('site_title',lang('sandbox'));	
		$this->site_title = 'Песочница';
		$articles = $this->articler_model->get_articles_for_sandbox($mktime,"$cur_page,$this->per_page");
		$config['base_url'] = site_url(lang('url_sandbox'));
		$config['total_rows'] = $this->articler_model->get_num_articles_for_sandbox($mktime);
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 2;
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();
		$data = array(
		'articles' => $articles,
		'paginator' => $paginator,
		'main_language' => $this->main_language
		);
		if($this->login)
			$data['author'] = 'user';
		else
			$data['author'] = 'guest';
		
		if($this->moderator && !$this->editor)
			$data['is_moderator'] = 1;
		
		$content = $this->template->view($this->theme_name.'/sandbox_articles.tpl',$data,true);		
		$this->display_layout($content);
	}
	
	public function articles(){
		
		$uriSegments = $this->uri->segment_array();	
		if(count($uriSegments) == 2)
			$cur_page = $uriSegments[2];
		else
			$cur_page = 0;
			
				
		$heading = $this->articler_model->get_heading_by_name($this->heading);
		$articles = $this->articler_model->get_articles_by_heading_id($heading->id, $cur_page, $this->per_page);
		$config['base_url'] = site_url($uriSegments[1]);

		$config['total_rows'] = $this->articler_model->get_num_articles_by_heading_id($heading->id);
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();
			
		$this->template->assign('activity',1);
		
		$this->template->assign('site_title',$heading->name_russian);	
		$this->site_title = $heading->name_russian;	
		$data = array(
		'heading' => $heading->name_russian,
		'heading_name' => $heading->name,
		'articles' => $articles,
		'is_moderator' => $is_moderator,
		'THEME' => $this->THEME,
		'paginator' => $paginator,
		'main_language' => $this->main_language
		);
		if($this->login)
			$data['author'] = 'user';
		else
			$data['author'] = 'guest';
		
		if($this->moderator && !$this->editor)
			$data['is_moderator'] = 1;
		
		$content = $this->template->view($this->theme_name.'/articles.tpl',$data,true);		
		$this->display_layout($content);
		
	}
	
    public function delete_comment(){
       
        $uriSegments = $this->uri->segment_array(); 
		if($uriSegments[3] == 0)
			exit;
		
		if(isset($_POST['private']) && $_POST['private']){
			$comments_table = 'private_comments';
			$articles_table = 'private_articles';
			$private = true;
		}
		else{
			$comments_table = 'article_comments';
			$articles_table = 'articles';
			$private = false;
		}
        
        if($this->moderator && !$this->editor){
            if($this->articler_model->delete_comment($uriSegments[3],$private)){
				$response['answer'] = 1;
				$response['num_comments'] = $this->articler_model->get_num_comments_by_article_id($this->input->post('article_id'),$private);
			}
			else{
				$response['answer'] = 0;
			}
        }
        elseif($this->access){
            $this->db->select('id, user_id, answered, data');
        	$this->db->from($comments_table);
        	$this->db->where('id',$uriSegments[3]);
        	$query = $this->db->get();
        	$comment = $query->row_array();
			if($this->articler_model->is_possible_delete_comment($comment,$this->user_id)){
				if($this->articler_model->delete_comment($uriSegments[3],$private)){
					$response['answer'] = 1;
					$response['num_comments'] = $this->articler_model->get_num_comments_by_article_id($this->input->post('article_id'),$private);
				}
				else{
					$response['answer'] = 0;
				}
			}
			else{
				$response['answer'] = -1;
			}
            
        }
        else{
            
            exit;
        }
		
		echo json_encode($response);
        
    }
	
	public function giventopic_delete(){
		
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[4];
		if($id == 0)
			exit;
		if($this->moderator && !$this->editor){
			$res = $this->db->delete('given_topics',array('id' => $id));
			if($res){
				$response['answer'] = 1;
			}
			else{
				$response['answer'] = 0;
			}
			echo json_encode($response);
		}
		else{
			exit;
		}
	}
	
	public function private_delete(){
		
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[3];
		if($id == 0)
			exit;
		if($this->moderator && !$this->editor){
			$res = $this->db->delete('private_articles',array('id' => $id));
			if($res){
				$response['answer'] = 1;
			}
			else{
				$response['answer'] = 0;
			}
			echo json_encode($response);
		}
		else{
			exit;
		}

	}
	
	public function delete_plus(){
		
		$uriSegments = $this->uri->segment_array();	
		if($this->access && $this->articler_model->is_possible_assess()):
		$this->db->select('id, user_id');
		$this->db->from('articles');
		$this->db->where('id',$uriSegments[3]);
		$query = $this->db->get();
		$article = $query->row();
		if($this->articler_model->delete_plus($article)){
			$response['answer'] = 1;
			$href = site_url('comment/add_plus/'.$article->id);
			$response['rating'] = $this->articler_model->get_article_rating($article->id);
			$response['content'] = "<a href='$href' onclick=\"add_plus(this.href, '$user_id', '$site_url',".$this->articler_model->add_plus.");return false;\" title=\"Поставить автор плюс\" style=\"color:black; text-decoration: none;\">
<img src=\"/templates/articler/images/plus.jpg\" width='13' height='13'/>
&nbsp;&nbsp;".lang('add_plus')."
</a>";
		}
		else{
			$response['answer'] = 0;
		}
		
		echo json_encode($response);
		endif;
	}
	
	public function add_plus(){
		
		$uriSegments = $this->uri->segment_array();	
		$is_possible_access = $this->articler_model->is_possible_assess();
		if($this->access && $is_possible_access):
		$this->db->select('id, user_id,heading_id,is_special');
		$this->db->from('articles');
		$this->db->where('id',$uriSegments[3]);
		$query = $this->db->get();
		$article = $query->row();
		$date = date("Y-m-d H:i:s");
		$arr = array();
		if($this->articler_model->add_plus($article, $date, null, $arr)){
			$response['answer'] = 1;
			$href = site_url('comment/delete_plus/'.$article->id);
			$date = time_change_show_data($date);
			$site_url = site_url('');
			$response['rating'] = $this->articler_model->get_article_rating($article->id);
			$delete_plus = lang('delete_plus');
			$delete_plus = str_replace('%date%',$date,$delete_plus);
			$response['content'] = "<a href='$href' onclick=\"delete_plus(this.href, '$user_id','$site_url',".$this->articler_model->add_plus.");return false;\" title=\"$delete_plus\" style=\"color:black; text-decoration: none;\">
<img src=\"/templates/articler/images/minus.gif\" width='13' height='13'/>
&nbsp;&nbsp;$delete_plus
</a>";
		}
		else{
			if(count($arr))
				$response['answer'] = 2;
			else
				$response['answer'] = 0;
		}
		echo json_encode($response);
		else:
			if($this->access && !$is_possible_access)
				$response['answer'] = 2;
			else
				$response['answer'] = 0;	
		echo json_encode($response);
		endif;
		
	}
	

	public function add_minus(){
		
		$uriSegments = $this->uri->segment_array();	
		$is_possible_access = $this->articler_model->is_possible_assess($this->user_id,'minus');
		if($this->access && $is_possible_access):
		$this->db->select('id, user_id,heading_id,is_special');
		$this->db->from('articles');
		$this->db->where('id',$uriSegments[3]);
		$query = $this->db->get();
		$article = $query->row();
		$date = date("Y-m-d H:i:s");
		$arr = array();
		if($this->articler_model->add_minus($article, $date, null, $arr)){
			$response['answer'] = 1;
			$href = site_url('comment/delete_plus/'.$article->id);
			$date = time_change_show_data($date);
			$site_url = site_url('');
			$response['rating'] = $this->articler_model->get_article_rating($article->id);
			$delete_plus = lang('delete_plus');
			$delete_plus = str_replace('%date%',$date,$delete_plus);
			$response['content'] = "<a href='$href' onclick=\"delete_plus(this.href, '$user_id','$site_url',".$this->articler_model->add_plus.");return false;\" title=\"$delete_plus\" style=\"color:black; text-decoration: none;\">
<img src=\"/templates/articler/images/minus.gif\" width='13' height='13'/>
&nbsp;&nbsp;$delete_plus
</a>";
		}
		else{
			if(count($arr))
				$response['answer'] = 2;
			else
				$response['answer'] = 0;
		}
		echo json_encode($response);
		else:
			if($this->access && !$is_possible_access)
				$response['answer'] = 2;
			else
				$response['answer'] = 0;	
		echo json_encode($response);
		endif;
		
	}
	
	protected function get_comments($article, $private=false){
		
		if($private)
			$query = $this->articler_model->select_private_comments($article->id,"0,$this->per_page_comment");
		else
			$query = $this->articler_model->select_comments($article->id,"0,$this->per_page_comment");
		$comments_arr = $query->result_array();
	
		foreach($comments_arr as $key=>$value){
			if(is_null($value['fullname']))
				$comments_arr[$key]['author'] = $value['username'];
			else	
				$comments_arr[$key]['author'] = $value['name'].' '.$value['family'];

		}
		$num_comments = $this->articler_model->get_num_comments_by_article_id($article->id,$private);
		if($this->access)
			$num_articles = $this->articler_model->get_num_articles_by_user_id($this->user_id);
		else
			$num_articles = 0;
		
		for($i = 0; $i <count($comments_arr); $i++){
			if($comments_arr[$i]['avatar_userid'])				
				$comments_arr[$i]['avatar'] = '<img src="'.$this->path_avatars.$comments_arr[$i]['avatar_userid'].'.'.$comments_arr[$i]['ext'].'" width="40" height="40">';
			else
				$comments_arr[$i]['avatar'] = '';
				
			if($this->articler_model->is_possible_delete_comment($comments_arr[$i],$this->user_id))
				$comments_arr[$i]['is_possible_delete'] = true;
			else
				$comments_arr[$i]['is_possible_delete'] = false;
				
				
			if($comments_arr[$i]['editor_id'] && $comments_arr[$i]['editor_id'] == $comments_arr[$i]['user_id']){
				$comments_arr[$i]['edited'] = ' <font style="font-size:9px;margin-left:5px;">('.lang('edited_by_author').' '.time_change_show_data($comments_arr[$i]['data_modified']).')</font>';
			}
			elseif($comments_arr[$i]['editor_id'] && $comments_arr[$i]['editor_id'] != $comments_arr[$i]['user_id']){
				$comments_arr[$i]['edited'] = ' <font style="font-size:9px;margin-left:5px;">('.lang('edited_by_moderator').' '.time_change_show_data($comments_arr[$i]['data_modified']).')</font>';
			}
			else{
				$comments_arr[$i]['edited'] = '';
			}

		}
		$reply_ids = array();
		foreach($comments_arr as $comment){
			if($comment['reply_id'])
				$reply_ids[] = $comment['reply_id'];
		}
		
		if(count($reply_ids) > 0):
			if($private)
				$main_table = 'private_comments';
			else
				$main_table = 'article_comments';
			$sql = "SELECT $main_table.*, authors.name, authors.family FROM $main_table,authors WHERE $main_table.user_id = authors.user_id AND $main_table.id IN (".implode(',',$reply_ids).")";
			$reply_query = $this->db->query($sql);
			foreach($reply_query->result_array() as $reply){
				$reply_array[$reply['id']] = $reply['name'].' '.$reply['family'].' '.time_change_show_data($reply['data']);
			}
		endif;
		
		
		if($this->access):
		
		$data = array(
		'comments_arr' => $comments_arr,
		'total_comments' => $num_comments,
		'article_id' => $article->id,
		'user_id' => $this->user_id,
		'article_owner_user_id' => $article->user_id,
		'show_num_comments' => $query->num_rows(),
		'data_published' => $article->data_published,
		'arr_comments' => $arr_comments,
		'allow_plea' => 1,
		'main_language' => $this->main_language
		);
		else:
		
		$data = array(
		'comments_arr' => $comments_arr,
		'total_comments' => $num_comments,
		'article_id' => $article->id,
		'user_id' => 0,
		'article_owner_user_id' => $article->user_id,
		'show_num_comments' => $query->num_rows(),
		'data_published' => $article->data_published,
		'unauthorized' => 1,
		'arr_comments' => $arr_comments,
		'main_language' => $this->main_language
		);
		
		endif;
		
		
		if(isset($reply_array) && count($reply_array) > 0)
			$data['reply'] = $reply_array;
		
		$data['add_plus'] = $this->articler_model->add_plus;
		
		if($this->access):
		if($this->articler_model->is_possible_assess($this->user_id) && !$private){
		
		if($num_articles >= $this->articler_model->quantity_articles_for_assess and $this->articler_model->allow_plus($article, $this->user_id))
			$data['allow_plus'] = 1;
		elseif(!$this->articler_model->allow_plus($article, $this->user_id))
			$data['allow_minus'] =1;
			
		}
		endif;
		
		if($private)
			$data['private'] = 1;
		
		if($this->access && !$this->moderator):
		if($this->articler_model->allow_comments($article, $this->user_id))
			$data['allow_comments'] = 1;
		else
			$data['allow_comments'] = 0;
		else:
		$data['allow_comments'] = 0;
		endif;
		
		if($this->moderator)
			$data['is_moderator'] = 1;
            
        $data['THEME'] = 'templates/'.$this->theme_name;
		
		return $this->template->view($this->theme_name.'/articler_comments.tpl',$data,true);
		
	}
	
	public function add_plea(){
		
		$uriSegments = $this->uri->segment_array();	
		$article_id = $uriSegments[3];
		if($this->access && isset($_REQUEST['comment_id'])):
		
		$res = $this->articler_model->add_plea();
		if($res){
			$response['answer'] = 1;
			$response['content'] = lang('complaint_sent_to_the_moderator');
		}
		else{
			$response['answer'] = 0;
			$response['content'] = lang('complaint_sent_error');
		}
		
		else:
		$response['answer'] = 0;
		$response['content'] = lang('complaint_need_authorize');
		
		endif;
		echo json_encode($response);

	}
	
	public function list_pleas(){
		
		if($this->moderator && !$this->editor):
		$this->template->assign('site_title','Список жалоб');
		$this->site_title = 'Список жалоб';
		$uriSegments = $this->uri->segment_array();
		if(count($uriSegments) == 3)
			$cur_page = $uriSegments[3];
		else
			$cur_page = 0;
		if(isset($_REQUEST['cur_page']))
			$cur_page = $_REQUEST['cur_page'];
		$pleas = $this->articler_model->list_pleas(null, "$cur_page,$this->per_page");
		$config['base_url'] = site_url('moderator/pleas');
		$config['total_rows'] = $this->articler_model->count('user_pleas',1);
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		if(empty($_REQUEST['offset']))
			$config['uri_segment'] = 2;
		else
			$config['cur_page'] = $_REQUEST['offset'];
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();	

		$data = array(
		'pleas' => $pleas,
		'THEME' => $this->THEME,
		'paginator' => $paginator,
		'cur_page' => $cur_page,
		'main_language' => $this->main_language
		);
		
		$content = $this->template->view($this->theme_name.'/list_pleas.tpl',$data,true);
	
		else:
		
		$data = array(
		'message' => lang('rights_on_the_section_only_moderator')
		);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		
		endif;
		
		$this->display_layout($content);

	}
	
	public function add_reply(){
		
		$uriSegments = $this->uri->segment_array();	
		$comment_id = $uriSegments[3];
		if($this->access):
			$query = $this->articler_model->select_comments($this->input->post('article_id'),$comment_id);
			foreach($query->result_array() as $elem){
				$arr_comments[$elem['id']] = $elem['username'].' '.time_change_show_data($elem['data']);
			}
			$res = $this->articler_model->insert_comment($this->user_id, $comment_id);

			if($res){
				$response['answer'] = 1;
				$response['num_comments'] = $this->articler_model->get_num_comments_by_article_id($this->input->post('article_id'));
				
				$this->articler_model->change_rating($this->user_id, $this->articler_model->add_comment, 'comment');
				if($res->reply_id)
					$reply_comment = $arr_comments[$res->reply_id];
				else
					$reply_comment = null;
				$data = array(
				'id' => $res->id,
				'username' => $res->username,
				'date' => time_change_show_data($res->data),
				'comment' => $res->comment,
				'reply_comment' => $reply_comment,
				'author' => $res->name.' '.$res->family,
				'main_language' => $this->main_language
				);
				if($this->controller == 'articler')
					$response['content'] = $this->template->view($this->theme_name.'/add_comment.tpl',$data,true);
				else
					$response['content'] = $this->template->view('/add_comment.tpl',$data,true);
				$response['your_num_comments'] = $this->articler_model->get_num_comments_by_user_id($this->user_id);
				$response['rating'] = $this->articler_model->get_rating($this->user_id, 'comment');
			}
			else{
				$response['answer'] = 0;
				$response['content'] = lang('unknown_error');
			}

		else:
		
			$response['answer'] = 0;
			$response['content'] = lang('reply_comment_need_authorize');
		endif;
		echo json_encode($response);

		
	}
	
	public function edit_comment(){
		
		$uriSegments = $this->uri->segment_array();	
		if($uriSegments[3] == 0)
			exit;
			
		if(isset($_POST['private']) && $_POST['private']){
			$comments_table = 'private_comments';
			$articles_table = 'private_articles';
			$private = true;
		}
		else{
			$comments_table = 'article_comments';
			$articles_table = 'articles';
			$private = false;
		}
			
		 if($this->moderator && !$this->editor){
            if($this->articler_model->edit_comment($uriSegments[3],$this->dx_auth->get_user_id())){
				$response['answer'] = 1;
				$comment = $this->articler_model->get_comment_by_id($uriSegments[3],$private);
				$response['content'] = $comment->comment;
			}
			else{
				$response['answer'] = 0;
			}
        }
        elseif($this->access){
			$this->db->select('id, user_id, answered, data');
        	$this->db->from($comments_table);
        	$this->db->where('id',$uriSegments[3]);
        	$query = $this->db->get();
        	$comment = $query->row_array();
			if($this->articler_model->is_possible_delete_comment($comment,$this->user_id)){
			
            	if($this->articler_model->edit_comment($uriSegments[3],$this->user_id)){
					$new_comment = $this->articler_model->get_comment_by_id($uriSegments[3],$private);
					$response['answer'] = 1;
					$response['content'] = $new_comment->comment;
					
				}
				else{
					$response['answer'] = 0;
				}
			}
			else{
				$response['answer'] = -1;
			}
            
        }
        else{
            
            exit;
        }
		
		echo json_encode($response);

	}
	
	public function add_comment(){
		
		$comment_flag = $_REQUEST['comment_flag'];
		if(isset($_POST['private']) && $_POST['private'])
			$private = true;
		else
			$private = false;
			
		if($this->access):
			$old_num_comments = $this->articler_model->get_num_comments_by_article_id($this->input->post('article_id'),$private);
			$res = $this->articler_model->insert_comment($this->user_id);

			if($res){
				$response['answer'] = 1;
				$response['num_comments'] = $this->articler_model->get_num_comments_by_article_id($this->input->post('article_id'),$private);
				$avatar = $this->articler_model->get_avatar_by_user_id($this->user_id);
				$this->articler_model->change_rating($this->user_id, $this->articler_model->add_comment, 'comment');
				$data = array(
				'id' => $res->id,
				'username' => '<a href="'.site_url('avtory').'/'.$res->name.'" target="_blank" style="color:black;text-decoration:underline;">'.$res->username.'</a>',
				'author' => '<a href="'.site_url('avtory').'/'.$res->name.'" target="_blank" style="color:black;text-decoration:underline;">'.$res->name.' '.$res->family.'</a>',
				'date' => time_change_show_data($res->data),
				'comment' => $res->comment,
				'THEME' => $this->THEME,
				'main_language' => $this->main_language
				);
				if(!$old_num_comments)
					$data['one_comments'] = 1;
				if($avatar)
					$data['avatar'] = '<img src="'.$this->path_avatars.$this->user_id.'.'.$avatar->ext.'" width="'.$avatar->small_width.'" height="'.$avatar->small_height.'">';
				$response['content'] = $this->template->view('/add_comment.tpl',$data,true);
				$response['your_num_comments'] = $this->articler_model->get_num_comments_by_user_id($this->user_id);
				$response['rating'] = $this->articler_model->get_rating($this->user_id, 'comment');
			}
			else{
				$response['answer'] = 0;
				$response['content'] = lang('unknown_error');
			}
			
				
		else:
			$response['answer'] = 0;
			$response['content'] = lang('comment_need_authorize');
		endif;
		echo json_encode($response);
		
	}
	
	public function reply_plea(){
		
		if($this->moderator && !$this->editor):
		
			$res = $this->articler_model->consider_plea($this->input->post('plea_id'), $this->input->post('answer_plea'));
			if($res){
				$response['answer'] = 1;
				$response['considered'] = '<img src="/'.$this->THEME.'/images/reply.png" width="12" height="12"/>';
				$response['num_pleas'] = $this->articler_model->num_unconsidered_pleas();
			}
			else{
				$response['answer'] = 0;
				$response['test'] = lang('error_complaint_consideration');
			}
		
		else:
			
			$response['text'] = lang('access_complaint_consideration_only_moderator');
			$response['answer'] = 0;
		
		endif;
		
		echo json_encode($response);

	}
	
	public function outer_link_info(){
		
		if($this->access):
			$content = $this->template->view($this->theme_name.'/outer_link_info.tpl',array(),true);
	
		else:
		
			redirect(site_url(''));
	
		endif;
		
		$this->display_layout($content);

	}
	
	public function outer_link(){
		
		if($this->access):
			$link = $this->input->post('outer_link');
			$article_id = $this->input->post('article_id');
			
			
				$scan_result = $this->articler_model->scanner_outer_link($link);
				if(isset($scan_result['error'])){
					$response['content'] = $scan_result['error'];
					$response['answer'] = 0;
				}
				else{
					$res = $this->articler_model->outer_link($article_id, $link, $scan_result);
					if($res){
						$response['content'] = lang('link_checked_and_attached');
						$response['address'] = $this->articler_model->get_outer_link($article_id,'link');
						$response['answer'] = 1;

					}
					else{
						$response['content'] = lang('unknown_error');
						$response['answer'] = 0;

					}
				}
			

			echo json_encode($response);
		
		else:
		
			redirect(site_url(''));
		
		endif;
		

	}
	
	public function article(){
	
		$uriSegments = $this->uri->segment_array();
		if($uriSegments[1]=='novosti' && count($uriSegments) > 1){
			$news = str_replace('.html','',$uriSegments[2]);
			redirect(site_url(lang('url_news')).'/'.$news);
		}

		if(count($uriSegments) == 2):
		
		$url = '/'.implode('/',$uriSegments);
		$article = $this->articler_model->get_article_by_parameters('*',"url = '$url' AND activity = 2");
		
		if($this->access){
			if(!$this->articler_model->get_visit($article->id, $this->user_id)){
				$this->articler_model->set_visit($article->id, $this->user_id, $article->user_id);
			}
		}
		
		if($article):
		$advert_blocks = $this->articler_model->get_article_advert_blocks($article->id,$article->heading_id);
		$is_quit_sandbox = $this->articler_model->is_quit_sandbox($article,mktime());
		if($is_quit_sandbox)
			$this->statistic_model->set_visit(date("Y-m-d H:i:s"), $article->id, $_SERVER['REMOTE_ADDR'], $this->user_id);	
		
		if($this->user_id == $article->user_id || ($this->moderator && !$this->editor)){
			$result_source = 0;
			$num_visites_avg = $this->statistic_model->average_num_visites($article->id,$result_source);
			if($this->moderator && $result_source)
				$num_visites_avg_correct = '('.$result_source.')';
			else
				$num_visites_avg_correct = '';
			$result_source = 0;
			$num_visites_all = $this->statistic_model->all_num_visites($article->id,$result_source);
			if($this->moderator && $result_source)
				$num_visites_all_correct = '('.$result_source.')';
			else
				$num_visites_all_correct = '';
		}
		if($this->moderator)
			$is_moderator = 1;
		else
			$is_moderator = 0;
						
		$content_article = $article->content;
		$headings = $article->heading_name_russian;
			
		$this->template->assign('activity',2);
		$this->template->assign('id',$article->id);
        if($article->description)
            $this->site_description = $article->description;
        else
		    $this->site_description = $article->annotation;
        if($article->keywords)
            $this->site_keywords = $article->keywords;
				
		$data = array(
		'header' => $article->header,
		'data_published' => $article->data_published,
		'data_saved' => $article->data_saved,
		'content' => $content_article,
		'is_moderator' => $is_moderator,
		'is_publish' => 1,
		'id' => $article->id,
		'headings' => $headings,
		'headings_url' => $article->heading_name,
		'heading_name_russian' => $article->heading_name_russian,
		'activity' => 2,
		'username' => $article->username,
		'rating' => $article->rating,
		'main_language' => $this->main_language
		);
		
		if($advert_blocks){
			$data['advert_over_content'] = $advert_blocks['over_content'];
			$data['advert_under_content'] = $advert_blocks['under_content'];
			$data['advert_under_image'] = $advert_blocks['under_image'];
		}
		
		if(isset($num_visites_all)){
			$data['num_visites_all'] = $num_visites_all;
			$data['num_visites_all_correct'] = $num_visites_all_correct;
			$data['num_visites_avg'] = $num_visites_avg;
			$data['num_visites_avg_correct'] = $num_visites_avg_correct;

		}
			
		$author_profile = $this->articler_model->get_author_profile($article->user_id);
		if($author_profile){
			$data['author_name'] = $author_profile->name;
			$data['author_family'] = $author_profile->family;
		}
		
		if($this->login)
			$data['author'] = 'user';
		else
			$data['author'] = 'guest';
		
		if(isset($article->image) && $article->image)
			$data['picture'] = $article->image;
		$content = $this->template->view($this->theme_name.'/article.tpl',$data,true);
		
		$this->template->assign('site_title',$article->header);
		$this->site_title = $article->header;
		
		else:
		
		$this->action404();
		
		endif;
		
		else:
		
		$id = $uriSegments[3];
		
		if($this->access && $this->articler_model->is_author_article($id)){
		
		$buffer = $this->articler_model->get_article_by_id($id);
        if($buffer->description)
            $this->site_description = $buffer->description;
        else
            $this->site_description = $buffer->annotation;
        if($buffer->keywords)
            $this->site_keywords = $buffer->keywords;
			
			$data = array(
			'header' => $buffer->header,
			'data_published' => $buffer->data_published,
			'data_saved' => $buffer->data_saved,
			'content' => $buffer->content,
			'id' => $buffer->id,
			'headings' => $buffer->heading_name_russian,
			'headings_url' => $article->heading_name,
			'activity' => $buffer->activity,
			'rating' => $buffer->rating,
			'username' => $buffer->username,
			'main_language' => $this->main_language
		);
		$author_profile = $this->articler_model->get_author_profile($buffer->user_id);
		if($author_profile){
			$data['author_name'] = $author_profile->name;
			$data['author_family'] = $author_profile->family;

		}
		if(isset($buffer->image) && $buffer->image)
			$data['picture'] = $buffer->image;
		$content = $this->template->view($this->theme_name.'/article.tpl',$data,true);	
		$this->template->assign('site_title',$buffer->header);
		$this->site_title = $buffer->header;
			
		}
		elseif($this->moderator && $uriSegments[1] == 'moderate'){
			
			$buffer = $this->articler_model->get_article_by_id($id,'*','AND articles.activity = 1');
			$this->site_description = $buffer->annotation;
			$author_profile = $this->articler_model->get_author_profile($buffer->user_id);
			
			$data = array(
			'header' => $buffer->header,
			'data_published' => $buffer->data_published,
			'data_saved' => $buffer->data_saved,
			'content' => $buffer->content,
			'id' => $buffer->id,
			'headings' => $buffer->heading_name_russian,
			'activity' => $buffer->activity,
			'is_moderator' => 1,
			'heading_id' => $buffer->heading_id,
			'headings_url' => $buffer->heading_name,
			'show' => 1,
			'rating' => $buffer->rating,
			'username' => $buffer->username,
			'main_language' => $this->main_language
		);
		
			if($author_profile){
				$data['author_name'] = $author_profile->name;
				$data['author_family'] = $author_profile->family;

			}
		if(isset($buffer->image) && $buffer->image)
			$data['picture'] = $buffer->image;
			
		$content = $this->template->view($this->theme_name.'/article.tpl',$data,true);
		
		$this->template->assign('site_title',$buffer->header);
		$this->site_title = $buffer->header;
		}
		
		else{
			$data = array(
			'message' => lang('access_to_draft_only_author')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		endif;	
		if($article){			
			$content.=$this->get_comments($article);
		}
		$this->display_layout($content);

	}
	
	
	public function translit(){
		
		if($_REQUEST['type'] == 'ajax'){
		$this->load->helper('translit');
		$str = $this->input->post('str');
		echo translit_url($str);
		
		}
		else{
			redirect(site_url(''));
		}
		
	}
	
	public function list_comments(){
		
		$this->template->assign('site_title',lang('comments_list'));
		$this->site_title = lang('comments_list');
		$uriSegments = $this->uri->segment_array();
		if(count($uriSegments) == 2)
			$cur_page = $uriSegments[2];
		else
			$cur_page = 0;
			
		$comments = $this->articler_model->list_comments(null, "$cur_page,$this->per_page");
		$config['base_url'] = site_url('list_comments');
		$config['total_rows'] = $this->articler_model->get_num_article_comments_by_user_id($this->user_id);
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		if(empty($_REQUEST['offset']))
			$config['uri_segment'] = 2;
		else
			$config['cur_page'] = $_REQUEST['offset'];
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();				
		$data = array(
		'comments' => $comments,
		'login' => ucfirst($this->login),
		'THEME' => $this->THEME,
		'paginator' => $paginator,
		'cur_page' => $cur_page,
		'main_language' => $this->main_language
		);
		
		$content = $this->template->view($this->theme_name.'/list_comments.tpl',$data,true);
		$this->display_layout($content);
			
	}
	
	protected function show_list_articles($insert = false, $res = false, $do = null, $id = 0, $last_id = false){
	
		$list_materials = lang('list_materials');
		$list_materials = str_replace('%login%',ucfirst($this->login),$list_materials);
		$this->template->assign('site_title',$list_materials);
		$this->site_title = $list_materials;
		$uriSegments = $this->uri->segment_array();
		if(count($uriSegments) == 2)
			$cur_page = $uriSegments[2];
		else
			$cur_page = 0;
		if(isset($_REQUEST['cur_page']))
			$cur_page = $_REQUEST['cur_page'];

		if($insert == true && $res == false)
			return 	$this->template->view($this->theme_name.'/error.tpl',array('error_text' => lang('publish_error')),true);
		
		if($insert == true && $res == true){
			$is_added = 1;
			$article = $this->articler_model->get_article_by_id($id, 'header');
			
			if($do == 'update'){
				$message = lang('article_changed');
				$message = str_replace('%header%',$article->header,$message);
				$action = 'update';
			}
			else{
				$message = lang('article_added');
				$message = str_replace('%header%',$article->header,$message);
				$action = 'insert';
			}
				
			$is_added = 1;
		
		}
		else{
			$is_added = 0;
			$message = '';
			$action = 'show';
		}
		
		
		$articles = $this->articler_model->list_articles(null, "$cur_page,$this->per_page");
		$config['base_url'] = site_url('list_articles');
		$config['total_rows'] = $this->articler_model->get_num_articles_by_user_id($this->user_id, 'all');
		$config['per_page'] = $this->per_page;
		$config['use_page_numbers'] = TRUE;
		if(empty($_REQUEST['offset']))
			$config['uri_segment'] = 2;
		else
			$config['cur_page'] = $_REQUEST['offset'];
		$this->pagination->initialize($config);
		$paginator = $this->pagination->create_links();
//		var_dump($articles);exit;
		$data = array(
		'articles' => $articles,
		'login' => ucfirst($this->login),
		'message' => $message,
		'THEME' => $this->THEME,
		'paginator' => $paginator,
		'cur_page' => $cur_page,
		'is_added' => $is_added,
		'article_id' => $id,
		'action' => $action,
		'main_language' => $this->main_language
		);
		
		if($uriSegments[1] == 'list_articles')
			$data['is_self'] = true;
		else
			$data['is_self'] = false;
		
		if(isset($article))
			$data['article_header'] = $article->header;
			
		
		return $this->template->view($this->theme_name.'/list_articles.tpl',$data,true);

	}
	
	
	
	public function list_articles($do = null, $id =null){
		
		if($this->access){
			$content = $this->show_list_articles();
			
		}
		else{
			$data = array(
			'message' => lang('access_only_for_registered_users')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		
		$this->display_layout($content);

	}
	
	public function private_add(){

		$access = false;
		if($this->moderator && !$this->editor)
			$access = true;
		if(!$access){
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		else{
			$this->site_title = lang('new_material_for_private');
			$this->template->assign('site_title',$this->site_title);
			$this->CKEditor = new CKEditor('/ckeditor/');
			ob_start();
			$this->CKEditor->editor("editor",'', array('language' => substr($this->main_language,0,2))); 
			$editor=ob_get_contents();
			ob_clean();
			$data = array(
			'editor' => $editor,
			'header' => '',
			'url' => '',
			'annotation' => '',
			'main_language' => $this->main_language
			);
			$content = $this->template->view($this->theme_name.'/private_article_add.tpl',$data,true);
			
		}	
		
		$this->display_layout($content);

		
	}
	
	public function private_update(){
	
		$uriSegments = $this->uri->segment_array();	

		$id = $uriSegments[3];
		$access = false;
		if($id == 0)
			redirect(site_url('private/list_articles'));
		if($this->moderator && !$this->editor)
			$access = true;
		
		if(!$access){
			$data = array(
			'message' => lang('access_only_for_registered_users')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		else{
			$query = $this->db->get_where('private_articles',array('id' => $id));
			$site_title = lang('editing_article');
			$site_title = str_replace('%header%',$query->row()->header,$site_title);
			$this->site_title = $site_title;	
			$this->template->assign('site_title',$this->site_title);
			
			$this->CKEditor = new CKEditor('/ckeditor/');
			ob_start();
			$this->CKEditor->editor("editor",$query->row()->content,array('language' => substr($this->main_language,0,2))); 
			$editor=ob_get_contents();
			ob_clean();
			$data = array(
			'editor' => $editor,
			'header' => $query->row()->header,
			'url' => $query->row()->url,
			'annotation' => $query->row()->annotation,
			'article_id' => $query->row()->id,
			'is_update' => 1,
			'main_language' => $this->main_language
			);
			$content = $this->template->view($this->theme_name.'/private_article_add.tpl',$data,true);
	
		}
			
		$this->display_layout($content);
	
	}
	
	public function private_article(){
		
		$uriSegments = $this->uri->segment_array();	
		if($this->access || $this->moderator || $this->editor){
			$article = $this->articler_model->get_private_article($uriSegments[2]);
			$this->site_title = $article->header;
			$this->template->assign('site_title',$this->site_title);
			$data = array(
			'id' => $article->id,
			'content' => $article->content,
			'main_language' => $this->main_language
			);
			if($this->moderator && !$this->editor)
				$data['is_moderator'] = 1;
			$content = $this->template->view($this->theme_name.'/private_article.tpl',$data,true);
			
			$content.=$this->get_comments($article,true);

		}
		else{
			$_SESSION['private_url'] = site_url('private/'.$uriSegments[2]);
			$data = array(
			'message' => lang('access_only_for_registered_users')
			);	
			redirect(site_url('auth/login'));
			//$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
			
		}
		
			
			$this->display_layout($content);

	}
	
	
	
	public function private_list_articles(){
			
		$access = false;
		$uriSegments = $this->uri->segment_array();	
		if(count($uriSegments) == 3)
			$cur_page = $uriSegments[3];
		else
			$cur_page = 0;
		if($this->moderator && !$this->editor)
			$access = true;
		if(!$access){
			$data = array(
			'message' => lang('rights_on_the_section_only_moderator')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		}
		else{
			$data = array(
			'THEME' => $this->THEME
			);
			if(isset($_POST['submit'])){
				
				if(isset($_POST['is_update'])){
					if(!$this->articler_model->check_private_url($this->input->post('url'),$this->input->post('article_id'))){
						$this->CKEditor = new CKEditor('/ckeditor/');
						ob_start();
						$this->CKEditor->editor("editor",$_POST['editor'],array('language' => substr($this->main_language,0,2))); 
						$editor=ob_get_contents();
						ob_clean();
						$data = array(
						'header' => $_POST['header'],
						'url' => $_POST['url'],
						'annotation' => $_POST['annotation'],
						'editor' => $editor,
						'message' => lang('url_material_not_unique'),
						'main_language' => $this->main_language
						);
						$content = $this->template->view($this->theme_name.'/private_article_add.tpl',$data,true);
						$this->display_layout($content);
						exit;
					}
					else{
						$res = $this->articler_model->update_private_article($_POST['article_id']);
						if($res)
						$data['message'] = lang('private_material_edited');
					}
					
					
				}
				else{
					if(!$this->articler_model->check_private_url($this->input->post('url'))){
						$this->CKEditor = new CKEditor('/ckeditor/');
						ob_start();
						$this->CKEditor->editor("editor",$_POST['editor'],array('language' => substr($this->main_language,0,2))); 
						$editor=ob_get_contents();
						ob_clean();
						$data = array(
						'header' => $_POST['header'],
						'url' => $_POST['url'],
						'annotation' => $_POST['annotation'],
						'editor' => $editor,
						'message' => lang('url_material_not_unique'),
						);
						$content = $this->template->view($this->theme_name.'/private_article_add.tpl',$data,true);
						$this->display_layout($content);
						exit;
					}
					else{
						$res = $this->articler_model->add_private_article();
						if($res)
						$data['message'] = lang('private_material_added');
					}
					
				}
				
			}
			$articles = $this->articler_model->get_private_articles("$cur_page, $this->per_page");
			$config['base_url'] = site_url($uriSegments[2]);

			$config['total_rows'] = $this->db->count_all('private_articles');
			$config['per_page'] = $this->per_page;
			$config['use_page_numbers'] = TRUE;
			$this->pagination->initialize($config);
			$paginator = $this->pagination->create_links();
			$data['paginator'] = $paginator;
			$data['articles'] = $articles;
		
			$this->site_title = 'Список приватных материалов';
			$this->template->assign('site_title', $this->site_title);
			$this->template->assign('is_main', 1);
			
			$content = $this->template->view($this->theme_name.'/private_articles.tpl',$data,true);;
		}
		
		
		$this->display_layout($content);
	}
	
	protected function preparePublish($id = 0, $errors = array()){
						
		$this->template->assign('is_editor',1);
		if($id != 0){
			$buffer = $this->articler_model->get_article_by_id($id);
			$content = $buffer->content;
			$annotation = $buffer->annotation;
			$description = $buffer->description;
			$keywords = $buffer->keywords;
			$page_header = lang('editing_the_material');
			$page_header = str_replace('%material%',$buffer->header,$page_header);
			$selected = $buffer->heading_id;
			$header = $buffer->header;
			$buffer_url = $buffer->url;
			$temp = explode('/',$buffer_url);
			$url_heading = $temp[1];
			$url_article = $temp[2];
			$arr_article = explode('.',$url_article);
			$url = $arr_article[0];
			$type = 'update';
			
		}
		else{
			$content = '';
			$annotation = '';
			$description = '';
			$keywords = '';
			$this->template->assign('site_title',lang('add_material'));
			$this->site_title = lang('add_material');
			$page_header = $this->site_title;
			if(isset($_REQUEST['headings']))
				$selected = $_REQUEST['headings'];
			else
				$selected = 0;
			if(isset($_REQUEST['header']))
				$header = $_REQUEST['header'];
			else
				$header = '';
			if(isset($_REQUEST['url']))
				$url = $_REQUEST['url'];
			else
				$url = '';
			$type = 'insert';

		}
		
		$this->template->assign('site_title',$page_header);
		$this->site_title = $page_header;	
			
		$this->CKEditor = new CKEditor('/ckeditor/');
		ob_start();
		$this->CKEditor->editor("editor",$content, array('language' => substr($this->main_language,0,2))); 
		$editor=ob_get_contents();
		ob_clean();
		
		$options[0] = '----------';
		foreach($this->all_headings as $heading){
			$options[$heading['id']] = $heading['name_russian'];
		}
			
		
		$data = array(
				'editor' => $editor,
				'annotation' => $annotation,
				'description' => $description,
				'keywords' => $keywords,
				'headings' => form_dropdown('headings',$options,$selected,'id="headings"'),
				'id_article' => $id,
				'page_header' => $page_header,
				'url' => $url,
				'header' => $header,
				'errors' => $errors,
				'id' => $id,
				'is_edited' => 1,
				'type' => $type,
				'main_language' => $this->main_language
		);
		
		if($id != 0){
			$outer_link = $this->articler_model->get_outer_link($id, 'link');
			if($outer_link)
				$data['outer_link'] = $outer_link;
		}	
		
		$result = $this->template->view($this->theme_name.'/publisher.tpl',$data,true);
		return $result;
		
	}
	
	public function delete(){
	
			$uriSegments = $this->uri->segment_array();
			$id = $uriSegments[3];
		
		if($this->access){
			
			if($id != 0):
			$res = $this->articler_model->delete_article($id);
			
			if(isset($res['errors'])){
				$response['answer'] = 0;
				$response['content'] = $res['errors']['activity'];
			}
			else{
				$response['content'] = $this->show_list_articles();
				$response['answer'] = 1;
			}
			
			endif;
			
			if($_POST['type'] == 'ajax'){
				echo json_encode($response);

			}
			else{
				$this->display_layout($response['content']);
			}
			
		}
	}
	
	public function add(){
		
			$this->operation = 'add';
			$uriSegments = $this->uri->segment_array();
			$id = $uriSegments[3];
			
			if($this->access):
		
		if($id != 0):
			$res = $this->articler_model->send_moderate($id);
			
			if(isset($res['errors'])){
				$response['answer'] = 0;
				$response['content'] = $res['errors']['moderate'];
			}
			else{
				$response['content'] = $this->show_list_articles();
				$response['answer'] = 1;
			}
			
			endif;
			
			if($_POST['type'] == 'ajax'){
				echo json_encode($response);

			}
			else{
				$this->display_layout($response['content']);
			}
			
			endif;
			
		}
	
	
		
	public function all_comments(){
	
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[2];
		
		
		if($id != 0):
		
		$begin = $this->input->post('next_elem');
		$num_comments = $this->articler_model->get_num_comments_by_article_id($id);
		$offset = $num_comments - $begin;
		$query = $this->articler_model->more_comments($id,"$begin,$offset");
				
		$comments = $query->result_array();

		foreach($comments as $key=>$value){
			if($value['avatar_userid']){
				$comments[$key]['avatar'] = '<img width="'.$value['small_width'].'" height="'.$value['small_height'].'" src="/uploads/images/avatars/'.$value['avatar_userid'].'" />';
			}
			else
				$comments[$key]['avatar'] = '';
		}
		foreach($comments as $key=>$value){
			if($value['fullname'])
				$comments[$key]['author'] = '<a style="color:black;text-decoration:underline;" target="_blank" href="/avtory/'.$value['username'].'"><b>'.$value['fullname'].'</b></a>';
			else
				$comments[$key]['author'] = '<a style="color:black;text-decoration:underline;" target="_blank" href="/avtory/'.$value['username'].'"><b>'.$value['username'].'</b></a>';
		}
		
		if($query){
			$response['answer'] = 1;
			$response['num_comments'] = $num_comments;
			$data = array(
			'all_comments' => 1,
			'comments' => $comments,
			'user_id' => $this->user_id,
			'article_owner_user_id' => $this->articler_model->get_article_owner_user_id('id', $id),
			'main_language' => $this->main_language
			);
			$response['content'] = $this->template->view($this->theme_name.'/add_comment.tpl',$data,true);
		}
		else{
			$response['answer'] = 0;
			$response['content'] = lang('unknown_error');
		}
		
		echo json_encode($response);
		
		endif;
		
		
		
	}
		
		
	public function cancel(){
		
			$this->operation = 'cancel';
			$uriSegments = $this->uri->segment_array();
			$id = $uriSegments[3];
			
			if($this->access):
			
		if($id != 0):
			$res = $this->articler_model->cancel_moderate($id);
			
			if(isset($res['errors'])){
				$response['answer'] = 0;
				$response['content'] = $res['errors']['moderate'];
			}
			else{
				$response['content'] = $this->show_list_articles();
				$response['answer'] = 1;
			}
			
			endif;
			
			if($_POST['type'] == 'ajax'){
				echo json_encode($response);

			}
			else{
				$this->display_layout($response['content']);
			}
			
			endif;
			
		}
		
	public function check(){
		
		
		$uriSegments = $this->uri->segment_array();
		$id = $uriSegments[3];
		
		if($this->access):
		
		$article = $this->articler_model->get_article_by_id($id,'activity');
		if($article->activity == 2){
			$response['answer'] = 0;
			$response['content'] = lang('editing_published_article_not_allowed');
		}
		elseif($this->articler_model->is_blocked($id)){
			$response['answer'] = 0;
			$response['content'] = lang('editing_moderate_articles_not_allowed');
		}
		else{
			$response['answer'] = 1;
		}
		
		if($_POST['type'] == 'ajax'){
				echo json_encode($response);

			}


		endif;
		
	}
	
	
	public function booking(){
		if($this->access){
			$article_id = 0;
			if($this->articler_model->booking($this->user_id,$article_id)){
				redirect(site_url('publish/update').'/'.$article_id);
			}
		}
		else{
			redirect(site_url('auth/login'));
		}
		$this->display_layout($content);
	}
	
	public function transit(){
		
		if($this->access){
			$this->template->assign('site_title',lang('preliminary_selection'));
			$this->site_title = lang('preliminary_selection');	
			$buffer_giventopics = $this->articler_model->get_giventopics();
			$arr = array();
			foreach($buffer_giventopics as $elem){
				$arr[$elem['id']] = $elem['header'];
			}
			$data = array(
			'giventopics' => form_dropdown('giventopics',$arr,'','id="giventopics"'),
			'THEME' => $this->THEME,
			'num_topics' =>count($arr)
			);
			
			$content = $this->template->view($this->theme_name.'/transit.tpl',$data,true);

		}
		else{
			redirect(site_url('auth/login'));
		}
		
		$this->display_layout($content);
		
	}
	
	public function publish($do = null){
	
		$uriSegments = $this->uri->segment_array();
		if($this->main_language == 'russian')
			$redirect_profile = 'avtor/profile/set_name';
		else
			$redirect_profile = 'author/profile/set_name';

		
		if($do != null){
			$id = $uriSegments[3];
		}
		else{
			$id = 0;
		}
		
		
		if($do == 'update' && $id !=0){
			$article = $this->articler_model->get_article_by_id($id);
		}
		
		if($this->access && !$this->articler_model->get_author_profile($this->user_id))
			redirect($redirect_profile);
		 
		if($this->access && ($this->user_rating_activity >= $this->rating_for_publish || $do == 'update')){
			if($id != 0){
				$do = $uriSegments[2];
				
			}
				
			if(isset($_REQUEST['submit'])){
				if($id == 0){
						$res = $this->articler_model->insert_article();
						$id = $this->db->insert_id();
					}
					else{
						$res = $this->articler_model->update_article($id);
					}

					$insert = true;
					
			
				if($insert == true && empty($res['errors'])){
					$content = $this->show_list_articles($insert, $res, $do, $id, true);
				}
				else{
					$content = $this->preparePublish($id,$res['errors']);
				}
			}			
			else{
				$content = $this->preparePublish($id);
			}
			
				
		}
		elseif($this->access && $this->user_rating_activity < $this->rating_for_publish && $do != 'update'){
		
			$data = array(
			'message' => lang('addition_material_positive_rating_activity')
			);
				
			$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
		
		}
		else{
			
			redirect(site_url('auth/login'));
			
		}
			
			
				if($this->articler_model->get_activity_by_article_id($id) == 2){
					$data = array(
					'message' => lang('editing_published_article_not_allowed')
					);
				
					$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
				}
				elseif($this->articler_model->is_blocked($id)){
					$data = array(
					'message' => lang('editing_moderate_articles_not_allowed')
					);
				
					$content = $this->template->view($this->theme_name.'/denied.tpl',$data,true);
				}
					
			
				$this->display_layout($content);

	}
	
	
	
	public function ajaxupload()
{

	$error = "";
	$msg = "";
	$uploaded = '0';
	$content = '';
	$fileElementName = 'avatar_file';
	$host = site_url('');

	if($this->access):
	$file_name = $_FILES[$fileElementName]['name'];
	$file_name_tmp = $_FILES[$fileElementName]['tmp_name'];

	$file_new_name = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/avatars/'.$this->login.'.jpg'; 
	$full_path = $file_new_name.$file_name;


	$http_path = '/uploads/images/publications/'.$this->login.'/'.$file_name; // адрес изображения для обращения через http
	
	if( move_uploaded_file($file_name_tmp, $full_new_name) )
	{
	// можно добавить код при успешном выполнение загрузки файла
		$msg = lang('message_ok');
	} else
	{
	$error = lang('error_try_again'); // эта ошибка появится в браузере если скрипт не смог загрузить файл
	$http_path = '';
	}
	echo "<script type=\"text/javascript\">// <![CDATA[
	window.parent.CKEDITOR.tools.callFunction(".$callback.",  \"".$http_path."\", \"".$error."\" );
	// ]]></script>";
	
	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "',\n";
	echo				"uploaded: '" . $uploaded . "',\n";
	echo				"content: '" . $content . "'\n";
	echo "}";
	
	endif;
}
	
	
	public function ckupload()
{

	if($this->access || $this->moderator):
	$callback = $_GET['CKEditorFuncNum'];
	$file_name = $_FILES['upload']['name'];
	$file_name_tmp = $_FILES['upload']['tmp_name'];
	if($this->moderator)
		$login = $_SESSION['user_login'];
	else
		$login = $this->login;
	$upload_dir = date('Y').'/'.date('m');
	if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.date('Y'))){
		mkdir($_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.date('Y'));
	}
	
	if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.$upload_dir)){
		mkdir($_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.$upload_dir);
	}
		
	$file_new_name = $_SERVER['DOCUMENT_ROOT'].'/uploads/images/publications/'.$upload_dir.'/'; 
	
	
	if($this->moderator)
		$hash_picture = 'private_'.date('d').date('h').date('i');
	else
		$hash_picture = date('d').date('h').date('i').'_'.$this->user_id;
		
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
	}
	 else
	{
	$error = lang('error_try_again'); // эта ошибка появится в браузере если скрипт не смог загрузить файл
	$http_path = '';
	}
	echo "<script type=\"text/javascript\">// <![CDATA[
	window.parent.CKEDITOR.tools.callFunction(".$callback.",  \"".$http_path."\", \"".$error."\" );
	// ]]></script>";
	
	endif;
}
	
	public function action404(){
	
		$content = $this->template->view($this->theme_name.'/404.tpl',array('error_text' => lang('articler_404')),true);
		$this->template->assign('site_title', lang('articler_404'));
		header("HTTP/1.0 404 Not Found");
		$this->display_layout($content);

	}
	
	private function address_tpl($file = ''){
	
		 $file = $_SERVER['DOCUMENT_ROOT'].'/application/modules/articler/templates/'.$file.'.tpl';
		 return $file;
	}
	
		

	private function display_layout($content){
		 
		 $content = str_replace('?>','',$content);
		 if(!$this->site_keywords)
		 	$this->template->assign('site_keywords', $this->settings['site_keywords']);
		 else
		 	$this->template->assign('site_keywords', $this->settings['site_keywords'].', '.$this->site_keywords);

		if(!$this->site_description)
			$this->template->assign('site_description', $this->settings['site_description']);
		else
			$this->template->assign('site_description', $this->site_description);
			

		 
		 if($_REQUEST['type'] == 'ajax'){

		 	$response['content'] = $content;
			$response['title'] = trim($this->site_title);
			echo json_encode($response);
		 }
		 else{
		 	 $this->template->assign('content',$content);
			 $file = $_SERVER['DOCUMENT_ROOT'].'/'.$this->THEME.'/'.$this->layout.'.tpl';
		 	 $this->template->display('file:'.$file);
		 }
		
	}
	
	
}

?>