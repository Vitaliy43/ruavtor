<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* The MX_Controller class is autoloaded as required */

/**
 * @property Core $core
 */
class MY_Controller extends MX_Controller {

	public $THEME;
	public $theme_name;
	protected $controller;
	protected $action;
	protected $main_headings = array();
	protected $add_headings = array();
	protected $all_headings = array();
	protected $hidden_headings = array();
	protected $heading;
	protected $cur_page;
	protected $author_fields = array();
	public $refer_expire = 86400;
	public $refer_add = 5184000;
	public $using_languages = array(
	'russian' => 'Русский',
	'english' => 'English'
	);
	public $main_language = 'russian';


    public function __construct()
    {
        parent::__construct();
		$this->load->model('dx_auth/users');
		$this->load->model('dx_auth/articler_users');
		$this->load->model('articler/settings_model');
		$this->load->router;
		$routes = $this->router->routes;
		$uriSegments = $this->uri->segment_array();	
		$old_uriSegments = $uriSegments;
		
		$find_symbol = preg_match_all('#\D+#',$uriSegments[2],$matches_symbol);
		$find_numeric = preg_match_all('#\d+#',$uriSegments[2],$matches_symbol);
		
		if(count($uriSegments) == 1 ){
			$this->heading = $uriSegments[1];	
		}
		elseif($uriSegments[1] == 'sandbox'){
			$this->heading = $uriSegments[2];	

		}
		elseif($find_numeric && !$find_symbol){
			$this->heading = $uriSegments[1];	

		}
		$route = array_complex_search($routes, $uriSegments[1], 'key');
		if($route):
		$uriSegments = explode('/',$routes[$route]);
			$add = -1;
		
		else:
		$add = 0;
		endif;
			
		if(count($uriSegments) == 0)
			{
				$this->controller = $routes['default_controller'];
				$this->action = 'index';
			}
		else{
			$this->controller = $uriSegments[1+$add];
	   		 $this->action = $uriSegments[2+$add];
		}
		
		
		$check_number = preg_match('#\d+#',$old_uriSegments[1],$regs);
		if($check_number){
			$this->action = 'index';
			$this->controller = 'articler';
			$this->cur_page = $old_uriSegments[1];
		}
		
			
		$this->db->order_by('name_russian','asc');
		$query = $this->db->get('headings');
		$this->all_headings = $query->result_array();
		
		foreach($query->result_array() as $row){
			
			if($row['type_heading'] == 1){
				$this->main_headings[] = $row;
			}
			elseif($row['type_heading'] == 2) {
				$this->add_headings[] = $row;
			}	
			else{
				$this->hidden_headings[] = $row;
			}		
			
		}
		
		foreach($this->all_headings as $heading){
			$heading_names[] = $heading['name'];
		}
		
		sort($this->main_headings);
		sort($this->add_headings);
		
		$uriSegments = $this->uri->segment_array();

		if(!$this->access){
			if(strpos($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_HOST']))
				$local_referer = true;
			else
				$local_referer = false;

			if(isset($_REQUEST['refer']) && !$this->is_refer_exceptions($uriSegments) && !$local_referer){
			$refer_user_id = (int)$_REQUEST['refer'];
			if($this->articler_users->isset_user_id($refer_user_id)){
				setcookie('refer_id',$refer_user_id,time()+$this->refer_expire,'/');
				setcookie('refer_link',request_url(),time()+$this->arefer_expire,'/');
				setcookie('refer_data',date("Y-m-d H:i:s"),time()+$this->refer_expire,'/');
			}
		}
		}
		
    }
	
	protected function init(){
		$this->main_language = $this->settings_model->get_setting('interface_settings', 'interface_language', 'property');
		$this->template->assign('TPL_PATH',$_SERVER['DOCUMENT_ROOT'].'/templates/'.$this->theme_name);	
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

}
