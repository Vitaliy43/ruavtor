<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller{
	
	protected $author_fields = array(
	'username' => 'Логин',
	'family' => 'Фамилия Имя',
	'author_rating' => 'Авторский рейтинг',
	'num_articles' => 'Кол-во статей',
	'sum_statistic' => 'Кол-во просмотров'
	);
	

	function __construct()
	{
		parent::__construct();

        $this->load->library('DX_Auth');
        if( $this->dx_auth->is_admin() == FALSE) exit;
		$this->load->model('articler/settings_model');
		$this->load->model('articler/statistic_model');
		$this->load->model('articler/articler_model');
	
	}
	
	
	function index()
	{
		$this->set_tpl_roles();
		$this->display_tpl('main');
	}

	/*
	 * Assign template roles
	 */
	function set_tpl_roles()
	{
		// roles
		$query = $this->db->get('roles');
		$this->template->assign('roles',$query->result_array());
		// roles
	}
	
	// Template functions
	private function display_tpl($file)
	{
        $file =  realpath(dirname(__FILE__)).'/templates/'.$file.'.tpl';  
		$this->template->display('file:'.$file);
	}

	private function fetch_tpl($file)
	{
        $file =  realpath(dirname(__FILE__)).'/templates/'.$file.'.tpl';  
		return $this->template->fetch('file:'.$file);
	}
	
	function edit_author($user_id)
	{
        cp_check_perm('user_edit');
		$this->load->model('dx_auth/users', 'users');
		$this->load->model('dx_auth/articler_users', 'articler_users');

		$author = $this->articler_users->get_author($user_id,$this->statistic_model->repository_statistic.'.statistic_day');
		if(!$author)
		{
			exit(lang('amt_users_not_found'));
		}else{
			$this->template->assign('author',$author);
			$this->set_tpl_roles();
			$this->display_tpl('edit_author');
		}
	}
	
	function update_author($user_id)
	{
		cp_check_perm('user_edit');
		$this->load->model('dx_auth/users', 'users');
		$this->load->model('dx_auth/articler_users', 'articler_users');
		$author_rating = $this->input->post('author_rating');
		$rating_activity = $this->input->post('rating_activity');
		$payments = $this->input->post('payments');
		$payouts = $this->input->post('payouts');
//		$corrective_q = $this->input->post('corrective_q');
		
		if($author_rating < 0){
			showMessage('Значение поля "Авторский рейтинг" не может быть меньше 0!');
			exit;
		}
		
		elseif($rating_activity < $this->articler_model->lowest_rating_activity){
			showMessage('Значение поля "Авторский рейтинг" не может быть меньше установленного минимального значения рейтинга активности!');
			exit;
		}
		
		elseif($rating_activity > $this->articler_model->highest_rating_activity){
			showMessage('Значение поля "Авторский рейтинг" не может быть больше установленного максимального значения рейтинга активности!');
			exit;
		}
		elseif(isset($_POST['payments']) && $payments < 0){
			showMessage('Значение поля "'.lang('amt_edit_payments').'" не может быть меньше 0!');
			exit;
		}
		elseif(isset($_POST['payouts']) && $payouts < 0){
			showMessage('Значение поля "'.lang('amt_edit_payouts').'" не может быть меньше 0!');
			exit;
		}
//		elseif(isset($_POST['corrective_q']) && $corrective_q < 0){
//			showMessage('Значение поля "Поправочный коэффициент" не может быть меньше 0!');
//			exit;
//		}
		else{
			
			$res = $this->articler_users->update_author($user_id);
			
		}
		
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по данному автору успешно обновлены');
		}	
	}

	function rating_activity()
	{
		$activity_day = $this->input->post('activity_day');
		$unactivity_day = $this->input->post('unactivity_day');
		
		if($activity_day <= 0){
			showMessage('Значение поля "Увеличение активности" не может равняться 0 или быть меньше 0');
			exit;
		}
		
		elseif($unactivity_day <= 0){
			showMessage('Значение поля "Уменьшение активности" не может равняться 0 или быть меньше 0');
			exit;
		}
		else{
			$res = $this->settings_model->set_setting('rating_activity','activity_day',$activity_day);
			$res = $this->settings_model->set_setting('rating_activity','unactivity_day',$unactivity_day);
			$res = $this->settings_model->set_setting('rating_activity','lowest_rating',$this->input->post('lowest_rating'));
			$res = $this->settings_model->set_setting('rating_activity','highest_rating',$this->input->post('highest_rating'));
		}
		
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по рейтингу активности успешно обновлены');
		}	
		
	}
	
	
	function list_authors($sort = null)
	{
		cp_check_perm('user_view_data');

		$this->load->model('dx_auth/users', 'users');
		$this->load->model('dx_auth/articler_users');
		$this->load->library('pagination');
		$uriSegments = $this->uri->segment_array();
		$is_number = preg_match('#\d+#', $this->uri->segment(6), $matches);

		if($is_number)
			$offset = (int) $this->uri->segment(6);
		else
			$offset = (int) $this->uri->segment(7);
			
		$row_count = 50;
		if($is_number)
			$authors = $this->articler_users->get_authors("$offset,$row_count", null,$this->statistic_model->repository_statistic.'.statistic_day');
		else
			$authors = $this->articler_users->get_authors("$offset,$row_count", $uriSegments[6],$this->statistic_model->repository_statistic.'.statistic_day');
		for($i = 0; $i < count($authors); $i++){
			$authors[$i]['sum_payouts'] = $this->articler_model->get_sum_payout_by_user_id($authors[$i]['id']);	
			$authors[$i]['sum_payments'] = $this->articler_model->get_sum_payment_by_user_id($authors[$i]['id']);	
//			$authors[$i]['score'] = round($authors[$i]['score'] * $this->articler_model->pay_for_visit);	
				
		}


		// Begin pagination
		if($is_number){
			$config['base_url'] = site_url('admin/components/cp/articler/list_authors');
			$config['uri_segment'] = 6;

		}
		else{
			$config['base_url'] = site_url('admin/components/cp/articler/list_authors/'.$uriSegments[6]);
			$config['uri_segment'] = 7;

		}

		$config['container'] = 'users_ajax_table';
		$config['total_rows'] =  $this->articler_model->num_authors();
		$config['per_page'] = $row_count;
		$this->pagination->initialize($config);
		$this->pagination->container = 'authors_ajax_table';
		$this->template->assign('paginator',$this->pagination->create_links_ajax());
		// End pagination

		$statuses_points = $this->articler_model->get_author_statuses('points', 1);
		$statuses_articles = $this->articler_model->get_author_statuses('articles', 1);
		
		$count = count($authors);
		for($i = 0; $i < $count ; ++$i){
			$author_group = $this->articler_model->get_author_group($authors[$i]['author_rating'], $authors[$i]['num_articles'], $statuses_points, $statuses_articles);
			if($author_group)
				$authors[$i]['author_group'] = $author_group;
			else
				$authors[$i]['author_group'] = '';
		}
		if($sort)
			$select_sort = form_dropdown('sort',$this->author_fields,$sort,'id="sort" onchange="ajax_sort();"');
		else
			$select_sort = form_dropdown('sort',$this->author_fields,'username','id="sort" onchange="ajax_sort();"');

		
		$this->template->assign('select_sort',$select_sort);
		$this->template->assign('authors',$authors);
		$this->template->assign('cur_page',$offset);
		$this->template->assign('statuses', $this->articler_model->get_author_statuses());

		echo $this->fetch_tpl('list_authors');
		
	}
	
	function rating_author()
	{	
		foreach($this->settings_model->type_headings as $type_heading){
			$rating_for_homefeed[$type_heading['id']] = $this->input->post('rating_for_homefeed_'.$type_heading['id']);
		}
		$hold_for_homefeed = $this->input->post('hold_for_homefeed');
		$add_for_homefeed = $this->input->post('add_for_homefeed');
		$bonus_for_homefeed = $this->input->post('bonus_for_homefeed');
		
		foreach($rating_for_homefeed as $key=>$value){
			
			if($value <= 0){
				showMessage('Значение поля "Рейтинг для выхода из песочницы. '.$this->settings_model->brief_type_headings[$key].' рубрики." не может равняться 0 или быть меньше 0');
			exit;
			}
		}
		
		
		if($hold_for_homefeed <= 0){
			showMessage('Значение поля "Период для выхода из песочницы" не может равняться 0 или быть меньше 0');
			exit;
		}
		elseif($add_for_homefeed < 0){
			showMessage('Значение поля "Увеличение рейтинга за выход из песочницы" не может быть меньше 0');
			exit;
		}
		elseif($bonus_for_homefeed < 0){
			showMessage('Значение поля "Бонус для выхода из песочницы" не может быть меньше 0');
			exit;
		}
		
			foreach($rating_for_homefeed as $key=>$value){
				$res = $this->settings_model->set_setting('rating_author','rating_for_homefeed_'.$key, $value);

			}
			$res = $this->settings_model->set_setting('rating_author','hold_for_homefeed',$hold_for_homefeed);
			$res = $this->settings_model->set_setting('rating_author','add_for_homefeed',$add_for_homefeed);
			$res = $this->settings_model->set_setting('rating_author','bonus_for_homefeed',$bonus_for_homefeed);
			
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по авторскому рейтингу успешно обновлены');
		}
	}
	
	function comments_system()
	{
		$this->template->assign('per_page_comments', $this->settings_model->get_setting('comments_system', 'per_page_comments'));
		$this->template->assign('num_last_comments', $this->settings_model->get_setting('comments_system', 'num_last_comments'));
		$this->template->assign('add_rating_by_plus', $this->settings_model->get_setting('comments_system', 'add_rating_by_plus'));
		$this->template->assign('limit_on_add_plus', $this->settings_model->get_setting('comments_system', 'limit_on_add_plus'));
		$this->template->assign('quantity_articles_for_assess', $this->settings_model->get_setting('comments_system', 'quantity_articles_for_assess'));
		
		
		echo $this->fetch_tpl('comments_system');
	}
	
	function statistic_settings()
	{
		
		$this->template->assign('use_limit_visites', $this->settings_model->get_setting('statistic_settings', 'use_limit_visites'));
		$this->template->assign('limit_visites', $this->settings_model->get_setting('statistic_settings', 'limit_visites'));
		
		echo $this->fetch_tpl('statistic_settings');
	}
	
	function interface_settings()
	{
		$buffer_language = $this->settings_model->get_setting('interface_settings', 'interface_language');
		$languages_box = form_dropdown('interface_language', $this->using_languages,$buffer_language['value']);
		$this->template->assign('languages_box', $languages_box);
		$this->template->assign('interface_language', $buffer_language);
		$this->template->assign('per_page_elements', $this->settings_model->get_setting('interface_settings', 'per_page_elements'));
		$this->template->assign('num_last_articles_sandbox', $this->settings_model->get_setting('interface_settings', 'num_last_articles_sandbox'));
		$this->template->assign('modal_box_for_user', $this->settings_model->get_setting('interface_settings', 'modal_box_for_user'));
		$this->template->assign('modal_box_for_guest', $this->settings_model->get_setting('interface_settings', 'modal_box_for_guest'));
		
		echo $this->fetch_tpl('interface_settings');

	}
	
	function submit_statistic_settings()
	{
		$limit_visites = $this->input->post('limit_visites');
		
		if($limit_visites < 0){
			showMessage('Значение поля "Лимит посещений, сверх которого статистика не учитывается" быть меньше 0');
			exit;
		}
		
		else{
			$res = $this->settings_model->set_setting('statistic_settings','limit_visites',$limit_visites);
			if(isset($_POST['use_limit_visites']))
				$res = $this->settings_model->set_setting('statistic_settings','use_limit_visites','1');
			else
				$res = $this->settings_model->set_setting('statistic_settings','use_limit_visites','0');

		}
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по настройкам статистики успешно обновлены');
		}
	}
	
	function submit_interface_settings()
	{
		$interface_language = $this->input->post('interface_language');
		$per_page_elements = $this->input->post('per_page_elements');
		$num_last_articles_sandbox = $this->input->post('num_last_articles_sandbox');
		
		if($per_page_elements <= 0){
			showMessage('Значение поля "Кол-во элементов, выводимых на странице" не может равняться 0 или быть меньше 0');
			exit;
		}
		elseif($num_last_articles_sandbox <= 0){
			showMessage('Значение поля "Блок последние статьи в песочнице, кол-во ссылок" не может равняться 0 или быть меньше 0');
			exit;
		}
	
		else{
			$res = $this->settings_model->set_setting('interface_settings','interface_language',$interface_language);
			$res = $this->settings_model->set_setting('interface_settings','per_page_elements',$per_page_elements);
			$res = $this->settings_model->set_setting('interface_settings','num_last_articles_sandbox',$num_last_articles_sandbox);
			if(isset($_POST['modal_box_for_user']))
				$res = $this->settings_model->set_setting('interface_settings','modal_box_for_user',1);
			else
				$res = $this->settings_model->set_setting('interface_settings','modal_box_for_user',0);
				
			if(isset($_POST['modal_box_for_guest']))
				$res = $this->settings_model->set_setting('interface_settings','modal_box_for_guest',1);
			else
				$res = $this->settings_model->set_setting('interface_settings','modal_box_for_guest',0);
		}
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по настройкам интнрфейсов успешно обновлены');
		}
	}
	
	function comments_settings()
	{
		$per_page_comments = $this->input->post('per_page_comments');
		$add_rating_by_plus = $this->input->post('add_rating_by_plus');
		$num_last_comments = $this->input->post('num_last_comments');
		$limit_on_add_plus = $this->input->post('limit_on_add_plus');
		$quantity_articles_for_assess = $this->input->post('quantity_articles_for_assess');
		
		if($per_page_comments <= 0){
			showMessage('Значение поля "Видимое кол-во комментариев на странице статьи" не может равняться 0 или быть меньше 0');
			exit;
		}
		
		elseif($add_rating_by_plus <= 0){
			showMessage('Значение поля "Увеличение рейтинга при нажатии на плюс" не может равняться 0 или быть меньше 0');
			exit;
		}
		elseif($num_last_commments < 0){
			showMessage('Значение поля "Кол-во комментариев в блоке <<Последние комментарии>>" не может быть меньше 0');
			exit;
		}
		elseif($limit_on_add_plus < 0){
			showMessage('Значение поля "Лимит на кол-во плюсований в течении дня" не может быть меньше 0');
			exit;
		}
		elseif($quantity_articles_for_assess < 0){
			showMessage('Значение поля "Кол-во опубликованных статей, позволяющее изменять авторский рейтинг" не может быть меньше 0');
			exit;
		}
		else{
			$res = $this->settings_model->set_setting('comments_system','per_page_comments',$per_page_comments);
			$res = $this->settings_model->set_setting('comments_system','add_rating_by_plus',$add_rating_by_plus);
			$res = $this->settings_model->set_setting('comments_system','num_last_comments',$num_last_comments);
			$res = $this->settings_model->set_setting('comments_system','limit_on_add_plus',$limit_on_add_plus);
			$res = $this->settings_model->set_setting('comments_system','quantity_articles_for_assess',$quantity_articles_for_assess);
		}
		
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по настройкам почтовых уведомлений и шаблонов успешно обновлены');
		}
	}
	
	function author_groups()
	{
		$this->template->assign('minimum_num_points_publicist', $this->settings_model->get_setting('author_groups', 'minimum_num_points_publicist'));
		$this->template->assign('minimum_num_articles_publicist', $this->settings_model->get_setting('author_groups', 'minimum_num_articles_publicist'));
		$this->template->assign('minimum_num_points_journalist', $this->settings_model->get_setting('author_groups', 'minimum_num_points_journalist'));
		$this->template->assign('minimum_num_articles_journalist', $this->settings_model->get_setting('author_groups', 'minimum_num_articles_journalist'));
		$this->template->assign('minimum_num_points_expert', $this->settings_model->get_setting('author_groups', 'minimum_num_points_expert'));
		$this->template->assign('minimum_num_articles_expert', $this->settings_model->get_setting('author_groups', 'minimum_num_articles_expert'));
		
		echo $this->fetch_tpl('author_groups');

	}
	
	function submit_author_groups()
	{
		$minimum_num_points_publicist = $this->input->post('minimum_num_points_publicist');
		$minimum_num_articles_publicist = $this->input->post('minimum_num_articles_publicist');
		$minimum_num_points_journalist = $this->input->post('minimum_num_points_journalist');
		$minimum_num_articles_journalist = $this->input->post('minimum_num_articles_journalist');
		$minimum_num_points_expert = $this->input->post('minimum_num_points_expert');
		$minimum_num_articles_expert = $this->input->post('minimum_num_articles_expert');
		
		if($minimum_num_points_publicist < 0){
			showMessage('Значение поля "Минимальный авторский рейтинг" в параграфе "Публицист" не может быть меньше 0');
			exit;
		}
		
		elseif($minimum_num_articles_publicist < 0){
			showMessage('Значение поля "Минимальное кол-во статей" в параграфе "Публицист" не может быть меньше 0');
			exit;
		}
		elseif($minimum_num_points_journalist < 0){
			showMessage('Значение поля "Минимальный авторский рейтинг" в параграфе "Журналист" не может быть меньше 0');
			exit;
		}
		elseif($minimum_num_articles_journalist < 0){
			showMessage('Значение поля "Минимальное кол-во статей" в параграфе "Журналист" не может быть меньше 0');
			exit;
		}
		elseif($minimum_num_points_expert < 0){
			showMessage('Значение поля "Минимальный авторский рейтинг" в параграфе "Эксперт" не может быть меньше 0');
			exit;
		}
		elseif($minimum_num_articles_expert < 0){
			showMessage('Значение поля "Минимальное кол-во статей" в параграфе "Эксперт" не может быть меньше 0');
			exit;
		}
		else{
			$res = $this->settings_model->set_setting('author_groups','minimum_num_points_publicist',$minimum_num_points_publicist);
			$res = $this->settings_model->set_setting('author_groups','minimum_num_articles_publicist',$minimum_num_articles_publicist);
			$res = $this->settings_model->set_setting('author_groups','minimum_num_points_journalist',$minimum_num_points_journalist);
			$res = $this->settings_model->set_setting('author_groups','minimum_num_articles_journalist',$minimum_num_articles_journalist);
			$res = $this->settings_model->set_setting('author_groups','minimum_num_points_expert',$minimum_num_points_expert);
			$res = $this->settings_model->set_setting('author_groups','minimum_num_articles_expert',$minimum_num_articles_expert);
		}
		
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по группам авторов успешно обновлены');
		}
	}
	

	function finance_system()
	{
			$this->template->assign('pay_publicist', $this->settings_model->get_setting('finance_system', 'pay_publicist'));
			$this->template->assign('pay_journalist', $this->settings_model->get_setting('finance_system', 'pay_journalist'));
			$this->template->assign('pay_expert', $this->settings_model->get_setting('finance_system', 'pay_expert'));
			$this->template->assign('lowest_rating_for_pay', $this->settings_model->get_setting('finance_system', 'lowest_rating_for_pay'));
			$this->template->assign('lowest_num_articles_for_pay', $this->settings_model->get_setting('finance_system', 'lowest_num_articles_for_pay'));
			$this->template->assign('num_visites_for_pay', $this->settings_model->get_setting('finance_system', 'num_visites_for_pay'));
			$this->template->assign('pay_for_visit', $this->settings_model->get_setting('finance_system', 'pay_for_visit'));
			$this->template->assign('corrective_q', $this->settings_model->get_setting('finance_system', 'corrective_q'));
			$this->template->assign('lowest_sum_for_pay', $this->settings_model->get_setting('finance_system', 'lowest_sum_for_pay'));
					
		echo $this->fetch_tpl('finance_system');

	}
	
	function submit_finance_system()
	{
		$pay_publicist = $this->input->post('pay_publicist');
		$pay_journalist = $this->input->post('pay_journalist');
		$pay_expert = $this->input->post('pay_expert');
		$lowest_rating_for_pay = $this->input->post('lowest_rating_for_pay');
		$lowest_num_articles_for_pay = $this->input->post('lowest_num_articles_for_pay');
		$lowest_sum_for_pay = $this->input->post('lowest_sum_for_pay');
		$num_visites_for_pay = $this->input->post('num_visites_for_pay');
		$pay_for_visit = $this->input->post('pay_for_visit');
		$corrective_q = $this->input->post('corrective_q');
		
		
		if($pay_publicist < 0){
			showMessage('Значение поля "Публицист" в параграфе "Гонорары авторам" не может быть меньше 0');
			exit;
		}
		
		elseif($pay_journalist < 0){
			showMessage('Значение поля "Журналист" в параграфе "Гонорары авторам" не может быть меньше 0');
			exit;
		}
		elseif($pay_expert < 0){
			showMessage('Значение поля "Эксперт" в параграфе "Гонорары авторам" не может быть меньше 0');
			exit;
		}
		elseif($lowest_rating_for_pay < 0){
			showMessage('Значение поля "Минимальный авторский рейтинг" не может быть меньше 0');
			exit;
		}
		elseif($lowest_num_articles_for_pay < 0){
			showMessage('Значение поля "Минимальное кол-во публикаций" не может быть меньше 0');
			exit;
		}
		elseif($lowest_sum_for_pay <= 0){
			showMessage('Значение поля "Минимальная сумма для выплаты" не может быть меньше или равно 0');
			exit;
		}
		elseif($num_visites_for_pay < 0){
			showMessage('Значение поля "Минимальная кол-во визитов для выплаты" не может быть меньше 0');
			exit;
		}
		elseif($pay_for_visit < 0){
			showMessage('Значение поля "Плата за один визит" не может быть меньше 0');
			exit;
		}
		elseif($corrective_q < 0){
			showMessage('Значение поля "Корректор статистики" не может быть меньше 0');
			exit;
		}
		else{
			$res = $this->settings_model->set_setting('finance_system','pay_publicist',$pay_publicist);
			$res = $this->settings_model->set_setting('finance_system','pay_journalist',$pay_journalist);
			$res = $this->settings_model->set_setting('finance_system','pay_expert',$pay_expert);
			$res = $this->settings_model->set_setting('finance_system','lowest_rating_for_pay',$lowest_rating_for_pay);
			$res = $this->settings_model->set_setting('finance_system','lowest_num_articles_for_pay',$lowest_num_articles_for_pay);
			$res = $this->settings_model->set_setting('finance_system','lowest_sum_for_pay',$lowest_sum_for_pay);
			$res = $this->settings_model->set_setting('finance_system','num_visites_for_pay',$num_visites_for_pay);
			$res = $this->settings_model->set_setting('finance_system','pay_for_visit',$pay_for_visit);
			$res = $this->settings_model->set_setting('finance_system','corrective_q',$corrective_q);
			
		}
		
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по финансовой системе успешно обновлены');
		}
	}
	
	function submit_mail_settings()
	{
		$main_email = $this->input->post('main_email');
		$res=preg_match('/^[A-Za-z0-9]+(\.\w+)*@([A-Za-z0-9]+\w*)((\.[A-Za-z0-9]+\w*))*\.([A-Za-z0-9]){2,6}$/',$main_email);
		if(!$main_email){
			showMessage('Поле "Основной почтовый ящик для отправления сообщений" не может быть пустым!');
			exit;
		}
		
		if(!$res){
			showMessage('В поле "Основной почтовый ящик для отправления сообщений" введены некорректные значения!');
			exit;
		}
		
		if(!$this->dx_auth->is_email_available($main_email)){
			showMessage('Данный email уже занят пользователем сайта!');
			exit;
		}
	
		$validate_add_comment =  $this->settings_model->validate_mail_template('add_comment');
		$validate_reply_comment =  $this->settings_model->validate_mail_template('reply_comment');
		$validate_publish_article =  $this->settings_model->validate_mail_template('publish_article');
		$validate_reject_article =  $this->settings_model->validate_mail_template('reject_article');
		$validate_add_plea =  $this->settings_model->validate_mail_template('add_plea');
		$validate_change_status =  $this->settings_model->validate_mail_template('change_status');
		 
		if($validate_add_comment != 1){
			showMessage('В шаблоне уведомления о комментарии на статью ошибочно написано или отсутствует слово %'.$validate_add_comment.'%');
			exit;
		}
		
		elseif($validate_reply_comment != 1){
			showMessage('В шаблоне уведомления об ответе на  комментарий ошибочно написано или отсутствует слово %'.$validate_reply_comment.'%');
			exit;
		}
		elseif($validate_publish_article != 1){
			showMessage('В шаблоне уведомления о публикации статьи ошибочно написано или отсутствует слово %'.$validate_publsih_article.'%');
			exit;
		}
		elseif($validate_reject_article != 1){
			showMessage('В шаблоне уведомления об отправке статьи на доработку ошибочно написано или отсутствует слово %'.$validate_reject_article.'%');
			exit;
		}
		elseif($validate_add_plea != 1){
			showMessage('В шаблоне уведомления о жалобе модератору ошибочно написано или отсутствует слово %'.$validate_add_plea.'%');
			exit;
		}
		elseif($validate_change_status != 1){
			showMessage('В шаблоне уведомления о смене статуса автора ошибочно написано или отсутствует слово %'.$validate_change_status.'%');
			exit;
		}
		else{
			$res = $this->settings_model->set_setting('mail_settings','main_email',$main_email);
			$res = $this->settings_model->set_mail_template('add_comment',$this->input->post('template_add_comment'));
			$res = $this->settings_model->set_mail_template('reply_comment',$this->input->post('template_reply_comment'));
			$res = $this->settings_model->set_mail_template('publish_article',$this->input->post('template_publish_article'));
			$res = $this->settings_model->set_mail_template('reject_article',$this->input->post('template_reject_article'));
			$res = $this->settings_model->set_mail_template('add_plea',$this->input->post('template_add_plea'));
			$res = $this->settings_model->set_mail_template('change_status',$this->input->post('template_change_status'));
		
		}
		
		
		if(!$res){
			showMessage('Неизвестная ошибка обновления!');
			exit;
		}
		else{
			showMessage('Параметры по настройкам почты успешно обновлены');
		}
	}
	
	function mail_settings()
	{
		$this->template->assign('main_email', $this->settings_model->get_setting('mail_settings', 'main_email'));
		$this->template->assign('template_add_comment', $this->settings_model->get_mail_template('add_comment'));
		$this->template->assign('template_add_plea', $this->settings_model->get_mail_template('add_plea'));
		$this->template->assign('template_reply_comment', $this->settings_model->get_mail_template('reply_comment'));
		$this->template->assign('template_publish_article', $this->settings_model->get_mail_template('publish_article'));
		$this->template->assign('template_reject_article', $this->settings_model->get_mail_template('reject_article'));
		$this->template->assign('template_change_status', $this->settings_model->get_mail_template('change_status'));

		echo $this->fetch_tpl('mail_settings');

	}
	
	function rating_system()
	{
			$this->template->assign('activity_day', $this->settings_model->get_setting('rating_activity', 'activity_day'));
			$this->template->assign('lowest_rating', $this->settings_model->get_setting('rating_activity', 'lowest_rating'));
			$this->template->assign('highest_rating', $this->settings_model->get_setting('rating_activity', 'highest_rating'));
			$this->template->assign('unactivity_day', $this->settings_model->get_setting('rating_activity', 'unactivity_day'));
			foreach($this->settings_model->type_headings as $type_heading){
				$rating_for_homefeed[$type_heading['id']] = $this->settings_model->get_setting('rating_author', 'rating_for_homefeed_'.$type_heading['id']);
				$rating_for_homefeed[$type_heading['id']]['show_key'] .= '. '.$type_heading['type'].' рубрики';
				$rating_for_homefeed[$type_heading['id']]['heading_id'] = $type_heading['id'];
			}
			$this->template->assign('rating_for_homefeed', $rating_for_homefeed);
			
			$this->template->assign('hold_for_homefeed', $this->settings_model->get_setting('rating_author', 'hold_for_homefeed'));
			$this->template->assign('add_for_homefeed', $this->settings_model->get_setting('rating_author', 'add_for_homefeed'));
			$this->template->assign('bonus_for_homefeed', $this->settings_model->get_setting('rating_author', 'bonus_for_homefeed'));
			$this->template->assign('type_headings', $this->settings_model->brief_type_headings);
			echo $this->fetch_tpl('rating_system');

	}
	
}

?>